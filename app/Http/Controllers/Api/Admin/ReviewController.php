<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'reservation.court'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return ReviewResource::collection($reviews);
    }

    public function show(Review $review)
    {
        $review->load('user', 'reservation.court');

        return new ReviewResource($review);
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return response()->json(['message' => 'Reseña eliminada.']);
    }
}
