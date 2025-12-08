<?php

namespace Database\Factories;

use App\Models\Restaurante;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestauranteFactory extends Factory
{
    protected $model = Restaurante::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->company,
            'cnpj' => $this->faker->numerify('##.###.###/####-##'),
            'endereco' => $this->faker->address,
            'telefone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'status' => 'ativo',
        ];
    }
}
