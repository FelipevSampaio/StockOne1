<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurante_id',
        'nome',
        'email',
        'telefone',
        'cargo',
        'status',
        'data_admissao',
        'data_desligamento',
    ];

    protected $casts = [
        'data_admissao' => 'date',
        'data_desligamento' => 'date',
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }
}
