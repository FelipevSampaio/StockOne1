<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PedidoService
{
    /**
     * Cria um pedido com itens, baixa estoque e registra auditoria de forma transacional.
     *
     * @param array $pedidoData
     * @param array $itensData
     * @return Pedido
     * @throws Exception
     */
    public function criarPedidoComItens(array $pedidoData, array $itensData): Pedido
    {
        return DB::transaction(function () use ($pedidoData, $itensData) {
            $pedido = Pedido::create($pedidoData);

            foreach ($itensData as $itemData) {
                $itemData['pedido_id'] = $pedido->id;
                PedidoItem::create($itemData);
                // A baixa de estoque e auditoria continuam nos observers
            }

            // Aqui você pode disparar eventos customizados, se necessário
            // event(new PedidoCriado($pedido));

            return $pedido;
        });
    }
}
