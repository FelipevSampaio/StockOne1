<?php

namespace App\Observers;

use App\Models\CompraSugestao;

class CompraSugestaoObserver
{
    public function created(CompraSugestao $compraSugestao)
    {
        $insumo = $compraSugestao->insumo;
        $estoque = $insumo->estoque;
        if ($estoque) {
            $estoque->increment('quantidade_atual', $compraSugestao->quantidade_comprada);
        }
    }
}
