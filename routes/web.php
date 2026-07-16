<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CourtController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Client\ReservationController as ClientReservationController;
use App\Http\Controllers\Client\PaymentController as ClientPaymentController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'welcome']);

Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de ADMIN
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courts', CourtController::class);
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::delete('courts/{court}/image', [CourtController::class, 'destroyImage'])->name('courts.image.destroy');
    Route::resource('courts.schedules', ScheduleController::class)->except(['show']);
    Route::resource('reservations', AdminReservationController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('payments', AdminPaymentController::class)->only(['index', 'show', 'update']);
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
});

// Rutas de CLIENTE
Route::middleware('auth')->prefix('client')->name('client.')->group(function () {
    Route::resource('reservations', ClientReservationController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::get('reservations/{reservation}/payment', [ClientPaymentController::class, 'show'])->name('payments.show');
    Route::post('reservations/{reservation}/payment', [ClientPaymentController::class, 'store'])->name('payments.store');
    Route::post('reservations/{reservation}/reviews', [ClientReviewController::class, 'store'])->name('reviews.store');
    Route::delete('reviews/{review}', [ClientReviewController::class, 'destroy'])->name('reviews.destroy');
});

require __DIR__.'/auth.php';