<?php

namespace Database\Factories;

use App\Models\CardapioItem;
use App\Models\Restaurante;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardapioItemFactory extends Factory
{
    protected $model = CardapioItem::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->word,
            'descricao' => $this->faker->sentence,
            'preco_venda' => $this->faker->randomFloat(2, 5, 100),
            'restaurante_id' => Restaurante::factory(),
            'ativo_online' => true,
            'disponibilidade' => true,
        ];
    }
}
