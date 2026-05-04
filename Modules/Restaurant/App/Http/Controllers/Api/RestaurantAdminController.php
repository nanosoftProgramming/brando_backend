<?php

namespace Modules\Restaurant\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Restaurant\App\Http\Requests\RestaurantStoreRequest;
use Modules\Restaurant\App\Http\Requests\RestaurantUpdateRequest;
use Modules\Restaurant\App\Models\Restaurant;
use Modules\Restaurant\App\resources\RestaurantResource;
use Modules\Restaurant\DTO\RestaurantDto;
use Modules\Restaurant\DTO\RestaurantManagerDto;
use Modules\Restaurant\DTO\WorkingTimeDto;
use Modules\Restaurant\Service\RestaurantService;

class RestaurantAdminController extends Controller
{
    private $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin');
        $this->restaurantService = $restaurantService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['manager', 'workingTimes'];
        $restaurants = $this->restaurantService->findAll($data, $relations);

        return returnMessage(true, 'Restaurants Fetched Successfully', RestaurantResource::collection($restaurants)->response()->getData(true));
    }

    public function store(RestaurantStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new RestaurantDto($request))->dataFromRequest();
            $managerData = (new RestaurantManagerDto($request))->dataFromRequest();
            $workingTimesData = (new WorkingTimeDto($request))->dataFromRequest();
            $restaurant = $this->restaurantService->create($data, $managerData, $workingTimesData);
            DB::commit();

            return returnMessage(true, 'Restaurant Created Successfully', $restaurant);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(RestaurantUpdateRequest $request, Restaurant $restaurant)
    {
        try {
            DB::beginTransaction();
            $restaurantData = (new RestaurantDto($request))->dataFromRequest();
            $managerData = (new RestaurantManagerDto($request))->dataFromRequest();
            $workingTimesData = (new WorkingTimeDto($request))->dataFromRequest();
            $restaurant = $this->restaurantService->update($restaurant, $restaurantData, $managerData, $workingTimesData);
            DB::commit();

            return returnMessage(true, 'Restaurant Updated Successfully', $restaurant);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(Restaurant $restaurant)
    {
        try {
            DB::beginTransaction();
            $this->restaurantService->delete($restaurant);
            DB::commit();

            return returnMessage(true, 'Restaurant Deleted Successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(Restaurant $restaurant)
    {
        try {
            DB::beginTransaction();
            $this->restaurantService->toggleActivate($restaurant);
            DB::commit();

            return returnMessage(true, 'Restaurant Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
