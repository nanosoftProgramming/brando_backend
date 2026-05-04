<?php

namespace Modules\Product\App\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = parent::toArray($request);

        $data['attributes'] = $this->attributeValues
            ->groupBy('attribute_id')
            ->map(fn($rows) => [
                'id' => $rows->first()->attribute->id,
                'name' => $rows->first()->attribute->name,
                'required' => $rows->first()->attribute->required,
                'multi_select' => $rows->first()->attribute->multi_select,
                'values' => $rows->map(fn($row) => [
                    'id' => $row->value->id,
                    'name' => $row->value->attribute_value,
                    'price_egp' => $row->price_egp,
                    'price_sar' => $row->price_sar,
                ])->values(),
            ])->values();

        unset($data['attribute_values']);

        return $data;
    }
}
