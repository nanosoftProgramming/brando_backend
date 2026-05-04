<?php

namespace Modules\Product\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Http\Requests\AddonRequest;
use Modules\Product\App\Models\Addon;
use Modules\Product\App\Resources\AddonResource;
use Modules\Product\DTO\AddonDto;
use Modules\Product\Service\AddonService;

class AddonAdminController extends Controller
{
    protected $addonService;

    public function __construct(AddonService $addonService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin|Restaurant Manager');

        $this->addonService = $addonService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $relations = ['restaurant'];
        $addons = $this->addonService->findAll($data, $relations);

        return returnMessage(true, 'Addons fetched successfully', AddonResource::collection($addons)->response()->getData(true));
    }

    public function store(AddonRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = (new AddonDto($request))->dataFromRequest();
            $addon = $this->addonService->create($data);
            DB::commit();

            return returnMessage(true, 'Addon created successfully', new AddonResource($addon));
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(AddonRequest $request, Addon $addon)
    {
        try {
            DB::beginTransaction();
            $data = (new AddonDto($request))->dataFromRequest();
            $addon = $this->addonService->update($addon, $data);
            DB::commit();

            return returnMessage(true, 'Addon updated successfully', new AddonResource($addon));
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(AddonRequest $request, Addon $addon)
    {
        try {
            DB::beginTransaction();
            $this->addonService->delete($addon);
            DB::commit();

            return returnMessage(true, 'Addon deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function activate(AddonRequest $request, Addon $addon)
    {
        try {
            DB::beginTransaction();
            $this->addonService->activate($addon);
            DB::commit();

            return returnMessage(true, 'Addon activated successfully', new AddonResource($addon));
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
