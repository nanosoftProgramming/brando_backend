<?php

namespace Modules\Product\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Product\Service\ProductService;
use Modules\Product\App\Resources\ProductResource;

class ProductController extends Controller
{
    private $relations = ['restaurant', 'category', 'addons', 'images', 'sizeImages', 'attributeValues.attribute', 'attributeValues.value', 'addons', 'rate.rate.client'];
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $products = $this->productService->active($data, $this->relations);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }

    public function getByRestaurant(Request $request, $restaurant)
    {
        $data = $request->all();
        $products = $this->productService->getByRestaurant($restaurant, $data, $this->relations);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }

    public function saleProducts(Request $request)
    {
        $data = $request->all();
        $products = $this->productService->saleProducts($data, $this->relations);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }

    public function recentlyProducts(Request $request)
    {
        $data = $request->all();
        $products = $this->productService->recentlyProducts($data, $this->relations);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }

    public function bestSellingProducts(Request $request)
    {
        $data = $request->all();
        $products = $this->productService->bestSellingProducts($data, $this->relations);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }

    public function highestRatedProducts(Request $request)
    {
        $data = $request->all();
        $products = $this->productService->highestRatedProducts($data, $this->relations);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }

    public function relatedProducts(Request $request, $productId)
    {
        $data = $request->all();
        $products = $this->productService->relatedProducts($data, $this->relations, $productId);

        return returnMessage(true, 'Products fetched successfully', ProductResource::collection($products)->response()->getData(true));
    }
}
