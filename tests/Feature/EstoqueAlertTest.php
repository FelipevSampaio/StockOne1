<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Insumo;
use App\Models\Estoque;
use App\Models\Alerta;

class EstoqueAlertTest extends TestCase
{
    use RefreshDatabase;

    public function test_alerta_estoque_automatico()
    {
        $insumo = Insumo::factory()->create([
            'ponto_reposicao_minimo' => 10,
        ]);
        $estoque = Estoque::create([
            'insumo_id' => $insumo->id,
            'quantidade_atual' => 9,
        ]);
        $insumo->verificarBaixoEstoqueEAlertar();
        $this->assertDatabaseHas('alertas', [
            'insumo_id' => $insumo->id,
            'tipo_alerta' => 'baixo_estoque',
            'resolvido' => false,
        ]);
    }
}
