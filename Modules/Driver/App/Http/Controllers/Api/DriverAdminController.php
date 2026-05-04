<?php

namespace Modules\Driver\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Driver\App\Http\Requests\DriverStoreRequest;
use Modules\Driver\App\Http\Requests\DriverUpdateRequest;
use Modules\Driver\App\Models\Driver;
use Modules\Driver\App\resources\DriverResource;
use Modules\Driver\DTO\DriverDto;
use Modules\Driver\Service\DriverService;

class DriverAdminController extends Controller
{
    protected $driverService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(DriverService $driverService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin');
        $this->driverService = $driverService;
    }

    public function index(Request $request)
    {
        try {
            $data = $request->all();
            $drivers = $this->driverService->findAll($data);

            return returnMessage(true, 'Drivers retrieved successfully', DriverResource::collection($drivers)->response()->getData(true));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function store(DriverStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new DriverDto($request))->dataFromRequest();
            $driver = $this->driverService->create($data);
            DB::commit();

            return returnMessage(true, 'Driver Created Successfully', $driver);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(DriverUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = (new DriverDto($request))->dataFromRequest();

            $driver = $this->driverService->update($id, $data);
            DB::commit();

            return returnMessage(true, 'Driver updated successfully', $driver);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $this->driverService->delete($id);
            DB::commit();

            return returnMessage(true, 'Driver deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(Driver $driver)
    {
        try {
            DB::beginTransaction();
            $driver = $this->driverService->toggleActivate($driver);
            DB::commit();

            return returnMessage(true, 'Driver updated successfully', null);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
