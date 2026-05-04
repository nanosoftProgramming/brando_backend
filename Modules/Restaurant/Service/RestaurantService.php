<?php

namespace Modules\Restaurant\Service;

use Illuminate\Support\Facades\File;
use Modules\Admin\App\Models\Admin;
use Modules\Common\Helpers\UploadHelper;
use Modules\Restaurant\App\Models\Restaurant;

class RestaurantService
{
    use UploadHelper;

    public function findAll($data = [], $relations = [])
    {
        $restaurants = Restaurant::withCount('branches')->filter($data)->with($relations)->latest();

        return getCaseCollection($restaurants, $data);
    }

    public function findById($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        return $restaurant;
    }

    public function findBy($key, $value)
    {
        $restaurant = Restaurant::where($key, $value)->get();

        return $restaurant;
    }

    public function active($data = [], $relations = [])
    {
        $restaurants = Restaurant::active()->withCount('branches')->with($relations)->latest();

        return getCaseCollection($restaurants, $data);
    }

    public function create($data, $managerData, $workingTimesData = [])
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'restaurant');
        }
        if (request()->hasFile('manager_image')) {
            $managerData['image'] = $this->upload(request()->file('manager_image'), 'admin');
        }
        $restaurant = Restaurant::create($data);
        $managerData['restaurant_id'] = $restaurant->id;
        $restaurantManager = Admin::create($managerData);
        $restaurantManager->assignRole('Restaurant Manager');
        if (! empty($workingTimesData)) {
            $this->updateWorkingTimes($restaurant, $workingTimesData);
        }

        return $restaurant->fresh()->load(['manager', 'workingTimes']);
    }

    public function update($restaurant, $restaurantData, $managerData, $workingTimesData = [])
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/restaurant/'.$this->getImageName('restaurant', $restaurant->image)));
            $restaurantData['image'] = $this->upload(request()->file('image'), 'restaurant');
        }
        if (request()->hasFile('manager_image')) {
            File::delete(public_path('uploads/admin/'.$this->getImageName('admin', $restaurant->manager->image)));
            $managerData['image'] = $this->upload(request()->file('manager_image'), 'admin');
        }
        if ($restaurantData) {
            $restaurant->update($restaurantData);
        }
        if ($managerData) {
            $restaurant->manager()->update($managerData);
        }
        if (! empty($workingTimesData)) {
            $this->updateWorkingTimes($restaurant, $workingTimesData);
        }

        return $restaurant->fresh()->load(['manager', 'workingTimes']);
    }

    private function updateWorkingTimes(Restaurant $restaurant, array $workingTimesData)
    {
        $restaurant->workingTimes()->delete();
        foreach ($workingTimesData as $workingTime) {
            if (! isset($workingTime['day'])) {
                continue;
            }
            $newWorkingTime = [
                'day' => $workingTime['day'],
                'is_closed' => $workingTime['is_closed'] ?? 0,
            ];
            if (! $newWorkingTime['is_closed']) {
                if (! isset($workingTime['opening_time']) || ! isset($workingTime['closing_time'])) {
                    continue;
                }
                $newWorkingTime['opening_time'] = $workingTime['opening_time'];
                $newWorkingTime['closing_time'] = $workingTime['closing_time'];
            } else {
                $newWorkingTime['opening_time'] = null;
                $newWorkingTime['closing_time'] = null;
            }

            $restaurant->workingTimes()->create($newWorkingTime);
        }
    }

    public function delete($restaurant)
    {
        File::delete(public_path('uploads/restaurant/'.$this->getImageName('restaurant', $restaurant->image)));
        File::delete(public_path('uploads/admin/'.$this->getImageName('admin', $restaurant->manager->image)));
        $restaurant->delete();
    }

    public function toggleActivate($restaurant)
    {
        $restaurant->update(['is_active' => ! $restaurant->is_active]);

        return $restaurant->fresh();
    }

    public function getByCategory($categoryId, $data = [], $relations = [])
    {
        $restaurants = Restaurant::whereHas('products', function ($query) use ($categoryId) {
            $query->active()
                ->whereHas('category', function ($subQuery) use ($categoryId) {
                    $subQuery->where('id', $categoryId);
                    $subQuery->active();
                });
        })
            ->with($relations)
            ->active()
            ->latest();

        return getCaseCollection($restaurants, $data);
    }
}
