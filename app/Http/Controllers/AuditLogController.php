<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuditLogController extends Controller
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
     * Exibir lista de logs de auditoria
     */
    public function index(Request $request)
    {
        $this->checkAdmin();

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Filtro por busca geral (ID, modelo, usuário)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('model', 'like', "%{$search}%")
                  ->orWhere('model_id', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro por ação
        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        // Filtro por modelo
        if ($request->filled('model')) {
            $query->where('model', $request->get('model'));
        }

        // Filtro por usuário
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        // Filtro por período pré-definido
        if ($request->filled('period')) {
            $period = $request->get('period');
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', today()->subDay());
                    break;
                case 'last_7_days':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year);
                    break;
                case 'last_month':
                    $query->whereMonth('created_at', now()->subMonth()->month)
                          ->whereYear('created_at', now()->subMonth()->year);
                    break;
            }
        }

        // Filtro por data customizada
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Quantidade por página
        $perPage = $request->get('per_page', 20);

        $logs = $query->paginate($perPage)->appends($request->except('page'));
        $users = \App\Models\User::orderBy('name')->get();

        // Estatísticas (com cache de 5 minutos)
        $stats = \Cache::remember('audit_logs_stats', 300, function() {
            return [
                'total' => AuditLog::count(),
                'today' => AuditLog::whereDate('created_at', today())->count(),
                'this_week' => AuditLog::where('created_at', '>=', now()->startOfWeek())->count(),
                'this_month' => AuditLog::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)
                                        ->count(),
                'by_action' => [
                    'create' => AuditLog::where('action', 'create')->count(),
                    'update' => AuditLog::where('action', 'update')->count(),
                    'delete' => AuditLog::where('action', 'delete')->count(),
                    'restore' => AuditLog::where('action', 'restore')->count(),
                ],
                'top_users' => AuditLog::selectRaw('user_id, COUNT(*) as total')
                                       ->whereNotNull('user_id')
                                       ->groupBy('user_id')
                                       ->orderByDesc('total')
                                       ->limit(5)
                                       ->with('user')
                                       ->get(),
                'top_models' => AuditLog::selectRaw('model, COUNT(*) as total')
                                        ->groupBy('model')
                                        ->orderByDesc('total')
                                        ->limit(5)
                                        ->get(),
                'by_hour' => AuditLog::selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                                     ->whereDate('created_at', '>=', now()->subDays(7))
                                     ->groupBy('hour')
                                     ->pluck('count', 'hour'),
            ];
        });

        // Modelos únicos para filtro
        $models = AuditLog::distinct('model')->pluck('model')->sort()->values();

        // If AJAX request, return only the table fragment (for faster pagination)
        if ($request->ajax()) {
            return view('admin.partials.audit-logs-table', compact('logs'));
        }

        return view('admin.audit-logs', compact('logs', 'users', 'stats', 'models'));
    }

    /**
     * Remove a single audit log
     */
    public function destroy($id, Request $request)
    {
        $this->checkAdmin();

        $log = AuditLog::find($id);
        if (!$log) {
            if ($request->ajax()) {
                return response()->json(['message' => 'Log não encontrado.'], 404);
            }
            return redirect()->back()->with('error', 'Log não encontrado.');
        }

        $log->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Log apagado com sucesso.']);
        }

        return redirect()->back()->with('success', 'Log apagado com sucesso.');
    }

    /**
     * Bulk delete audit logs (expects `ids` array in POST body)
     */
    public function bulkDestroy(Request $request)
    {
        $this->checkAdmin();

        $data = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|distinct',
        ]);

        $ids = $data['ids'];
        $deleted = AuditLog::whereIn('id', $ids)->delete();

        if ($request->ajax()) {
            return response()->json(['message' => "Apagados: {$deleted} registros."]);
        }

        return redirect()->back()->with('success', "Apagados: {$deleted} registros.");
    }

    /**
     * Exportar logs de auditoria para CSV
     */
    public function export(Request $request)
    {
        $this->checkAdmin();

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Aplicar mesmos filtros da listagem
        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        if ($request->filled('model')) {
            $query->where('model', $request->get('model'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->get('user_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        $logs = $query->get();

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // BOM para UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Cabeçalhos
            fputcsv($file, [
                'ID',
                'Usuário',
                'Ação',
                'Modelo',
                'ID do Registro',
                'Mudanças',
                'IP',
                'User Agent',
                'Data'
            ], ';');

            // Dados
            foreach ($logs as $log) {
                $changes = is_array($log->changes) ? json_encode($log->changes, JSON_UNESCAPED_UNICODE) : $log->changes;

                fputcsv($file, [
                    $log->id,
                    $log->user ? $log->user->name : 'Sistema',
                    $log->action,
                    $log->model,
                    $log->model_id,
                    $changes,
                    $log->ip_address ?? '-',
                    $log->user_agent ?? '-',
                    $log->created_at->format('d/m/Y H:i:s')
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show detailed view of a log
     */
    public function show($id)
    {
        $this->checkAdmin();

        $log = AuditLog::with('user')->findOrFail($id);

        return view('admin.partials.audit-log-details', compact('log'));
    }

    /**
     * Revert a log change
     */
    public function revert($id, Request $request)
    {
        $this->checkAdmin();

        $log = AuditLog::findOrFail($id);

        if (!$log->changes || !isset($log->changes['old'])) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível reverter este log.'
            ], 400);
        }

        try {
            // Find the model instance
            $modelClass = $log->model;
            $model = $modelClass::find($log->model_id);

            if (!$model) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registro não encontrado.'
                ], 404);
            }

            // Restore old values
            $changes = is_string($log->changes) ? json_decode($log->changes, true) : $log->changes;
            $model->update($changes['old']);

            // Log the revert action
            AuditLog::create([
                'user_id' => auth()->id(),
                'model' => $log->model,
                'model_id' => $log->model_id,
                'action' => 'restore',
                'changes' => json_encode([
                    'reverted_log_id' => $log->id,
                    'restored_values' => $changes['old']
                ]),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mudança revertida com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao reverter: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add note to a log
     */
    public function addNote($id, Request $request)
    {
        $this->checkAdmin();

        $validated = $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        $log = AuditLog::findOrFail($id);

        // Add note to changes
        $changes = is_string($log->changes) ? json_decode($log->changes, true) : $log->changes;
        $changes['admin_notes'] = $changes['admin_notes'] ?? [];
        $changes['admin_notes'][] = [
            'note' => $validated['note'],
            'added_by' => auth()->user()->name,
            'added_at' => now()->toDateTimeString()
        ];

        $log->update(['changes' => $changes]);

        return response()->json([
            'success' => true,
            'message' => 'Nota adicionada com sucesso!'
        ]);
    }

    /**
     * Get analytics data for charts
     */
    public function analytics(Request $request)
    {
        $this->checkAdmin();

        $period = $request->get('period', '7d');

        $days = match($period) {
            '7d' => 7,
            '30d' => 30,
            '90d' => 90,
            default => 7
        };

        $data = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = AuditLog::whereDate('created_at', $date)->count();
            $data[] = [
                'date' => $date->format('d/m'),
                'count' => $count
            ];
        }

        return response()->json($data);
    }

    /**
     * Advanced search within changes
     */
    public function advancedSearch(Request $request)
    {
        $this->checkAdmin();

        $searchTerm = $request->get('q');

        if (empty($searchTerm)) {
            return response()->json([]);
        }

        // Search in changes JSON
        $logs = AuditLog::with('user')
            ->where(function($query) use ($searchTerm) {
                $query->where('changes', 'like', "%{$searchTerm}%")
                      ->orWhere('model', 'like', "%{$searchTerm}%")
                      ->orWhere('model_id', 'like', "%{$searchTerm}%")
                      ->orWhereHas('user', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%{$searchTerm}%")
                            ->orWhere('email', 'like', "%{$searchTerm}%");
                      });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'model' => class_basename($log->model),
                    'model_id' => $log->model_id,
                    'user' => $log->user->name ?? 'Sistema',
                    'date' => $log->created_at->format('d/m/Y H:i'),
                    'url' => route('admin.audit-logs.index', ['search' => $log->id])
                ];
            });

        return response()->json($logs);
    }

    /**
     * Get complete audit trail for a specific record
     */
    public function trail(Request $request)
    {
        $this->checkAdmin();

        $model = $request->get('model');
        $modelId = $request->get('model_id');

        if (empty($model) || empty($modelId)) {
            return response()->json(['error' => 'Model e Model ID são obrigatórios'], 400);
        }

        $trail = AuditLog::with('user')
            ->where('model', $model)
            ->where('model_id', $modelId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'model' => class_basename($model),
            'model_id' => $modelId,
            'total_changes' => $trail->count(),
            'timeline' => $trail->groupBy(function($log) {
                return $log->created_at->format('Y-m-d');
            })
        ]);
    }

    /**
     * Clear stats cache
     */
    public function clearCache(Request $request)
    {
        $this->checkAdmin();

        \Cache::forget('audit_logs_stats');

        return response()->json([
            'success' => true,
            'message' => 'Cache limpo com sucesso!'
        ]);
    }

    /**
     * Export to PDF
     */
    public function exportPdf(Request $request)
    {
        $this->checkAdmin();

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        if ($request->filled('model')) {
            $query->where('model', $request->get('model'));
        }

        $logs = $query->limit(500)->get(); // Limit for PDF

        $pdf = \PDF::loadView('admin.audit-logs-pdf', compact('logs'));

        return $pdf->download('audit_logs_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export logs to Excel/CSV
     */
    public function exportExcel(Request $request)
    {
        $this->checkAdmin();

        $query = AuditLog::with('user')->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->filled('action')) {
            $query->where('action', $request->get('action'));
        }

        if ($request->filled('model')) {
            $query->where('model', $request->get('model'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->limit(1000)->get();

        $filename = 'audit-logs-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header
            fputcsv($file, ['ID', 'Ação', 'Modelo', 'ID do Modelo', 'Usuário', 'IP', 'Data/Hora']);

            // Data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    ucfirst($log->action),
                    $log->model ?? 'N/A',
                    $log->model_id ?? 'N/A',
                    $log->user->name ?? 'Sistema',
                    $log->ip_address ?? 'N/A',
                    $log->created_at->format('d/m/Y H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
