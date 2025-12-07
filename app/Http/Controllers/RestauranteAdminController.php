<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestauranteAdminController extends Controller {
    /**
     * Exibe alertas de baixo estoque e sugestões de compra para insumos do restaurante
     */
    public function estoqueInteligente(Restaurante $restaurante)
    {
        $this->checkAdmin();

        $insumos = $restaurante->insumos()->with(['estoque', 'alertas'])->get();
        $alertas = [];
        $sugestoes = [];

        // Gera alertas de validade próxima para todos insumos do restaurante
        \App\Models\Insumo::gerarAlertasVencimento(7);
        foreach ($insumos as $insumo) {
            // Verifica e dispara alerta automático se necessário
            $insumo->verificarBaixoEstoqueEAlertar();
            // Coleta alertas não resolvidos
            $alertasInsumo = $insumo->alertas()->where('resolvido', false)->get();
            if ($alertasInsumo->count() > 0) {
                $alertas = array_merge($alertas, $alertasInsumo->toArray());
            }
            // Gera sugestão de compra
            $sugestoes[] = $insumo->gerarSugestaoCompra(30);
        }

        return view('admin.restaurantes.estoque_inteligente', compact('restaurante', 'alertas', 'sugestoes'));
    }
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

        // Obter resultados
        $perPage = $request->get('per_page', 15);
        $restaurantes = $query->get();

        // Calcular Health Score para cada restaurante
        $restaurantes = $restaurantes->map(function ($restaurante) {
            $restaurante->health_data = $restaurante->calculateHealthScore();
            return $restaurante;
        });

        // Filtro por Health Score Risk
        if ($request->filled('health_risk')) {
            $risk = $request->get('health_risk');
            $restaurantes = $restaurantes->filter(function ($restaurante) use ($risk) {
                return $restaurante->health_data['risk'] === $risk;
            });
        }

        // Aplicar paginação manual após filtros
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $restaurantes = new \Illuminate\Pagination\LengthAwarePaginator(
            $restaurantes->forPage($currentPage, $perPage)->values(),
            $restaurantes->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        // Estatísticas
        $stats = [
            'total' => Restaurante::count(),
            'ativos' => Restaurante::where('status', 'ativo')->count(),
            'inativos' => Restaurante::where('status', 'inativo')->count(),
            'novos' => Restaurante::where('created_at', '>=', now()->subDays(7))->count(),
            'total_usuarios' => Restaurante::withCount('users')->get()->sum('users_count'),
            'media_usuarios' => round(Restaurante::withCount('users')->get()->avg('users_count'), 1),
        ];

        return view('admin.restaurantes.index', compact('restaurantes', 'stats'));
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
     * Toggle status do restaurante via AJAX
     */
    public function toggleStatus(Restaurante $restaurante)
    {
        $this->checkAdmin();

        try {
            $newStatus = $restaurante->status === 'ativo' ? 'inativo' : 'ativo';
            $restaurante->update(['status' => $newStatus]);

            // Contar usuários vinculados
            $usuariosCount = $restaurante->users()->count();

            // Se desativar, forçar logout de todos os usuários do restaurante
            if ($newStatus === 'inativo' && $usuariosCount > 0) {
                // Deletar todas as sessões dos usuários deste restaurante
                $userIds = $restaurante->users()->pluck('id')->toArray();

                if (!empty($userIds)) {
                    // Log para debug
                    \Log::info('Forçando logout dos usuários', [
                        'restaurante_id' => $restaurante->id,
                        'restaurante_nome' => $restaurante->nome,
                        'user_ids' => $userIds
                    ]);

                    // Remove sessões da tabela sessions
                    $deletedCount = DB::table('sessions')
                        ->whereNotNull('user_id')
                        ->whereIn('user_id', $userIds)
                        ->delete();

                    \Log::info('Sessões deletadas', ['count' => $deletedCount]);
                }
            }

            $message = $newStatus === 'ativo'
                ? "Restaurante ativado com sucesso!"
                : "Restaurante desativado com sucesso!" . ($usuariosCount > 0 ? " Os {$usuariosCount} usuário(s) vinculado(s) foram desconectados automaticamente." : "");

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $newStatus,
                'usuarios_afetados' => $usuariosCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Quick view - retornar dados do restaurante
     */
    public function quickView(Restaurante $restaurante)
    {
        $this->checkAdmin();

        $restaurante->load(['users' => function($query) {
            $query->select('id', 'name', 'email', 'restaurante_id')->limit(5);
        }]);

        return response()->json([
            'success' => true,
            'restaurante' => [
                'id' => $restaurante->id,
                'nome' => $restaurante->nome,
                'cnpj' => $restaurante->cnpj,
                'email' => $restaurante->email,
                'telefone' => $restaurante->telefone,
                'endereco' => $restaurante->endereco,
                'status' => $restaurante->status,
                'users_count' => $restaurante->users->count(),
                'users' => $restaurante->users,
                'created_at' => $restaurante->created_at->format('d/m/Y H:i'),
                'updated_at' => $restaurante->updated_at->format('d/m/Y H:i')
            ],
            'health_data' => $restaurante->calculateHealthScore(),
            'health_trend' => $restaurante->getHealthTrend(),
            'health_history' => $restaurante->getHealthHistory(30),
            'recommended_actions' => $restaurante->getRecommendedActions(),
            'lifecycle_stage' => $restaurante->getLifecycleStage()
        ]);
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
