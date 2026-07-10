<?php

namespace App\Http\Requests\Client;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'court_id' => [
                'required',
                Rule::exists('courts', 'id')->where('is_active', true),
            ],
            'start_datetime' => 'required|date|after:now',
            'end_datetime'   => 'required|date|after:start_datetime',
        ];
    }

    public function messages(): array
    {
        return [
            'court_id.required'       => 'Debes seleccionar una cancha.',
            'court_id.exists'         => 'La cancha seleccionada no existe o no está disponible.',
            'start_datetime.required' => 'La fecha de inicio es obligatoria.',
            'start_datetime.after'    => 'La fecha de inicio debe ser en el futuro.',
            'end_datetime.required'   => 'La fecha de fin es obligatoria.',
            'end_datetime.after'      => 'La fecha de fin debe ser después de la de inicio.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if (!$this->court_id || !$this->start_datetime || !$this->end_datetime) {
                return;
            }

            $start = Carbon::parse($this->start_datetime);
            $end   = Carbon::parse($this->end_datetime);

            if (!$start->isSameDay($end)) {
                $validator->errors()->add(
                    'end_datetime',
                    'La reserva debe iniciar y terminar el mismo día.'
                );
                return;
            }

            $schedule = Schedule::where('court_id', $this->court_id)
                ->where('day_of_week', $start->dayOfWeek)
                ->first();

            if (!$schedule || !($schedule->is_available ?? true)) {
                $validator->errors()->add(
                    'start_datetime',
                    'La cancha no tiene horario disponible ese día.'
                );
                return;
            }

            $openTime  = Carbon::parse($start->toDateString() . ' ' . $schedule->open_time);
            $closeTime = Carbon::parse($start->toDateString() . ' ' . $schedule->close_time);

            if ($start->lt($openTime) || $end->gt($closeTime)) {
                $validator->errors()->add(
                    'start_datetime',
                    "El horario debe estar entre {$schedule->open_time} y {$schedule->close_time}."
                );
            }
        });
    }
}