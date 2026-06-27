<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_and_redirect_to_dashboard(): void
    {
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@test.com',
            'password' => bcrypt('password'),
            'role'     => 'admin',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_client_can_login_and_redirect_to_reservations(): void
    {
        $client = User::create([
            'name'     => 'Cliente',
            'email'    => 'cliente@test.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);

        $response = $this->post('/login', [
            'email'    => 'cliente@test.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('client.reservations.index'));
    }

    public function test_guest_cannot_access_admin(): void
    {
        $response = $this->get('/admin/courts');
        $response->assertRedirect('/login');
    }

    public function test_guest_cannot_access_client(): void
    {
        $response = $this->get('/client/reservations');
        $response->assertRedirect('/login');
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name'     => 'Usuario',
            'email'    => 'user@test.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);

        $this->actingAs($user);
        $response = $this->post('/logout');
        $response->assertRedirect('/');
    }
}