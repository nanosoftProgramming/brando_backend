<?php

namespace Modules\Client\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\App\Resources\OrderResource;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'image' => $this->image,
            'is_active' => $this->is_active,
            'allow_notification' => $this->allow_notification,
            'created_at' => $this->created_at?->format('Y-m-d'),
            'updated_at' => $this->updated_at?->format('Y-m-d'),
            'addresses' => AddressResource::collection($this->whenLoaded('addresses')),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),

        ];
    }
}
