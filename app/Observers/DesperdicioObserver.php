<?php

namespace App\Observers;

use App\Models\Desperdicio;

class DesperdicioObserver
{
    public function created(Desperdicio $desperdicio)
    {
        $insumo = $desperdicio->insumo;
        $estoque = $insumo->estoque;
        if ($estoque) {
            $estoque->decrement('quantidade_atual', $desperdicio->quantidade);
            $insumo->verificarBaixoEstoqueEAlertar();
        }
    }
}
