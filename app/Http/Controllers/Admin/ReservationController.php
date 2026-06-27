<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateReservationRequest;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'court'])
            ->orderBy('start_datetime', 'desc')
            ->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'court', 'payment', 'review');
        return view('admin.reservations.show', compact('reservation'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $reservation->update(['status' => $request->status]);
        return redirect()->route('admin.reservations.show', $reservation)
                         ->with('success', 'Estado actualizado.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->route('admin.reservations.index')->with('success', 'Reserva eliminada.');
    }
}