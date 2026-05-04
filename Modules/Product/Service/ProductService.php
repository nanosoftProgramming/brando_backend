<?php

namespace Modules\Product\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
use Modules\Order\App\Models\OrderDetailAttribute;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductAttributeValue;
use Modules\Product\App\Models\ProductImage;
use Modules\Product\App\Models\ProductSizeImage;

class ProductService
{
    use UploadHelper;

    public function findAll($data = [], $relations = [])
    {
        $query = Product::query()
            ->available()
            ->with($relations)
            ->filter($data)
            ->orderByDesc('created_at');

        return getCaseCollection($query, $data);
    }

    public function active($data = [], $relations = [])
    {
        $query = Product::query()
            ->active()
            ->available()
            ->with($relations)
            ->filter($data)
            ->orderByDesc('created_at');

        return getCaseCollection($query, $data);
    }

    public function create(array $data)
    {

        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'products');
        }

        $product = Product::create($data);

        if (isset($data['addons']) && !empty($data['addons'])) {
            $addonIds = array_column($data['addons'], 'addon_id');
            $product->addons()->sync($addonIds);
        }
        if (isset($data['attributes']) && !empty($data['attributes'])) {
            foreach ($data['attributes'] as $attribute) {
                $product->attributeValues()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'attribute_value_id' => $attribute['attribute_value_id'],
                    'price_egp' => $attribute['price_egp'],
                    'price_sar' => $attribute['price_sar'],
                ]);
            }
        }
        // if (isset($data['attributes']) && !empty($data['attributes'])) {
        //     foreach ($data['attributes'] as $attribute) {
        //         $productAttribute = ProductAttribute::create([
        //             'name' => $attribute['name'],
        //             'required' => $attribute['required'] ?? false,
        //             'multi_select' => $attribute['multi_select'] ?? false,
        //             'override_price' => $attribute['override_price'] ?? false,
        //             'product_id' => $product->id,
        //         ]);

        //         if (isset($attribute['values']) && !empty($attribute['values'])) {
        //             foreach ($attribute['values'] as $value) {
        //                 ProductAttributeValue::create([
        //                     'attribute_value' => $value['attribute_value'],
        //                     'price_egp' => $value['price_egp'] ?? 0,
        //                     'price_sar' => $value['price_sar'] ?? 0,
        //                     'product_attribute_id' => $productAttribute->id,
        //                 ]);
        //             }
        //         }
        //     }
        // }

        if (request()->hasFile('images')) {
            foreach (request()->file('images') as $image) {
                $imagePath = $this->upload($image, 'products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);
            }
        }

        // Size Images (same idea as product images)
        if (request()->hasFile('size_images')) {
            foreach (request()->file('size_images') as $image) {
                $imagePath = $this->upload($image, 'product_size_images');
                ProductSizeImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);
            }
        }

        return $product->fresh()->load(['addons', 'images', 'sizeImages', 'restaurant', 'category', 'attributeValues.attribute', 'attributeValues.value']);
    }

    public function update(Product $product, array $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/products/' . $this->getImageName('products', $product->image)));
            $data['image'] = $this->upload(request()->file('image'), 'products');
        }
        $product->update($data);

        if (isset($data['addons']) && !empty($data['addons'])) {
            $addonIds = array_column($data['addons'], 'addon_id');
            $product->addons()->sync($addonIds);
        }

        if (isset($data['attributes']) && !empty($data['attributes'])) {
            $this->updateProductAttributes($product, $data['attributes']);
        }

        if (request()->hasFile('images')) {
            foreach (request()->file('images') as $image) {
                $imagePath = $this->upload($image, 'products');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);
            }
        }

        // Size Images (same idea as product images)
        if (request()->hasFile('size_images')) {
            foreach (request()->file('size_images') as $image) {
                $imagePath = $this->upload($image, 'product_size_images');
                ProductSizeImage::create([
                    'product_id' => $product->id,
                    'image' => $imagePath,
                ]);
            }
        }

        return $product->fresh()->load(['addons', 'images', 'sizeImages', 'restaurant', 'category', 'attributeValues.attribute', 'attributeValues.value']);
    }

    private function updateProductAttributes(Product $product, array $attributes)
    {
        $existing = $product->attributeValues;
        $incomingKeys = [];

        foreach ($attributes as $attribute) {
            $key = $attribute['attribute_id'] . '_' . $attribute['attribute_value_id'];
            $incomingKeys[] = $key;

            $existingAttribute = $existing->first(function ($item) use ($attribute) {
                return $item->attribute_id == $attribute['attribute_id']
                    && $item->attribute_value_id == $attribute['attribute_value_id'];
            });

            if ($existingAttribute) {
                $existingAttribute->update([
                    'price_egp' => $attribute['price_egp'],
                    'price_sar' => $attribute['price_sar'],
                ]);
            } else {
                $product->attributeValues()->create($attribute);
            }
        }

        foreach ($existing as $attributeValue) {
            $key = $attributeValue->attribute_id . '_' . $attributeValue->attribute_value_id;
            if (!in_array($key, $incomingKeys)) {
                $canDelete = !OrderDetailAttribute::where('product_attribute_value_id', $attributeValue->id)->exists();
                if ($canDelete) {
                    $attributeValue->delete();
                }
            }
        }
    }

    public function findById($id)
    {
        return Product::with(['restaurant', 'category'])->findOrFail($id);
    }

    public function delete(Product $product)
    {
        $product->loadMissing(['sizeImages', 'images']);

        // Clean up size image files before deleting the product (DB has cascade, files do not)
        foreach ($product->sizeImages as $sizeImage) {
            $raw = $sizeImage->getRawOriginal('image');
            if ($raw) {
                File::delete(public_path('uploads/product_size_images/' . $this->getImageName('product_size_images', $raw)));
            }
        }

        // Clean up extra product images files before deleting the product
        foreach ($product->images as $img) {
            $raw = $img->getRawOriginal('image');
            if ($raw) {
                File::delete(public_path('uploads/products/' . $this->getImageName('products', $raw)));
            }
        }

        return $product->delete();
    }

    public function activate($id)
    {
        $product = $this->findById($id);
        $product->is_active = !$product->is_active;
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

    public function saleProducts($data = [], $relations = [])
    {
        $query = Product::query()
            ->with($relations)
            ->active()
            ->where('discounted_price_egp', '>', 0)
            ->latest();

        return getCaseCollection($query, $data);
    }

    public function recentlyProducts($data = [], $relations = [])
    {
        $query = Product::query()
            ->with($relations)
            ->active()
            ->latest();

        return getCaseCollection($query, $data);
    }

    public function bestSellingProducts($data = [], $relations = [])
    {
        $query = Product::query()
            ->with($relations)
            ->active()
            ->withCount('orderDetails')
            ->having('order_details_count', '>', 0)
            ->orderByDesc('order_details_count');

        return getCaseCollection($query, $data);
    }

    public function highestRatedProducts($data = [], $relations = [])
    {
        $query = Product::query()
            ->with($relations)
            ->active()
            ->withCount('rate')
            ->having('rate_count', '>', 0)
            ->orderByDesc('rate_count');

        return getCaseCollection($query, $data);
    }

    public function relatedProducts($data = [], $relations = [], $productId)
    {
        $query = Product::query()
            ->with($relations)
            ->active()
            ->where('category_id', $data['category_id'])
            ->where('id', '!=', $productId)
            ->latest()
            ->take(10);

        return getCaseCollection($query, $data);
    }
}
