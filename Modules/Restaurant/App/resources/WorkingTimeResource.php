<?php

namespace Modules\Restaurant\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkingTimeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'day' => $this->day,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
