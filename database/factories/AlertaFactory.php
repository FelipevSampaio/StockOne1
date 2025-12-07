<?php

namespace Database\Factories;

use App\Models\Alerta;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlertaFactory extends Factory
{
    protected $model = Alerta::class;

    public function definition()
    {
        return [
            'insumo_id' => null, // Defina no teste
            'tipo_alerta' => 'baixo_estoque',
            'mensagem' => $this->faker->sentence,
            'data_hora_alerta' => now(),
            'visualizado' => false,
            'resolvido' => false,
        ];
    }
}
