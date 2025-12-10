<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $table = 'estoque';

    protected $fillable = [
        'insumo_id',
        'quantidade_atual',
        'localizacao',
    ];

    protected $casts = [
        'quantidade_atual' => 'decimal:6',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
