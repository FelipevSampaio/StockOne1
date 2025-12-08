<?php

namespace Database\Factories;

use App\Models\Estoque;
use App\Models\Insumo;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstoqueFactory extends Factory
{
    protected $model = Estoque::class;

    public function definition(): array
    {
        return [
            'insumo_id' => Insumo::factory(),
            'quantidade_atual' => $this->faker->randomFloat(2, 5, 50),
        ];
    }
}
