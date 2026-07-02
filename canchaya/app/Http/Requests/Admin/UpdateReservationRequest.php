<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pendiente,confirmada,cancelada,completada',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'El estado es obligatorio.',
            'status.in'       => 'El estado no es válido.',
        ];
    }
}