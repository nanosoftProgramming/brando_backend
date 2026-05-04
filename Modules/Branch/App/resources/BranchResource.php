<?php

namespace Modules\Branch\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\App\Resources\OrderAdminForBranchResource;

class BranchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'restaurant' => $this->restaurant->name ?? null,
            'city' => $this->city->title ?? null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'block' => $this->block,
            'street' => $this->street,
            'building_number' => $this->building_number,
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
            'is_active' => $this->is_active,
            'orders' => OrderAdminForBranchResource::collection($this->whenLoaded('orders')),

        ];
    }
}
