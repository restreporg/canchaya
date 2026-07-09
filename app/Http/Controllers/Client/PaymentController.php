<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    use AuthorizesRequests;

    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        $payment = $reservation->payment;
        return view('client.payments.show', compact('reservation', 'payment'));
    }

    public function store(StorePaymentRequest $request, Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        if ($reservation->payment) {
            return back()->withErrors(['payment' => 'Esta reserva ya fue pagada.']);
        }

        if ($reservation->status === 'cancelada') {
            return back()->withErrors(['payment' => 'No se puede pagar una reserva cancelada.']);
        }

        Payment::create([
            'reservation_id' => $reservation->getKey(),
            'user_id'        => Auth::id(),
            'amount'         => $reservation->getAttribute('total_price'),
            'method'         => $request->input('method'),
            'status'         => 'pagado',
            'paid_at'        => now(),
        ]);

        $reservation->update(['status' => 'confirmada']);
        return redirect()->route('client.reservations.show', $reservation)
                         ->with('success', 'Pago realizado correctamente.');
    }
}