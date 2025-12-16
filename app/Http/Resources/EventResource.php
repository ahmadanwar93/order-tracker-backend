<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // the resource might not be necessary
        return [
            'uuid' => $this->uuid,
            'sequence' => $this->sequence,
            'event_type' => $this->event_type,
            'payload' => $this->payload,
            'created_at' => $this->created_at,
        ];
    }
}
