<?php

namespace Modules\Restaurant\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Restaurant\App\resources\RestaurantResource;
use Modules\Restaurant\Service\RestaurantService;

class RestaurantController extends Controller
{
    private $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = [];
        $restaurants = $this->restaurantService->active($data, $relations);

        return returnMessage(true, 'Restaurants Fetched Successfully', RestaurantResource::collection($restaurants)->response()->getData(true));
    }

    public function getByCategory(Request $request, $category)
    {
        $data = $request->all();
        $relations = [];
        $restaurants = $this->restaurantService->getByCategory($category, $data, $relations);

        return returnMessage(true, 'Restaurants Fetched Successfully', RestaurantResource::collection($restaurants)->response()->getData(true));
    }
}
