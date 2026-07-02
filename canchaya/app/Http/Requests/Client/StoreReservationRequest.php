<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'court_id'       => 'required|exists:courts,id',
            'start_datetime' => 'required|date|after:now',
            'end_datetime'   => 'required|date|after:start_datetime',
        ];
    }

    public function messages(): array
    {
        return [
            'court_id.required'       => 'Debes seleccionar una cancha.',
            'court_id.exists'         => 'La cancha seleccionada no existe.',
            'start_datetime.required' => 'La fecha de inicio es obligatoria.',
            'start_datetime.after'    => 'La fecha de inicio debe ser en el futuro.',
            'end_datetime.required'   => 'La fecha de fin es obligatoria.',
            'end_datetime.after'      => 'La fecha de fin debe ser después de la de inicio.',
        ];
    }
}