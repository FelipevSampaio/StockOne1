<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Restaurante;
use App\Models\CardapioItem;
use App\Models\Insumo;
use App\Models\Estoque;
use App\Models\Receita;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoItemObserverTest extends TestCase
{
    use RefreshDatabase;

    public function test_observer_baixa_estoque_ao_criar_pedido_item()
    {
        $restaurante = Restaurante::factory()->create();
        $insumo = Insumo::factory()->create(['restaurante_id' => $restaurante->id, 'ponto_reposicao_minimo' => 2]);
        $estoque = Estoque::factory()->create(['insumo_id' => $insumo->id, 'quantidade_atual' => 10]);
        $item = CardapioItem::factory()->create(['restaurante_id' => $restaurante->id, 'preco_venda' => 20.0]);
        Receita::factory()->create([
            'cardapio_item_id' => $item->id,
            'insumo_id' => $insumo->id,
            'quantidade_necessaria' => 3,
        ]);
        $pedido = Pedido::factory()->create(['restaurante_id' => $restaurante->id, 'valor_total' => 20.0]);

        PedidoItem::create([
            'pedido_id' => $pedido->id,
            'cardapio_item_id' => $item->id,
            'quantidade' => 2,
            'preco_unitario' => 20.0,
            'observacao' => null,
        ]);

        $estoqueAtualizado = Estoque::find($estoque->id);
        $this->assertEquals(4, $estoqueAtualizado->quantidade_atual, 'O estoque deve ser baixado corretamente pelo observer.');
    }
}
