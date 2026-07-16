<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'reservation.court'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return PaymentResource::collection($payments);
    }

    public function show(Payment $payment)
    {
        $payment->load('user', 'reservation.court');

        return new PaymentResource($payment);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update(['status' => $request->status]);

        return new PaymentResource($payment);
    }
}
