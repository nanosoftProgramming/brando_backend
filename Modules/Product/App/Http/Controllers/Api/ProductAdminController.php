<?php

namespace Modules\Product\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Http\Requests\ProductRequest;
use Modules\Product\App\Models\Product;
use Modules\Product\DTO\ProductDto;
use Modules\Product\Service\ProductService;

class ProductAdminController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin')->only(['activate']);
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['restaurant', 'category', 'addons', 'images', 'sizeImages', 'attributeValues.attribute', 'attributeValues.value', 'addons'];
        $products = $this->productService->findAll($data, $relations);

        return returnMessage(true, 'Products fetched successfully', $products);
    }

    public function store(ProductRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new ProductDto($request))->dataFromRequest();
            $product = $this->productService->create($data);
            DB::commit();

            return returnMessage(true, 'Product created successfully', $product);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(ProductRequest $request, Product $product)
    {
        try {
            DB::beginTransaction();
            $data = (new ProductDto($request))->dataFromRequest();
            $product = $this->productService->update($product, $data);
            DB::commit();

            return returnMessage(true, 'Product updated successfully', $product);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function show(Product $product)
    {
        $product = $this->productService->findById($product->id);

        return returnMessage(true, 'Product retrieved successfully', $product);
    }

    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();
            $this->productService->delete($product);
            DB::commit();

            return returnMessage(true, 'Product deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function activate($id)
    {
        try {
            DB::beginTransaction();
            $this->productService->activate($id);
            DB::commit();

            return returnMessage(true, 'Product activated successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
