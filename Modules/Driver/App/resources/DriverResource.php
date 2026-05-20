<?php

namespace Modules\Driver\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\App\Resources\OrderAdminResource;

class DriverResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image,
            'license_id' => $this->license_id,
            'is_active' => $this->is_active,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'area' => $this->area,
            'block' => $this->block,
            'street' => $this->street,
            'house_number' => $this->house_number,
            'notes' => $this->notes,
            'allow_notification' => $this->allow_notification,
            'branch_id' => $this->branch_id,
            'branch_name' => optional($this->branch)->name,
            'city_name' => optional($this->city)->name,
            'restaurant_name' => optional($this->restaurant)->name,
            'city_id' => $this->city_id,
            'restaurant_id' => $this->restaurant_id,
            'role' => $this->getRoleNames()->first(),
            'created_at' => $this->created_at->format('Y-m-d h:i A'),
            'updated_at' => $this->created_at->format('Y-m-d h:i A'),
            'orders' => OrderAdminResource::collection($this->whenLoaded('orders')),
        ];
    }
}
