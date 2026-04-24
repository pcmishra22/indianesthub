<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => '1234567890',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'testuser@example.com']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('Password123!'),
        ]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'Password123!',
        ]);
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }
}
