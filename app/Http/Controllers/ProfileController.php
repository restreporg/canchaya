<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreReviewRequest;
use App\Models\Review;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreReviewRequest $request, Reservation $reservation)
    {
        // Reutilizamos la habilidad 'view' de ReservationPolicy: solo el dueño
        // de la reserva puede reseñarla.
        $this->authorize('view', $reservation);

        if ($reservation->status !== 'completada') {
            return back()->withErrors(['review' => 'Solo puedes reseñar reservas completadas.']);
        }

        if ($reservation->review) {
            return back()->withErrors(['review' => 'Ya dejaste una reseña para esta reserva.']);
        }

        Review::create([
            'reservation_id' => $reservation->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('client.reservations.show', $reservation)
            ->with('success', 'Reseña enviada.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();
        return back()->with('success', 'Reseña eliminada.');
    }
}