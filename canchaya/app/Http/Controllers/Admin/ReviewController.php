<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // El admin ve todas las reseñas
    public function index()
    {
        $reviews = Review::with(['user', 'reservation.court'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    // Ver detalle de una reseña
    public function show(Review $review)
    {
        $review->load('user', 'reservation.court');
        return view('admin.reviews.show', compact('review'));
    }

    // El admin puede eliminar reseñas inapropiadas
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
                         ->with('success', 'Reseña eliminada.');
    }
}