<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'login@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/home'); // Ajuste conforme sua rota pós-login
        $this->assertAuthenticatedAs($user);
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard'); // Ajuste conforme sua rota protegida
        $response->assertRedirect('/login');
    }
}
