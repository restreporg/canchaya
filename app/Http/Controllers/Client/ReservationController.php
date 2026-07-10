<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreReservationRequest;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('court')
            ->orderBy('start_datetime', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('client.reservations.index', compact('reservations'));
    }

    public function create(Request $request)
    {
        $tipos = Court::where('is_active', true)
            ->distinct()
            ->pluck('type');

        $ubicaciones = Court::where('is_active', true)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location');

        $courts = Court::where('is_active', true)
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->location, fn($q) => $q->where('location', $request->location))
            ->get();

        return view('client.reservations.create', compact('courts', 'tipos', 'ubicaciones'));
    }

    public function store(StoreReservationRequest $request)
    {
        $reservation = DB::transaction(function () use ($request) {
            // Bloqueamos la fila de la cancha (que siempre existe) para que dos
            // requests concurrentes por la misma cancha se resuelvan una por una,
            // sin depender de "gap locks" que varían según el motor de base de datos.
            $court = Court::where('id', $request->court_id)->lockForUpdate()->firstOrFail();

            $conflict = Reservation::where('court_id', $request->court_id)
                ->where('status', '!=', 'cancelada')
                ->where(function ($q) use ($request) {
                    $q->where('start_datetime', '<', $request->end_datetime)
                      ->where('end_datetime', '>', $request->start_datetime);
                })
                ->exists();

            if ($conflict) {
                return null;
            }

            $hours = (strtotime($request->end_datetime) - strtotime($request->start_datetime)) / 3600;
            $total = $court->price_per_hour * $hours;

            return Reservation::create([
                'user_id'        => Auth::id(),
                'court_id'       => $request->court_id,
                'start_datetime' => $request->start_datetime,
                'end_datetime'   => $request->end_datetime,
                'total_price'    => $total,
                'status'         => 'pendiente',
            ]);
        });

        if (!$reservation) {
            return back()->withErrors([
                'start_datetime' => 'Ese horario ya fue reservado por otro cliente. Prueba con otro horario o cancha.',
            ]);
        }

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