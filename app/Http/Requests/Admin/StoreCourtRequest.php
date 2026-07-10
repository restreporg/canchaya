<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'type'           => ['required', 'string', Rule::in(['Fútbol', 'Tenis', 'Basketball', 'Volleyball', 'Pádel'])],
            'price_per_hour' => 'required|numeric|min:0',
            'location'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'El nombre es obligatorio.',
            'type.required'           => 'El tipo de cancha es obligatorio.',
            'type.in'                 => 'Selecciona un tipo de cancha válido.',
            'price_per_hour.required' => 'El precio por hora es obligatorio.',
            'price_per_hour.numeric'  => 'El precio debe ser un número.',
            'price_per_hour.min'      => 'El precio no puede ser negativo.',
            'image.image'             => 'El archivo debe ser una imagen.',
            'image.max'               => 'La imagen no puede pesar más de 2MB.',
        ];
    }
}