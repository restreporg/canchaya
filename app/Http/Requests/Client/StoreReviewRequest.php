<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'La calificación es obligatoria.',
            'rating.between'  => 'La calificación debe ser entre 1 y 5.',
            'comment.max'     => 'El comentario no puede superar los 500 caracteres.',
        ];
    }
}