<?php

namespace Modules\Driver\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Driver\App\Http\Requests\DriverChangePasswordRequest;
use Modules\Driver\App\Http\Requests\DriverUpdateProfileRequest;
use Modules\Driver\App\resources\DriverResource;
use Modules\Driver\Service\DriverService;

class DriverProfileController extends Controller
{
    protected $driverService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(DriverService $driverService)
    {
        $this->middleware('auth:driver');
        $this->driverService = $driverService;
    }

    public function changePassword(DriverChangePasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->driverService->changePassword($request->validated());
            DB::commit();

            return returnMessage(true, 'Password Changed Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function updateProfile(DriverUpdateProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->driverService->updateProfile($request->validated());
            DB::commit();

            return returnMessage(true, 'Profile Updated Successfully',
                new DriverResource(auth('driver')->user()));
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }
}
