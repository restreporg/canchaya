<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'day_of_week' => 'required|integer|between:0,6',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
            'is_available' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'day_of_week.required' => 'El día de la semana es obligatorio.',
            'day_of_week.between' => 'El día debe ser un número entre 0 (domingo) y 6 (sábado).',
            'open_time.required' => 'La hora de apertura es obligatoria.',
            'close_time.required' => 'La hora de cierre es obligatoria.',
            'close_time.after' => 'La hora de cierre debe ser posterior a la de apertura.',
        ];
    }
}