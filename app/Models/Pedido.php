<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurante_id',
        'usuario_id',
        'comanda_id',
        'numero_pedido_externo',
        'plataforma_origem',
        'data_hora_pedido',
        'status',
        'valor_total',
        'tempo_preparo_estimado',
    ];
    public function comanda()
    {
        return $this->belongsTo(Comanda::class);
    }

    protected $casts = [
        'data_hora_pedido' => 'datetime',
        'valor_total' => 'decimal:2',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function itens()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function filaProducao()
    {
        return $this->hasMany(FilaProducao::class);
    }
}
