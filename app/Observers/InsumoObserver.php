<?php

namespace App\Observers;

use App\Models\Insumo;
use App\Models\Estoque;

class InsumoObserver
{
    /**
     * Evento disparado ao criar um insumo
     */
    public function created(Insumo $insumo)
    {
        // Cria registro de estoque para o novo insumo
        Estoque::create([
            'insumo_id' => $insumo->id,
            'quantidade_atual' => 0,
            'localizacao' => null,
        ]);
    }

    /**
     * Evento disparado ao deletar um insumo
     */
    public function deleted(Insumo $insumo)
    {
        // Remove registro de estoque vinculado
        if ($insumo->estoque) {
            $insumo->estoque->delete();
        }
    }
}
