<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
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

        return new PaymentResource($reservation->payment);
    }

    public function store(StorePaymentRequest $request, Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        if ($reservation->payment) {
            return response()->json([
                'message' => 'Esta reserva ya fue pagada.',
                'errors'  => ['payment' => ['Esta reserva ya fue pagada.']],
            ], 422);
        }

        if ($reservation->status === 'cancelada') {
            return response()->json([
                'message' => 'No se puede pagar una reserva cancelada.',
                'errors'  => ['payment' => ['No se puede pagar una reserva cancelada.']],
            ], 422);
        }

        $payment = Payment::create([
            'reservation_id' => $reservation->getKey(),
            'user_id'        => Auth::id(),
            'amount'         => $reservation->getAttribute('total_price'),
            'method'         => $request->input('method'),
            'status'         => 'pagado',
            'paid_at'        => now(),
        ]);

        $reservation->update(['status' => 'confirmada']);

        return new PaymentResource($payment);
    }
}
