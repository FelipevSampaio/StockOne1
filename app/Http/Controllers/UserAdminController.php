<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Restaurante;
use App\Models\AuditLog;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserAdminController extends Controller
{
    /**
     * Verificar se o usuário é administrador
     */
    private function checkAdmin()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }
    }

    /**
     * Exibir lista de todos os usuários
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = User::withTrashed()->with('restaurante');

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Filtro por restaurante
        if ($request->has('restaurante_id')) {
            if ($request->get('restaurante_id') === 'null' || $request->get('restaurante_id') === '') {
                // Filtrar usuários sem restaurante
                $query->whereNull('restaurante_id');
            } elseif ($request->filled('restaurante_id')) {
                // Filtrar por restaurante específico
                $query->where('restaurante_id', $request->get('restaurante_id'));
            }
        }

        // Filtro por papel
        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        // Filtro por status
        if ($request->filled('status')) {
            if ($request->get('status') === 'ativo') {
                $query->whereNull('deleted_at');
            } elseif ($request->get('status') === 'inativo') {
                $query->whereNotNull('deleted_at');
            }
        }

        // Paginação com quantidade personalizável
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage)->withQueryString();
        $restaurantes = Restaurante::all();

        // Estatísticas
        $totalUsers = User::count();
        $totalActive = User::whereNull('deleted_at')->count();
        $totalInactive = User::whereNotNull('deleted_at')->count();
        $totalAdmins = User::where('role', 'admin')->whereNull('deleted_at')->count();
        $newUsersWeek = User::where('created_at', '>=', now()->subDays(7))->count();

        return view('admin.users.index', compact('users', 'restaurantes', 'totalUsers', 'totalActive', 'totalInactive', 'totalAdmins', 'newUsersWeek'));
    }

    /**
     * Exibir formulário de criação
     */
    public function create()
    {
        $this->checkAdmin();
        $restaurantes = Restaurante::all();
        return view('admin.users.create', compact('restaurantes'));
    }

    /**
     * Guardar novo usuário
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'restaurante_id' => ['required', 'exists:restaurantes,id'],
            'role' => ['required', 'in:admin,user'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        // Registrar auditoria
        AuditLog::log('create', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'restaurante_id' => $user->restaurante_id,
        ]);

        // Criar notificação
        AdminNotification::createNotification(
            'user_created',
            'Novo usuário cadastrado',
            "{$user->name} foi adicionado ao sistema como " . ($user->role === 'admin' ? 'administrador' : 'usuário'),
            ['user_id' => $user->id, 'user_name' => $user->name]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Exibir formulário de edição
     */
    public function edit(User $user)
    {
        $this->checkAdmin();
        $restaurantes = Restaurante::all();
        return view('admin.users.edit', compact('user', 'restaurantes'));
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'restaurante_id' => ['required', 'exists:restaurantes,id'],
            'role' => ['required', 'in:admin,user'],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        // Capturar mudanças
        $changes = [];
        foreach ($validated as $key => $value) {
            if ($user->$key !== $value) {
                $changes[$key] = [
                    'old' => $user->$key,
                    'new' => $value,
                ];
            }
        }

        $user->update($validated);

        // Registrar auditoria se houve mudanças
        if (!empty($changes)) {
            AuditLog::log('update', 'User', $user->id, $changes);

            // Criar notificação
            AdminNotification::createNotification(
                'user_updated',
                'Usuário atualizado',
                "As informações de {$user->name} foram atualizadas",
                ['user_id' => $user->id, 'changes' => array_keys($changes)]
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Deletar usuário (soft delete)
     */
    public function destroy(User $user)
    {
        $this->checkAdmin();
        $user->delete();

        // Registrar auditoria
        AuditLog::log('delete', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        // Criar notificação
        AdminNotification::createNotification(
            'user_deleted',
            'Usuário desativado',
            "{$user->name} foi desativado do sistema",
            ['user_id' => $user->id, 'user_name' => $user->name]
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário desativado com sucesso!');
    }

    /**
     * Reativar usuário deletado
     */
    public function restore($id)
    {
        $this->checkAdmin();
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        // Registrar auditoria
        AuditLog::log('restore', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário reativado com sucesso!');
    }

    /**
     * Deletar permanentemente usuário
     */
    public function forceDelete($id)
    {
        $this->checkAdmin();
        $user = User::withTrashed()->findOrFail($id);

        // Registrar auditoria antes de deletar
        AuditLog::log('force_delete', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
        ]);

        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Usuário deletado permanentemente!');
    }

    /**
     * Exportar usuários para CSV
     */
    public function export(Request $request)
    {
        $this->checkAdmin();

        $query = User::withTrashed()->with('restaurante');

        // Aplicar mesmos filtros da listagem
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        if ($request->filled('restaurante_id')) {
            $query->where('restaurante_id', $request->get('restaurante_id'));
        }

        if ($request->filled('role')) {
            $query->where('role', $request->get('role'));
        }

        if ($request->filled('status')) {
            if ($request->get('status') === 'ativo') {
                $query->whereNull('deleted_at');
            } elseif ($request->get('status') === 'inativo') {
                $query->whereNotNull('deleted_at');
            }
        }

        $users = $query->get();

        // Registrar auditoria
        AuditLog::log('export', 'User', 0, [
            'total_exported' => $users->count(),
            'filters' => $request->only(['search', 'restaurante_id', 'role', 'status'])
        ]);

        $filename = 'usuarios_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabeçalhos
            fputcsv($file, [
                'ID',
                'Nome',
                'Email',
                'Restaurante',
                'Papel',
                'Status',
                'Criado em',
                'Atualizado em',
                'Deletado em'
            ], ';');

            // Dados
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->restaurante ? $user->restaurante->nome : 'N/A',
                    $user->role === 'admin' ? 'Administrador' : 'Usuário',
                    $user->deleted_at ? 'Inativo' : 'Ativo',
                    $user->created_at->format('d/m/Y H:i:s'),
                    $user->updated_at->format('d/m/Y H:i:s'),
                    $user->deleted_at ? $user->deleted_at->format('d/m/Y H:i:s') : '-'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Busca em tempo real (AJAX)
     */
    public function liveSearch(Request $request)
    {
        $this->checkAdmin();

        $search = $request->get('q', '');
        $limit = $request->get('limit', 10);

        $users = User::withTrashed()
            ->with('restaurante')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit($limit)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'restaurante' => $user->restaurante?->nome ?? 'N/A',
                    'role' => $user->role,
                    'is_active' => !$user->trashed(),
                    'avatar_initials' => strtoupper(substr($user->name, 0, 2))
                ];
            });

        return response()->json($users);
    }

    /**
     * Visualização rápida (Quick View)
     */
    public function quickView(User $user)
    {
        $this->checkAdmin();

        $user->load('restaurante');

        // Buscar últimas atividades relacionadas ao usuário
        $recentLogs = AuditLog::where(function($query) use ($user) {
                $query->where('model_type', 'User')
                      ->where('model_id', $user->id);
            })
            ->orWhere('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        try {
            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'restaurante' => $user->restaurante?->nome ?? 'N/A',
                    'restaurante_id' => $user->restaurante_id,
                    'is_active' => $user->deleted_at === null,
                    'created_at' => $user->created_at->format('d/m/Y H:i'),
                    'updated_at' => $user->updated_at->format('d/m/Y H:i'),
                    'deleted_at' => $user->deleted_at ? $user->deleted_at->format('d/m/Y H:i') : null,
                    'created_diff' => $user->created_at->diffForHumans(),
                    'avatar_initials' => strtoupper(substr($user->name, 0, 2))
                ],
                'recent_logs' => $recentLogs->map(function($log) {
                    return [
                        'action' => $log->action,
                        'description' => $log->action . ' - ' . $log->model_type,
                        'created_at' => $log->created_at->diffForHumans()
                    ];
                })->toArray()
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro no quickView: ' . $e->getMessage());
            return response()->json([
                'error' => 'Erro ao carregar dados do usuário',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ações em massa
     */
    public function bulkAction(Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $validated['user_ids'];
        $action = $validated['action'];
        $count = 0;

        foreach ($userIds as $userId) {
            $user = User::withTrashed()->find($userId);
            if (!$user) continue;

            switch ($action) {
                case 'activate':
                    if ($user->trashed()) {
                        $user->restore();
                        $count++;
                        AuditLog::log('restore', 'User', $user->id, ['name' => $user->name]);
                    }
                    break;
                case 'deactivate':
                    if (!$user->trashed()) {
                        $user->delete();
                        $count++;
                        AuditLog::log('soft_delete', 'User', $user->id, ['name' => $user->name]);
                    }
                    break;
                case 'delete':
                    $user->forceDelete();
                    $count++;
                    AuditLog::log('force_delete', 'User', $user->id, ['name' => $user->name]);
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} usuário(s) processado(s) com sucesso",
            'count' => $count
        ]);
    }
}

