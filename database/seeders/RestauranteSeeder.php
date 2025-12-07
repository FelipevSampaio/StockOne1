<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RestauranteSeeder extends Seeder
{
    public function run()
    {
        $restaurante = Restaurante::create([
            'nome' => 'Restaurante Teste',
            'status' => 'ativo',
        ]);

        $user = User::where('email', 'admin@teste.com')->first();
        if ($user) {
            $user->update(['restaurante_id' => $restaurante->id]);
        } else {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@teste.com',
                'password' => Hash::make('senha123'),
                'restaurante_id' => $restaurante->id,
            ]);
        }
    }
}
