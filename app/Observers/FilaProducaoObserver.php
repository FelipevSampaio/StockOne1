<?php

namespace App\Observers;

use App\Models\FilaProducao;

class FilaProducaoObserver
{
    public function created(FilaProducao $filaProducao)
    {
        // Baixa dos insumos ao iniciar produção
        // Carregar relacionamentos necessários
        $filaProducao->load('pedidoItem.cardapioItem.receitas.insumo.estoque');

        $pedidoItem = $filaProducao->pedidoItem;
        if (!$pedidoItem) {
            return;
        }

        $cardapioItem = $pedidoItem->cardapioItem;
        if (!$cardapioItem) {
            return;
        }

        $receitas = $cardapioItem->receitas;
        if (!$receitas || $receitas->isEmpty()) {
            return;
        }

        $quantidade = $pedidoItem->quantidade ?? 1;

        foreach ($receitas as $receita) {
            $insumo = $receita->insumo;
            if (!$insumo) {
                continue;
            }

            $estoque = $insumo->estoque;
            if ($estoque) {
                $quantidadeNecessaria = ($receita->quantidade_necessaria ?? 0) * $quantidade;
                if ($quantidadeNecessaria > 0) {
                    $estoque->decrement('quantidade_atual', $quantidadeNecessaria);
                    $insumo->verificarBaixoEstoqueEAlertar();
                }
            }
        }
    }
}
