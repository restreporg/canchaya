<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function show(Reservation $reservation)
    {
        $payment = $reservation->payment;
        return view('client.payments.show', compact('reservation', 'payment'));
    }

    public function store(StorePaymentRequest $request, Reservation $reservation)
    {
        if ($reservation->payment) {
            return back()->withErrors(['payment' => 'Esta reserva ya fue pagada.']);
        }

        Payment::create([
            'reservation_id' => $reservation->id,
            'user_id'        => Auth::id(),
            'amount'         => $reservation->total_price,
            'method'         => $request->method,
            'status'         => 'pagado',
            'paid_at'        => now(),
        ]);

        $reservation->update(['status' => 'confirmada']);
        return redirect()->route('client.reservations.show', $reservation)
                         ->with('success', 'Pago realizado correctamente.');
    }
}