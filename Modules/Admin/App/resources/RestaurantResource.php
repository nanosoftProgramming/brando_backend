<?php

namespace Modules\Admin\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'is_active' => $this->is_active ?? null,
            'image' => $this->image ?? null,
            'min_time' => $this->min_time ?? null,
            'max_time' => $this->max_time ?? null,
            'branches_count' => $this->branches_count ?? null,
            'created_at' => $this->created_at?->format('Y-m-d h:i A') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d h:i A') ?? null,

        ];
    }
}
