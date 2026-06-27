<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        // Reserva 1 - completada con pago
        $res1 = Reservation::create([
            'user_id'        => 2,
            'court_id'       => 1,
            'start_datetime' => now()->subDays(5)->setHour(10)->setMinute(0),
            'end_datetime'   => now()->subDays(5)->setHour(12)->setMinute(0),
            'total_price'    => 100000,
            'status'         => 'completada',
        ]);

        Payment::create([
            'reservation_id' => $res1->id,
            'user_id'        => 2,
            'amount'         => 100000,
            'method'         => 'tarjeta',
            'status'         => 'pagado',
            'paid_at'        => now()->subDays(5),
        ]);

        // Reserva 2 - confirmada con pago
        $res2 = Reservation::create([
            'user_id'        => 3,
            'court_id'       => 2,
            'start_datetime' => now()->addDays(2)->setHour(14)->setMinute(0),
            'end_datetime'   => now()->addDays(2)->setHour(16)->setMinute(0),
            'total_price'    => 70000,
            'status'         => 'confirmada',
        ]);

        Payment::create([
            'reservation_id' => $res2->id,
            'user_id'        => 3,
            'amount'         => 70000,
            'method'         => 'efectivo',
            'status'         => 'pagado',
            'paid_at'        => now(),
        ]);

        // Reserva 3 - pendiente sin pago
        Reservation::create([
            'user_id'        => 4,
            'court_id'       => 3,
            'start_datetime' => now()->addDays(3)->setHour(9)->setMinute(0),
            'end_datetime'   => now()->addDays(3)->setHour(10)->setMinute(0),
            'total_price'    => 30000,
            'status'         => 'pendiente',
        ]);

        // Reserva 4 - cancelada
        Reservation::create([
            'user_id'        => 2,
            'court_id'       => 4,
            'start_datetime' => now()->subDays(2)->setHour(16)->setMinute(0),
            'end_datetime'   => now()->subDays(2)->setHour(17)->setMinute(0),
            'total_price'    => 40000,
            'status'         => 'cancelada',
        ]);
    }
}