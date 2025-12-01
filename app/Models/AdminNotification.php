<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    /**
     * Marcar notificação como lida
     */
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Verificar se está lida
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Tempo relativo
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->locale('pt_BR')->diffForHumans();
    }

    /**
     * Criar notificação
     */
    public static function createNotification(string $type, string $title, string $message, array $data = [])
    {
        return self::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }
}
