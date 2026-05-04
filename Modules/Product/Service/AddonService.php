<?php

namespace Modules\Product\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
use Modules\Product\App\Models\Addon;

class AddonService
{
    use UploadHelper;

    public function findAll($data = [], $relations = [])
    {
        $query = Addon::query()
            ->with($relations)
            ->available()
            ->orderByDesc('created_at');

        return getCaseCollection($query, $data);
    }

    public function create(array $data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'addon');
        }

        return Addon::create($data);
    }

    public function update(Addon $addon, array $data)
    {
        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/addon/'.$this->getImageName('addon', $addon->image)));
            $data['image'] = $this->upload(request()->file('image'), 'addon');
        }
        $addon->update($data);

        return $addon;
    }

    public function findById($id)
    {
        return Addon::findOrFail($id);
    }

    public function delete(Addon $addon)
    {
        return $addon->delete();
    }

    public function activate($addon)
    {
        $addon->is_active = ! $addon->is_active;
        $addon->save();

        return $addon->fresh();
    }
}
