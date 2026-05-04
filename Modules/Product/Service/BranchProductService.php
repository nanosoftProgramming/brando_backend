<?php

namespace Modules\Product\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\BranchProduct;

class BranchProductService
{
    public function createMany($data)
    {
        $branchId = $data['branch_id'];

        foreach ($data['products'] as $product) {
            DB::table('branch_product')->updateOrInsert(
                ['branch_id' => $branchId, 'product_id' => $product['product_id']],
                [
                    'custom_price_egp' => $product['custom_price_egp'] ?? null,
                    'custom_price_sar' => $product['custom_price_sar'] ?? null,
                    'updated_at' => Carbon::now(),
                    'created_at' => Carbon::now(),
                ]
            );
        }
    }

    public function getByBranch($branchId, $filters = [])
    {
        $query = BranchProduct::where('branch_id', $branchId)
            ->with(['product.category'])
            ->filter($filters);

        return getCaseCollection($query, $filters);
    }

    public function markBestSeller($products)
    {
        $bestSeller = DB::table('order_items')
            ->select('branch_product_id', DB::raw('SUM(quantity) as total_sales'))
            ->groupBy('branch_product_id')
            ->orderByDesc('total_sales')
            ->first();

        return $products->map(function ($product) use ($bestSeller) {
            if ($bestSeller) {
                $product->is_best_seller = ($product->id == $bestSeller->branch_product_id);
            } else {
                $product->is_best_seller = false;
            }

            return $product;
        });
    }

    public function delete($branchProduct): bool
    {
        return $branchProduct->delete();
    }

    public function update(BranchProduct $branchProduct, array $data): BranchProduct
    {
        $exists = BranchProduct::where('branch_id', $data['branch_id'] ?? $branchProduct->branch_id)
            ->where('product_id', $data['product_id'] ?? $branchProduct->product_id)
            ->where('id', '!=', $branchProduct->id)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'product_id' => ['This product is already assigned to the given branch.'],
            ]);
        }

        $branchProduct->update($data);

        return $branchProduct;
    }
}
