<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'start_datetime' => $this->start_datetime?->toIso8601String(),
            'end_datetime'   => $this->end_datetime?->toIso8601String(),
            'total_price'    => $this->total_price,
            'user'           => new UserResource($this->whenLoaded('user')),
            'court'          => new CourtResource($this->whenLoaded('court')),
            'payment'        => new PaymentResource($this->whenLoaded('payment')),
            'review'         => new ReviewResource($this->whenLoaded('review')),
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }
}
