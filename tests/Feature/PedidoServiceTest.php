<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\CardapioItem;
use App\Models\Restaurante;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_cria_pedido_com_itens_e_baixa_estoque()
    {
        $restaurante = Restaurante::factory()->create();
        $item = CardapioItem::factory()->create(['restaurante_id' => $restaurante->id, 'preco_venda' => 10.0]);

        $pedidoData = [
            'restaurante_id' => $restaurante->id,
            'usuario_id' => null,
            'numero_pedido_externo' => '123456',
            'plataforma_origem' => 'web',
            'data_hora_pedido' => now(),
            'status' => 'pendente',
            'valor_total' => 10.0,
            'tempo_preparo_estimado' => null,
        ];
        $itensData = [[
            'cardapio_item_id' => $item->id,
            'quantidade' => 1,
            'preco_unitario' => 10.0,
            'observacao' => null,
        ]];

        $service = new PedidoService();
        $pedido = $service->criarPedidoComItens($pedidoData, $itensData);

        $this->assertDatabaseHas('pedidos', ['id' => $pedido->id, 'valor_total' => 10.0]);
        $this->assertDatabaseHas('pedido_itens', ['pedido_id' => $pedido->id, 'cardapio_item_id' => $item->id]);
    }
}
