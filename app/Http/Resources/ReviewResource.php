<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'reservation_id' => $this->reservation_id,
            'user'           => new UserResource($this->whenLoaded('user')),
            'rating'         => $this->rating,
            'comment'        => $this->comment,
            'created_at'     => $this->created_at?->toIso8601String(),
        ];
    }
}
