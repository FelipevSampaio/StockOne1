<?php

namespace Database\Factories;

use App\Models\Receita;
use App\Models\CardapioItem;
use App\Models\Insumo;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReceitaFactory extends Factory
{
    protected $model = Receita::class;

    public function definition(): array
    {
        return [
            'cardapio_item_id' => CardapioItem::factory(),
            'insumo_id' => Insumo::factory(),
            'quantidade_necessaria' => $this->faker->randomFloat(2, 1, 5),
            'essencial' => true,
        ];
    }
}
