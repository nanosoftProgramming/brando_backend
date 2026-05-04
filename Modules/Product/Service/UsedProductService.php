<?php

namespace Modules\Product\Service;

use Illuminate\Support\Facades\File;
use Modules\Common\Helpers\UploadHelper;
use Modules\Product\App\Models\UsedProduct;

class UsedProductService
{
    use UploadHelper;

    public function findAll($data = [])
    {
        $usedProducts = UsedProduct::with(['category', 'client'])
            ->where('is_sold', false)
            ->active()
            ->latest();

        return getCaseCollection($usedProducts, $data);
    }

    public function findById($id)
    {
        return UsedProduct::with(['category', 'client'])->findOrFail($id);
    }

    public function create($data)
    {
        if (request()->hasFile('image')) {
            $data['image'] = $this->upload(request()->file('image'), 'used_product');
        }

        return UsedProduct::create($data);
    }

    public function update($id, $data)
    {
        $usedProduct = $this->findById($id);

        if ($usedProduct->is_sold) {
            throw new \Exception('Cannot update a sold product');
        }

        if (request()->hasFile('image')) {
            File::delete(public_path('uploads/used_product/' . $this->getImageName('used_product', $usedProduct->image)));
            $data['image'] = $this->upload(request()->file('image'), 'used_product');
        }

        $usedProduct->update($data);

        return $usedProduct;
    }

    public function delete($id)
    {
        $usedProduct = $this->findById($id);

        if ($usedProduct->is_sold) {
            throw new \Exception('Cannot delete a sold product');
        }

        if ($usedProduct->image) {
            File::delete(public_path('uploads/used_product/' . $this->getImageName('used_product', $usedProduct->image)));
        }

        return $usedProduct->delete();
    }
}
