<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreReviewRequest $request, Reservation $reservation)
    {
        // Solo el dueño de la reserva puede reseñarla.
        $this->authorize('view', $reservation);

        if ($reservation->status !== 'completada') {
            return response()->json([
                'message' => 'Solo puedes reseñar reservas completadas.',
                'errors'  => ['review' => ['Solo puedes reseñar reservas completadas.']],
            ], 422);
        }

        if ($reservation->review) {
            return response()->json([
                'message' => 'Ya dejaste una reseña para esta reserva.',
                'errors'  => ['review' => ['Ya dejaste una reseña para esta reserva.']],
            ], 422);
        }

        $review = Review::create([
            'reservation_id' => $reservation->id,
            'user_id'        => Auth::id(),
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        // Solo el autor de la reseña puede borrarla.
        if ($review->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar esta reseña.');
        }

        $review->delete();

        return response()->json(['message' => 'Reseña eliminada.']);
    }
}
