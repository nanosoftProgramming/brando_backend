<?php

namespace Modules\Product\Service;

use Modules\Common\Helpers\UploadHelper;
use Modules\Product\App\Models\Attribute;
use Modules\Product\App\Models\Product;

class AttributeService
{
    use UploadHelper;

    public function findAll($data = [], $relations = [])
    {
        $query = Attribute::query()
            ->available()
            ->with($relations)
            ->orderByDesc('created_at');

        return getCaseCollection($query, $data);
    }

    public function create(array $data)
    {
        $attribute = Attribute::create($data);
        $attribute->values()->createMany($data['values']);

        return $attribute->fresh()->load('values');
    }

    public function update($attribute, array $data)
    {
        $attribute = Attribute::findOrFail($attribute->id);
        $attribute->update($data);
        $attribute->values()->delete();
        $attribute->values()->createMany($data['values']);

        return $attribute->fresh()->load('values');
    }

    public function findById($id)
    {
        return Product::with(['restaurant', 'category'])->findOrFail($id);
    }

    public function delete($attribute)
    {
        return $attribute->delete();
    }

    public function activate($id)
    {
        $product = $this->findById($id);
        $product->is_active = ! $product->is_active;
        $product->save();
    }

    public function getByRestaurant($restaurantId, $data = [], $relations = [])
    {
        $query = Product::query()
            ->where('restaurant_id', $restaurantId)
            ->active()
            ->with($relations)
            ->filter($data)
            ->orderByDesc('created_at');

        return getCaseCollection($query, $data);
    }
}
