<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['mesa_id', 'cliente', 'horario', 'status', 'confirmacao_automatica'];

    public function mesa()
    {
        return $this->belongsTo(Mesa::class);
    }
}
