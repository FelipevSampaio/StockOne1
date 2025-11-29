<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Restaurante;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usando factories com relacionamentos para criar restaurantes e seus usuários
        Restaurante::factory()
            ->has(User::factory()->state([
                'name' => 'Usuário A',
                'email' => 'usuario-a@example.com',
                'role' => UserRole::USER,
            ]), 'users')
            ->create([
                'nome' => 'Restaurante A',
                'cnpj' => '12.345.678/0001-90',
                'email' => 'restaurante-a@example.com',
            ]);

        Restaurante::factory()
            ->has(User::factory()->state([
                'name' => 'Usuário B',
                'email' => 'usuario-b@example.com',
                'role' => UserRole::USER,
            ]), 'users')
            ->create([
                'nome' => 'Restaurante B',
                'cnpj' => '98.765.432/0001-09',
                'email' => 'restaurante-b@example.com',
            ]);

        // Criar usuário administrador (sem restaurante específico)
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'role' => UserRole::ADMIN,
        ]);
    }
}
