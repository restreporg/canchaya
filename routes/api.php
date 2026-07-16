<?php

use App\Http\Controllers\Api\Admin\CourtController as AdminCourtController;
use App\Http\Controllers\Api\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Api\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Api\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Client\PaymentController as ClientPaymentController;
use App\Http\Controllers\Api\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\Api\Client\ReviewController as ClientReviewController;
use App\Http\Controllers\Api\CourtController;
use Illuminate\Support\Facades\Route;

// ---------- Públicas ----------
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/courts', [CourtController::class, 'index']);
Route::get('/courts/{court}', [CourtController::class, 'show']);

// ---------- Autenticadas (cualquier rol) ----------
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // ---------- Cliente ----------
    Route::prefix('client')->name('client.')->group(function () {
        Route::apiResource('reservations', ClientReservationController::class)
            ->only(['index', 'store', 'show', 'destroy']);

        Route::get('reservations/{reservation}/payment', [ClientPaymentController::class, 'show']);
        Route::post('reservations/{reservation}/payment', [ClientPaymentController::class, 'store']);

        Route::post('reservations/{reservation}/reviews', [ClientReviewController::class, 'store']);
        Route::delete('reviews/{review}', [ClientReviewController::class, 'destroy']);
    });

    // ---------- Admin ----------
    Route::middleware('isAdmin')->prefix('admin')->name('admin.')->group(function () {
        Route::apiResource('courts', AdminCourtController::class);
        Route::delete('courts/{court}/image', [AdminCourtController::class, 'destroyImage']);

        Route::apiResource('courts.schedules', AdminScheduleController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::apiResource('reservations', AdminReservationController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::apiResource('payments', AdminPaymentController::class)
            ->only(['index', 'show', 'update']);

        Route::apiResource('reviews', AdminReviewController::class)
            ->only(['index', 'show', 'destroy']);
    });
});
