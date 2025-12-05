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

        // Estatísticas
        $stats = [
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
        ];

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
}
