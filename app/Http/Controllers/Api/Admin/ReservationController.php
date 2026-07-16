<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'court'])
            ->orderBy('start_datetime', 'desc')
            ->paginate(15);

        return ReservationResource::collection($reservations);
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('user', 'court', 'payment', 'review');

        return new ReservationResource($reservation);
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $reservation->update(['status' => $request->status]);

        return new ReservationResource($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return response()->json(['message' => 'Reserva eliminada.']);
    }
}
