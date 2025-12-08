<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Restaurante;
use App\Models\CardapioItem;
use App\Models\Insumo;
use App\Models\Estoque;
use App\Models\Receita;
use App\Services\PedidoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PedidoBaixaEstoqueTest extends TestCase
{
    use RefreshDatabase;

    public function test_baixa_estoque_ao_criar_pedido()
    {
        $restaurante = Restaurante::factory()->create();
        $insumo = Insumo::factory()->create(['restaurante_id' => $restaurante->id, 'ponto_reposicao_minimo' => 2]);
        $estoque = Estoque::factory()->create(['insumo_id' => $insumo->id, 'quantidade_atual' => 10]);
        $item = CardapioItem::factory()->create(['restaurante_id' => $restaurante->id, 'preco_venda' => 20.0]);


        // Cria a receita associada ao item e recarrega o item para garantir persistência
        $item->receitas()->create([
            'insumo_id' => $insumo->id,
            'quantidade_necessaria' => 3,
            'essencial' => true,
        ]);
        $item->refresh();

        $pedidoData = [
            'restaurante_id' => $restaurante->id,
            'usuario_id' => null,
            'numero_pedido_externo' => '654321',
            'plataforma_origem' => 'web',
            'data_hora_pedido' => now(),
            'status' => 'pendente',
            'valor_total' => 20.0,
            'tempo_preparo_estimado' => null,
        ];
        $itensData = [[
            'cardapio_item_id' => $item->id,
            'quantidade' => 2,
            'preco_unitario' => 20.0,
            'observacao' => null,
        ]];

        $service = new PedidoService();
        $service->criarPedidoComItens($pedidoData, $itensData);

        $estoque->refresh();
        $this->assertEquals(4, $estoque->quantidade_atual, 'O estoque deve ser baixado corretamente.');
    }
}
