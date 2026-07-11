<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCourtTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::forceCreate([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }

    private function clientUser(): User
    {
        return User::forceCreate([
            'name' => 'Cliente',
            'email' => 'cliente@test.com',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);
    }

    public function test_admin_can_see_courts_list(): void
    {
        $this->actingAs($this->adminUser());
        $response = $this->get(route('admin.courts.index'));
        $response->assertStatus(200);
    }

    public function test_client_cannot_access_admin_courts(): void
    {
        $this->actingAs($this->clientUser());
        $response = $this->get(route('admin.courts.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_create_court(): void
    {
        $this->actingAs($this->adminUser());

        $response = $this->post(route('admin.courts.store'), [
            'name' => 'Cancha Test',
            'type' => 'Fútbol',
            'price_per_hour' => 50000,
            'location' => 'Bloque A',
            'description' => 'Descripción test',
        ]);

        $response->assertRedirect(route('admin.courts.index'));
        $this->assertDatabaseHas('courts', ['name' => 'Cancha Test']);
    }

    public function test_admin_can_update_court(): void
    {
        $this->actingAs($this->adminUser());

        $court = Court::create([
            'name' => 'Cancha Original',
            'type' => 'Tenis',
            'price_per_hour' => 30000,
            'is_active' => true,
        ]);

        $response = $this->put(route('admin.courts.update', $court), [
            'name' => 'Cancha Actualizada',
            'type' => 'Tenis',
            'price_per_hour' => 35000,
            'is_active' => true,
        ]);

        $response->assertRedirect(route('admin.courts.index'));
        $this->assertDatabaseHas('courts', ['name' => 'Cancha Actualizada']);
    }

    public function test_admin_can_deactivate_court(): void
    {
        $this->actingAs($this->adminUser());

        $court = Court::create([
            'name' => 'Cancha Activa',
            'type' => 'Fútbol',
            'price_per_hour' => 50000,
            'is_active' => true,
        ]);

        $response = $this->delete(route('admin.courts.destroy', $court));
        $response->assertRedirect(route('admin.courts.index'));
        $this->assertDatabaseHas('courts', ['id' => $court->id, 'is_active' => false]);
    }
}