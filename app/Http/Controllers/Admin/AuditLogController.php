<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query()->with('user');

        // Filtros
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        $logs = $query->orderByDesc('created_at')->paginate(20);
        $users = User::pluck('name', 'id');

        return view('admin.audit-log.index', [
            'logs' => $logs,
            'users' => $users,
            'request' => $request,
        ]);
    }
    public function edit($id)
    {
        $log = AuditLog::findOrFail($id);
        return view('admin.audit-log.edit', compact('log'));
    }
}
