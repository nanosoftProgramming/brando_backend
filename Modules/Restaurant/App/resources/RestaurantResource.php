<?php

namespace Modules\Restaurant\App\resources;

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
            'branches_count' => $this->branches_count,
            'created_at' => $this->created_at?->format('Y-m-d h:i A') ?? null,
            'updated_at' => $this->updated_at?->format('Y-m-d h:i A') ?? null,
            'manager' => $this->whenLoaded('manager'),
            'working_times' => WorkingTimeResource::collection($this->whenLoaded('workingTimes')),
        ];
    }
}
