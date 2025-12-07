<?php

namespace App\Observers;

use App\Models\FilaProducao;
use App\Models\Receita;

class FilaProducaoObserver
{
    public function created(FilaProducao $filaProducao)
    {
        // Baixa dos insumos ao iniciar produção
        $receitas = $filaProducao->cardapioItem->receitas;
        foreach ($receitas as $receita) {
            $insumo = $receita->insumo;
            $estoque = $insumo->estoque;
            if ($estoque) {
                $estoque->decrement('quantidade_atual', $receita->quantidade_necessaria * $filaProducao->quantidade);
                $insumo->verificarBaixoEstoqueEAlertar();
            }
        }
    }
}
