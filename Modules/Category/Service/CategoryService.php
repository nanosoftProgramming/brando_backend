<?php

namespace Modules\Category\Service;

use Illuminate\Support\Facades\File;
use Modules\Category\App\Models\Category;
use Modules\Common\Helpers\UploadHelper;

class CategoryService
{
    use UploadHelper;

    public function findAll($data = [], $relations = [])
    {
        $categories = Category::query()->parent()->with($relations)->latest();

        return getCaseCollection($categories, $data);
    }

    public function findSubCategories($category, $data = [], $relations = [])
    {
        $subCategories = Category::query()->where('category_id', $category->id)->with($relations)->latest();

        return getCaseCollection($subCategories, $data);
    }

    public function findById($id)
    {
        $category = Category::findOrFail($id);

        return $category;
    }

    public function active($data = [], $relations = [])
    {
        $categories = Category::query()->parent()->active()->with($relations)->latest();

        return getCaseCollection($categories, $data);
    }

    public function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'category');
        }
        $category = Category::create($data);

        return $category;
    }

    public function update($category, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/category/'.$this->getImageName('category', $category->image)));
            $data['image'] = $this->upload(request()->file('image'), 'category');
        }
        $category->update($data);

        return $category->fresh();
    }

    public function delete($category)
    {
        if ($category->image) {
            File::delete(public_path('uploads/category/'.$this->getImageName('category', $category->image)));
        }
        $category->delete();
    }

    public function toggleActivate($category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return $category->fresh();
    }

    public function getByRestaurant($restaurant, $data = [])
    {
        $categories = Category::query()
            ->whereHas('products', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id)
                    ->active();
            })
            ->active()
            ->latest();

        return getCaseCollection($categories, $data);
    }
}
