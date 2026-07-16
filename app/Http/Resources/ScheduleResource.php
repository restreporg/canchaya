<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'court_id'     => $this->court_id,
            'day_of_week'  => $this->day_of_week,
            'open_time'    => $this->open_time,
            'close_time'   => $this->close_time,
            'is_available' => (bool) $this->is_available,
        ];
    }
}
