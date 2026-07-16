<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CourtResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'type'           => $this->type,
            'price_per_hour' => $this->price_per_hour,
            'location'       => $this->location,
            'description'    => $this->description,
            'is_active'      => (bool) $this->is_active,
            'image_url'      => $this->image ? Storage::disk('public')->url($this->image) : null,
            'schedules'      => ScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
