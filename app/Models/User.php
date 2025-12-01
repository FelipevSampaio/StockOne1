<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'restaurante_id',
        'role',
        'last_login_at',
        'last_login_ip',
        'notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    /**
     * Get the restaurante associated with the user.
     */
    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    /**
     * Verificar se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar se o usuário é usuário comum
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Registrar o último login do usuário
     */
    public function recordLogin(?string $ip = null): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip(),
        ]);
    }

    /**
     * Verificar se o usuário está online (logou nos últimos 15 minutos)
     */
    public function isOnline(): bool
    {
        return $this->last_login_at && $this->last_login_at->gt(now()->subMinutes(15));
    }

    /**
     * Obter o status de presença do usuário
     */
    public function getPresenceStatus(): string
    {
        if (!$this->last_login_at) {
            return 'never';
        }

        if ($this->isOnline()) {
            return 'online';
        }

        if ($this->last_login_at->gt(now()->subHours(24))) {
            return 'away';
        }

        return 'offline';
    }

    /**
     * Obter o avatar initials
     */
    public function getAvatarInitialsAttribute(): string
    {
        return strtoupper(substr($this->name, 0, 2));
    }
}
