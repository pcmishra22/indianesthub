<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_admin_login_page_loads()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
        $response->assertSee('Login');
    }

    public function test_admin_can_login()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $response = $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin, 'admin');
    }

    public function test_admin_dashboard_access()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_admin_users_list()
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $this->actingAs($admin, 'admin');
        $response = $this->get('/admin/users');
        $response->assertStatus(200);
        $response->assertSee('Users');
    }
}
