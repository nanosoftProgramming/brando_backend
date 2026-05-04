<?php

namespace Modules\Common\Service;

use Modules\Branch\App\Models\Branch;
use Modules\Category\App\Models\Category;
use Modules\Product\App\Models\Addon;
use Modules\Product\App\Models\Attribute;
use Modules\Product\App\Models\Product;
use Modules\Restaurant\App\Models\Restaurant;

class SearchService
{
    public function searchEverything(string $query)
    {
        $results = collect();

        $products = $this->searchProducts($query);
        $results = $results->merge($products);

        $categories = $this->searchCategories($query);
        $results = $results->merge($categories);

        $branches = $this->searchBranches($query);
        $results = $results->merge($branches);

        $restaurants = $this->searchRestaurants($query);
        $results = $results->merge($restaurants);

        $addons = $this->searchAddons($query);
        $results = $results->merge($addons);

        $attributes = $this->searchAttributes($query);
        $results = $results->merge($attributes);

        return $results;
    }

    private function searchProducts(string $query)
    {
        return Product::query()
            ->with(['category', 'restaurant', 'images'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->active()
            ->get()
            ->map(function ($item) {
                $item->type = 'product';

                return $item;
            });
    }

    private function searchCategories(string $query)
    {
        return Category::query()
            ->with(['parent', 'products'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->active()
            ->get()
            ->map(function ($item) {
                $item->type = 'category';

                return $item;
            });
    }

    private function searchBranches(string $query)
    {
        return Branch::query()
            ->with(['restaurant', 'city'])
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->active()
            ->get()
            ->map(function ($item) {
                $item->type = 'branch';

                return $item;
            });
    }

    private function searchRestaurants(string $query)
    {
        return Restaurant::query()
            ->where('name', 'like', "%{$query}%")
            ->active()
            ->get()
            ->map(function ($item) {
                $item->type = 'restaurant';

                return $item;
            });
    }

    private function searchAddons(string $query)
    {
        return Addon::query()
            ->with(['restaurant'])
            ->where('name', 'like', "%{$query}%")
            ->active()
            ->get()
            ->map(function ($item) {
                $item->type = 'addon';

                return $item;
            });
    }

    private function searchAttributes(string $query)
    {
        return Attribute::query()
            ->with(['product', 'values'])
            ->where('name', 'like', "%{$query}%")
            ->get()
            ->map(function ($item) {
                $item->type = 'attribute';

                return $item;
            });
    }
}
