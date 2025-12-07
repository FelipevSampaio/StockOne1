<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desperdicio extends Model
{
    protected $fillable = [
        'insumo_id',
        'quantidade',
        'motivo',
        'data',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }
}
