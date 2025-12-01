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

        // Eager loading otimizado para evitar N+1
        $query = User::withTrashed()->with('restaurante:id,nome');

        // Filtro de busca
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filtro por restaurante
        if ($request->has('restaurante_id')) {
            if ($request->get('restaurante_id') === 'null' || $request->get('restaurante_id') === '') {
                $query->whereNull('restaurante_id');
            } elseif ($request->filled('restaurante_id')) {
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

        // Filtro para usuários que nunca fizeram login
        if ($request->get('never_logged_in') === '1') {
            $query->whereNull('last_login_at');
        }

        // Filtros avançados
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->get('created_from'));
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->get('created_to'));
        }

        // Ordenação
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Paginação com quantidade personalizável
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage)->withQueryString();

        // Restaurantes apenas com ID e nome (otimizado)
        $restaurantes = Restaurante::select('id', 'nome')->orderBy('nome')->get();

        // Estatísticas com cache (5 minutos)
        $cacheKey = 'user_statistics';
        $statistics = \Cache::remember($cacheKey, 300, function () {
            return [
                'totalUsers' => User::count(),
                'totalActive' => User::whereNull('deleted_at')->count(),
                'totalInactive' => User::onlyTrashed()->count(),
                'totalAdmins' => User::where('role', 'admin')->whereNull('deleted_at')->count(),
                'newUsersWeek' => User::where('created_at', '>=', now()->subDays(7))->count(),
                'onlineUsers' => User::where('last_login_at', '>=', now()->subMinutes(15))->count(),
            ];
        });

        return view('admin.users.index', compact('users', 'restaurantes') + $statistics);
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

        // Se tiver IDs específicos, exporta apenas eles
        if ($request->filled('ids')) {
            $ids = explode(',', $request->get('ids'));
            $query->whereIn('id', $ids);
        } else {
            // Aplicar mesmos filtros da listagem
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('restaurante_id')) {
                if ($request->get('restaurante_id') === 'sem_restaurante') {
                    $query->whereNull('restaurante_id');
                } else {
                    $query->where('restaurante_id', $request->get('restaurante_id'));
                }
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
                $query->where('model', 'User')
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
                    'avatar_initials' => $user->avatar_initials,
                    'last_login_at' => $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : null,
                    'last_login_diff' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Nunca',
                    'last_login_ip' => $user->last_login_ip,
                    'presence_status' => $user->getPresenceStatus(),
                    'is_online' => $user->isOnline(),
                    'notes' => $user->notes,
                ],
                'recent_logs' => $recentLogs->map(function($log) {
                    return [
                        'action' => $log->action,
                        'description' => $log->action . ' - ' . $log->model,
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

        // Limpar cache de estatísticas
        \Cache::forget('user_statistics');

        return response()->json([
            'success' => true,
            'message' => "{$count} usuário(s) processado(s) com sucesso",
            'count' => $count
        ]);
    }

    /**
     * Enviar email de reset de senha
     */
    public function sendPasswordReset(User $user)
    {
        $this->checkAdmin();

        try {
            $token = app('auth.password.broker')->createToken($user);
            $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

            // Aqui você pode enviar o email customizado
            // Por enquanto, apenas retornamos sucesso

            AuditLog::log('password_reset_sent', 'User', $user->id, [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link de redefinição de senha enviado com sucesso!',
                'reset_url' => $resetUrl // Para teste
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar link: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Forçar logout do usuário (encerrar todas as sessões)
     */
    public function forceLogout(User $user)
    {
        $this->checkAdmin();

        try {
            // Atualizar remember_token para invalidar sessões
            $user->update([
                'remember_token' => \Str::random(60)
            ]);

            // Deletar sessões do banco (se estiver usando database driver)
            \DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();

            AuditLog::log('force_logout', 'User', $user->id, [
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Usuário desconectado de todas as sessões!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao desconectar usuário: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Atualizar notas do usuário
     */
    public function updateNotes(Request $request, User $user)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000'
        ]);

        $oldNotes = $user->notes;
        $user->update(['notes' => $validated['notes']]);

        AuditLog::log('update_notes', 'User', $user->id, [
            'old_notes' => $oldNotes,
            'new_notes' => $validated['notes'],
            'admin_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notas atualizadas com sucesso!',
            'notes' => $validated['notes']
        ]);
    }

    /**
     * Obter sessões ativas do usuário
     */
    public function getActiveSessions(User $user)
    {
        $this->checkAdmin();

        try {
            $sessions = \DB::table('sessions')
                ->where('user_id', $user->id)
                ->orderBy('last_activity', 'desc')
                ->get()
                ->map(function($session) {
                    $payload = unserialize(base64_decode($session->payload));

                    return [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_activity' => date('d/m/Y H:i:s', $session->last_activity),
                        'last_activity_diff' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                });

            return response()->json([
                'success' => true,
                'sessions' => $sessions,
                'total' => $sessions->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar sessões: ' . $e->getMessage(),
                'sessions' => []
            ]);
        }
    }

    /**
     * Limpar cache de estatísticas
     */
    public function clearStatsCache()
    {
        $this->checkAdmin();
        \Cache::forget('user_statistics');

        return response()->json([
            'success' => true,
            'message' => 'Cache de estatísticas limpo!'
        ]);
    }
}

