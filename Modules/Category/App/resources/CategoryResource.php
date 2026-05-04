<?php

namespace Modules\Category\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? null,
            'description' => $this->description ?? null,
            'image' => $this->image ?? null,
            'category_id' => $this->category_id ?? null,
            'is_active' => $this->is_active ?? null,
            'created_at' => $this->created_at->format('Y-m-d') ?? null,
            'updated_at' => $this->updated_at->format('Y-m-d') ?? null,
            'sub_categories' => CategoryResource::collection($this->whenLoaded('childrenRecursive')) ?? null,
        ];
    }
}
