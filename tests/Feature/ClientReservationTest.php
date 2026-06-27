<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientReservationTest extends TestCase
{
    use RefreshDatabase;

    private function clientUser(): User
    {
        return User::create([
            'name'     => 'Cliente',
            'email'    => 'cliente@test.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);
    }

    private function court(): Court
    {
        return Court::create([
            'name'           => 'Cancha Test',
            'type'           => 'Fútbol',
            'price_per_hour' => 50000,
            'is_active'      => true,
        ]);
    }

    public function test_client_can_see_reservations(): void
    {
        $this->actingAs($this->clientUser());
        $response = $this->get(route('client.reservations.index'));
        $response->assertStatus(200);
    }

    public function test_client_can_create_reservation(): void
    {
        $client = $this->clientUser();
        $court  = $this->court();

        $this->actingAs($client);

        $response = $this->post(route('client.reservations.store'), [
            'court_id'       => $court->id,
            'start_datetime' => now()->addDay()->setHour(10)->format('Y-m-d H:i'),
            'end_datetime'   => now()->addDay()->setHour(12)->format('Y-m-d H:i'),
        ]);

        $response->assertRedirect(route('client.reservations.index'));
        $this->assertDatabaseHas('reservations', [
            'user_id'  => $client->id,
            'court_id' => $court->id,
        ]);
    }

    public function test_client_cannot_see_other_client_reservation(): void
    {
        $client1 = $this->clientUser();
        $client2 = User::create([
            'name'     => 'Cliente 2',
            'email'    => 'cliente2@test.com',
            'password' => bcrypt('password'),
            'role'     => 'client',
        ]);

        $court = $this->court();

        $reservation = Reservation::create([
            'user_id'        => $client2->id,
            'court_id'       => $court->id,
            'start_datetime' => now()->addDay()->setHour(10),
            'end_datetime'   => now()->addDay()->setHour(12),
            'total_price'    => 100000,
            'status'         => 'pendiente',
        ]);

        $this->actingAs($client1);
        $response = $this->get(route('client.reservations.show', $reservation));
        $response->assertStatus(403);
    }

    public function test_client_can_cancel_own_reservation(): void
    {
        $client = $this->clientUser();
        $court  = $this->court();

        $reservation = Reservation::create([
            'user_id'        => $client->id,
            'court_id'       => $court->id,
            'start_datetime' => now()->addDay()->setHour(10),
            'end_datetime'   => now()->addDay()->setHour(12),
            'total_price'    => 100000,
            'status'         => 'pendiente',
        ]);

        $this->actingAs($client);
        $response = $this->delete(route('client.reservations.destroy', $reservation));
        $response->assertRedirect(route('client.reservations.index'));
        $this->assertDatabaseHas('reservations', ['id' => $reservation->id, 'status' => 'cancelada']);
    }
}