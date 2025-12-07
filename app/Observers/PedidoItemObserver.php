<?php

namespace App\Observers;

use App\Models\PedidoItem;
use App\Models\Receita;

class PedidoItemObserver
{
    /**
     * Evento disparado ao criar um item de pedido (venda de produto)
     */
    public function created(PedidoItem $pedidoItem)
    {
        // Baixa automática dos insumos usados na receita do produto vendido
        $receitas = $pedidoItem->cardapioItem->receitas;
        foreach ($receitas as $receita) {
            $insumo = $receita->insumo;
            $estoque = $insumo->estoque;
            if ($estoque) {
                $estoque->decrement('quantidade_atual', $receita->quantidade_necessaria * $pedidoItem->quantidade);
                $insumo->verificarBaixoEstoqueEAlertar();
            }
        }

        // Auditoria: registrar criação de item de pedido
        \App\Models\AuditLog::log(
            'created',
            'PedidoItem',
            $pedidoItem->id,
            json_encode($pedidoItem->toArray())
        );
    }

    /**
     * Evento disparado ao atualizar um item de pedido
     */
    public function updated(PedidoItem $pedidoItem)
    {
        \App\Models\AuditLog::log(
            'updated',
            'PedidoItem',
            $pedidoItem->id,
            json_encode($pedidoItem->getChanges())
        );
    }

    /**
     * Evento disparado ao excluir um item de pedido
     */
    public function deleted(PedidoItem $pedidoItem)
    {
        \App\Models\AuditLog::log(
            'deleted',
            'PedidoItem',
            $pedidoItem->id,
            json_encode($pedidoItem->toArray())
        );
    }
}
