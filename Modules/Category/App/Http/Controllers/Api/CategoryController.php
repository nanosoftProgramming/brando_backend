<?php

namespace Modules\Category\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Category\App\resources\CategoryResource;
use Modules\Category\Service\CategoryService;
use Modules\Restaurant\App\Models\Restaurant;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['childrenRecursive'];
        $categories = $this->categoryService->active($data, $relations);

        return returnMessage(true, 'Categories Fetched Successfully', CategoryResource::collection($categories)->response()->getData(true));
    }

    public function getByRestaurant(Request $request, Restaurant $restaurant)
    {
        $data = $request->all();
        $categories = $this->categoryService->getByRestaurant($restaurant, $data);

        return returnMessage(true, 'Categories Fetched Successfully', CategoryResource::collection($categories)->response()->getData(true));
    }
}
