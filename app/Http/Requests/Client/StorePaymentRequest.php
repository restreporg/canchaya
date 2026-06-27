<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'method' => 'required|in:efectivo,tarjeta,transferencia',
        ];
    }

    public function messages(): array
    {
        return [
            'method.required' => 'El método de pago es obligatorio.',
            'method.in'       => 'El método de pago no es válido.',
        ];
    }
}