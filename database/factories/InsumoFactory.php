<?php

namespace Database\Factories;

use App\Models\Insumo;
use Illuminate\Database\Eloquent\Factories\Factory;

class InsumoFactory extends Factory
{
    protected $model = Insumo::class;

    public function definition()
    {
        return [
            'restaurante_id' => 1,
            'nome' => $this->faker->word,
            'descricao' => $this->faker->sentence,
            'categoria' => 'geral',
            'unidade_medida' => 'kg',
            'ponto_reposicao_minimo' => 10,
            'custo_unitario' => 5.00,
            'data_validade_minima' => now()->addMonth(),
        ];
    }
}
