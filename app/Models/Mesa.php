<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $fillable = ['numero', 'status'];

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function comandas()
    {
        return $this->hasMany(Comanda::class);
    }
}
