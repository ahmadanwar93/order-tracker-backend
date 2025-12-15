<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // in this case, there is actually very little sense to do a resource since all of the attribute is just straight up value
        // but just for future sake, the reason why resource is used instead of mode is that, API shape can be different from db schema
        // also allowing for versioning of the resource without changing models
        return [
            'id' => $this->id,
            'references' => $this->references,
            'status' => $this->status->value,
            'customer_name' => $this->customer_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
