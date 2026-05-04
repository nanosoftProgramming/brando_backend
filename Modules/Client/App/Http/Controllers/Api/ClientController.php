<?php

namespace Modules\Client\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Client\App\Http\Requests\ClientChangePasswordRequest;
use Modules\Client\App\Http\Requests\ClientUpdateProfileRequest;
use Modules\Client\App\resources\ClientResource;
use Modules\Client\Service\ClientService;

class ClientController extends Controller
{
    protected $clientService;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ClientService $clientService)
    {
        $this->middleware('auth:client');
        $this->clientService = $clientService;
    }

    public function changePassword(ClientChangePasswordRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->clientService->changePassword($request->validated());
            DB::commit();

            return returnMessage(true, 'Password Changed Successfully');
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function updateProfile(ClientUpdateProfileRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->clientService->updateProfile($request->validated());
            DB::commit();

            return returnMessage(true, 'Profile Updated Successfully', new ClientResource(auth('client')->user()));
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }
}
