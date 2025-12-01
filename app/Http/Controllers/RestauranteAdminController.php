<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestauranteAdminController extends Controller
{
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = Restaurante::withCount('users');

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('nome', 'like', "%{$search}%")
                  ->orWhere('cnpj', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Paginação com quantidade personalizável
        $perPage = $request->get('per_page', 15);
        $restaurantes = $query->paginate($perPage)->withQueryString();

        return view('admin.restaurantes.index', compact('restaurantes'));
    }

    public function create()
    {
        $this->checkAdmin();
        return view('admin.restaurantes.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:restaurantes'],
            'email' => ['required', 'email', 'unique:restaurantes'],
            'endereco' => ['nullable', 'string'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:ativo,inativo'],
        ]);

        Restaurante::create($validated);

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante criado com sucesso!');
    }

    public function edit(Restaurante $restaurante)
    {
        $this->checkAdmin();
        return view('admin.restaurantes.edit', compact('restaurante'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cnpj' => ['required', 'string', 'max:18', 'unique:restaurantes,cnpj,' . $restaurante->id],
            'email' => ['required', 'email', 'unique:restaurantes,email,' . $restaurante->id],
            'endereco' => ['nullable', 'string'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'in:ativo,inativo'],
        ]);

        $restaurante->update($validated);

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante atualizado com sucesso!');
    }

    public function destroy(Restaurante $restaurante)
    {
        $this->checkAdmin();
        $restaurante->delete();

        return redirect()->route('admin.restaurantes.index')
            ->with('success', 'Restaurante deletado com sucesso!');
    }

    /**
     * Ações em massa para restaurantes
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,export',
            'ids' => 'required|array',
            'ids.*' => 'exists:restaurantes,id'
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');

        try {
            switch ($action) {
                case 'activate':
                    Restaurante::whereIn('id', $ids)->update(['status' => 'ativo']);
                    $message = count($ids) . ' restaurante(s) ativado(s) com sucesso!';
                    break;

                case 'deactivate':
                    Restaurante::whereIn('id', $ids)->update(['status' => 'inativo']);
                    $message = count($ids) . ' restaurante(s) desativado(s) com sucesso!';
                    break;

                case 'delete':
                    // Verificar se algum restaurante tem usuários vinculados
                    $withUsers = Restaurante::whereIn('id', $ids)->withCount('users')->having('users_count', '>', 0)->count();

                    if ($withUsers > 0) {
                        return response()->json([
                            'success' => false,
                            'message' => "Não é possível excluir {$withUsers} restaurante(s) com usuários vinculados."
                        ], 422);
                    }

                    Restaurante::whereIn('id', $ids)->delete();
                    $message = count($ids) . ' restaurante(s) excluído(s) com sucesso!';
                    break;

                case 'export':
                    return $this->exportSelected($ids);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar ação: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Exportar restaurantes selecionados para CSV
     */
    private function exportSelected($ids)
    {
        $restaurantes = Restaurante::withCount('users')->whereIn('id', $ids)->get();

        $filename = 'restaurantes_selecionados_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($restaurantes) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabeçalhos
            fputcsv($file, [
                'ID',
                'Nome',
                'CNPJ',
                'Email',
                'Telefone',
                'Endereço',
                'Status',
                'Total de Usuários',
                'Criado em',
                'Atualizado em'
            ], ';');

            // Dados
            foreach ($restaurantes as $restaurante) {
                fputcsv($file, [
                    $restaurante->id,
                    $restaurante->nome,
                    $restaurante->cnpj,
                    $restaurante->email,
                    $restaurante->telefone ?? '-',
                    $restaurante->endereco ?? '-',
                    $restaurante->status === 'ativo' ? 'Ativo' : 'Inativo',
                    $restaurante->users_count,
                    $restaurante->created_at->format('d/m/Y H:i:s'),
                    $restaurante->updated_at->format('d/m/Y H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Exportar todos os restaurantes para CSV
     */
    public function export(Request $request)
    {
        $this->checkAdmin();

        $restaurantes = Restaurante::withCount('users')->get();

        $filename = 'restaurantes_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($restaurantes) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabeçalhos
            fputcsv($file, [
                'ID',
                'Nome',
                'CNPJ',
                'Email',
                'Telefone',
                'Endereço',
                'Status',
                'Total de Usuários',
                'Criado em',
                'Atualizado em'
            ], ';');

            // Dados
            foreach ($restaurantes as $restaurante) {
                fputcsv($file, [
                    $restaurante->id,
                    $restaurante->nome,
                    $restaurante->cnpj,
                    $restaurante->email,
                    $restaurante->telefone ?? '-',
                    $restaurante->endereco ?? '-',
                    $restaurante->status === 'ativo' ? 'Ativo' : 'Inativo',
                    $restaurante->users_count,
                    $restaurante->created_at->format('d/m/Y H:i:s'),
                    $restaurante->updated_at->format('d/m/Y H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
