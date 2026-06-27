<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePaymentRequest;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'reservation.court'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment)
    {
        $payment->load('user', 'reservation.court');
        return view('admin.payments.show', compact('payment'));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $payment->update(['status' => $request->status]);
        return redirect()->route('admin.payments.show', $payment)
                         ->with('success', 'Pago actualizado.');
    }
}