<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreReservationRequest;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('court')
            ->orderBy('start_datetime', 'desc')
            ->get();
        return view('client.reservations.index', compact('reservations'));
    }

    public function create()
    {
        $courts = Court::where('is_active', true)->get();
        return view('client.reservations.create', compact('courts'));
    }

    public function store(StoreReservationRequest $request)
    {
        $conflict = Reservation::where('court_id', $request->court_id)
            ->where('status', '!=', 'cancelada')
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_datetime', [$request->start_datetime, $request->end_datetime])
                  ->orWhereBetween('end_datetime', [$request->start_datetime, $request->end_datetime]);
            })->exists();

        if ($conflict) {
            return back()->withErrors(['start_datetime' => 'La cancha no está disponible en ese horario.']);
        }

        $court = Court::findOrFail($request->court_id);
        $hours = (strtotime($request->end_datetime) - strtotime($request->start_datetime)) / 3600;
        $total = $court->price_per_hour * $hours;

        Reservation::create([
            'user_id'        => Auth::id(),
            'court_id'       => $request->court_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime'   => $request->end_datetime,
            'total_price'    => $total,
            'status'         => 'pendiente',
        ]);

        return redirect()->route('client.reservations.index')->with('success', 'Reserva creada.');
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        $reservation->load('court', 'payment', 'review');
        return view('client.reservations.show', compact('reservation'));
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->update(['status' => 'cancelada']);
        return redirect()->route('client.reservations.index')->with('success', 'Reserva cancelada.');
    }
}