<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Restaurante;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        return [
            'restaurante_id' => Restaurante::factory(),
            'usuario_id' => null,
            'numero_pedido_externo' => $this->faker->unique()->numerify('PED###'),
            'plataforma_origem' => 'web',
            'data_hora_pedido' => now(),
            'status' => 'pendente',
            'valor_total' => $this->faker->randomFloat(2, 10, 100),
            'tempo_preparo_estimado' => null,
        ];
    }
}
