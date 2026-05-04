<?php

namespace Modules\Common\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\App\Models\Slider;
use Modules\Common\Helpers\UploadHelper;

class SliderService
{
    use UploadHelper;

    public function findAll($data = [], $relations = [])
    {
        $sliders = Slider::query()->with($relations)->latest();

        return getCaseCollection($sliders, $data);
    }

    public function findById($id)
    {
        $slider = Slider::findOrFail($id);

        return $slider;
    }

    public function active($data = [], $relations = [])
    {
        $sliders = Slider::query()->active()->with($relations)->latest();

        return getCaseCollection($sliders, $data);
    }

    public function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'slider');
        }
        if (auth('admin')->user()->hasRole('Super Admin')) {
            $data['is_active'] = 1;
        }
        $slider = Slider::create($data);

        return $slider;
    }

    public function update($slider, $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/slider/'.$this->getImageName('slider', $slider->image)));
            $data['image'] = $this->upload(request()->file('image'), 'slider');
        }
        $slider->update($data);

        return $slider->fresh();
    }

    public function delete($slider)
    {
        if ($slider->image) {
            File::delete(public_path('uploads/slider/'.$this->getImageName('slider', $slider->image)));
        }
        $slider->delete();
    }

    public function toggleActivate($slider)
    {
        $slider->update(['is_active' => ! $slider->is_active]);

        return $slider->fresh();
    }

    public function getByRestaurant($restaurant, $data = [])
    {
        $sliders = Slider::query()
            ->whereHas('products', function ($query) use ($restaurant) {
                $query->where('restaurant_id', $restaurant->id)
                    ->active();
            })
            ->active()
            ->latest();

        return getCaseCollection($sliders, $data);
    }
}
