<?php

namespace App\Observers;

use App\Models\Estoque;

class EstoqueObserver
{
    /**
     * Evento disparado ao criar um registro de estoque
     */
    public function created(Estoque $estoque)
    {
        \App\Models\AuditLog::log(
            'created',
            'Estoque',
            $estoque->id,
            json_encode($estoque->toArray())
        );
    }

    /**
     * Evento disparado ao atualizar um registro de estoque
     */
    public function updated(Estoque $estoque)
    {
        \App\Models\AuditLog::log(
            'updated',
            'Estoque',
            $estoque->id,
            json_encode($estoque->getChanges())
        );
    }

    /**
     * Evento disparado ao excluir um registro de estoque
     */
    public function deleted(Estoque $estoque)
    {
        \App\Models\AuditLog::log(
            'deleted',
            'Estoque',
            $estoque->id,
            json_encode($estoque->toArray())
        );
    }
}
