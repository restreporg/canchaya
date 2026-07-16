<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->with('court', 'payment', 'review')
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);

        return ReservationResource::collection($reservations);
    }

    /**
     * Misma lógica que el flujo web: bloquea la cancha, revisa solapamiento
     * de horarios y calcula el precio total antes de crear la reserva.
     */
    public function store(StoreReservationRequest $request)
    {
        $reservation = DB::transaction(function () use ($request) {
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

        if (! $reservation) {
            return response()->json([
                'message' => 'Ese horario ya fue reservado por otro cliente. Prueba con otro horario o cancha.',
                'errors'  => ['start_datetime' => ['Ese horario ya no está disponible.']],
            ], 422);
        }

        return new ReservationResource($reservation->load('court'));
    }

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        $reservation->load('court', 'payment', 'review');

        return new ReservationResource($reservation);
    }

    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);
        $reservation->update(['status' => 'cancelada']);

        return response()->json(['message' => 'Reserva cancelada.']);
    }
}
