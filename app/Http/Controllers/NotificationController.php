<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Listar notificações
     */
    public function index()
    {
        $notifications = AdminNotification::orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'read_at' => $notification->read_at,
                    'time_ago' => $notification->time_ago,
                    'created_at' => $notification->created_at->toISOString()
                ];
            });

        $unreadCount = AdminNotification::whereNull('read_at')->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Marcar como lida
     */
    public function markAsRead($id)
    {
        $notification = AdminNotification::findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Marcar todas como lidas
     */
    public function markAllAsRead()
    {
        AdminNotification::whereNull('read_at')->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
