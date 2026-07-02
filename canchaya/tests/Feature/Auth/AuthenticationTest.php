<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);

        $response = $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('client.reservations.index'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);

        $this->post('/login', [
            'email'    => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::create([
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);

        $this->actingAs($user);
        $response = $this->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/');
    }
}