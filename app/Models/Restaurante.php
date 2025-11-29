<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'cnpj',
        'endereco',
        'telefone',
        'email',
        'status',
    ];

    public function insumos()
    {
        return $this->hasMany(Insumo::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function cardapioItens()
    {
        return $this->hasMany(CardapioItem::class);
    }

    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
