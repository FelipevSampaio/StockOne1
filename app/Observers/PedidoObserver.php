<?php

namespace App\Observers;

use App\Models\Pedido;

class PedidoObserver
{
    /**
     * Evento disparado ao criar um pedido
     */
    public function created(Pedido $pedido)
    {
        \App\Models\AuditLog::log(
            'created',
            'Pedido',
            $pedido->id,
            json_encode($pedido->toArray())
        );
    }

    /**
     * Evento disparado ao atualizar um pedido
     */
    public function updated(Pedido $pedido)
    {
        \App\Models\AuditLog::log(
            'updated',
            'Pedido',
            $pedido->id,
            json_encode($pedido->getChanges())
        );
    }

    /**
     * Evento disparado ao excluir um pedido
     */
    public function deleted(Pedido $pedido)
    {
        \App\Models\AuditLog::log(
            'deleted',
            'Pedido',
            $pedido->id,
            json_encode($pedido->toArray())
        );
    }
}
