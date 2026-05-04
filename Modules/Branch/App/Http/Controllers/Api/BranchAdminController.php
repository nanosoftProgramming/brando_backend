<?php

namespace Modules\Branch\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Branch\App\Http\Requests\BranchRequest;
use Modules\Branch\App\Http\Requests\BranchUpdateRequest;
use Modules\Branch\App\Models\Branch;
use Modules\Branch\App\resources\BranchResource;
use Modules\Branch\DTO\BranchDto;
use Modules\Branch\DTO\BranchManagerDto;
use Modules\Branch\Service\BranchService;

class BranchAdminController extends Controller
{
    private $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin|Restaurant Manager');
        $this->branchService = $branchService;
    }

    public function index(Request $request)
    {
        $data = $request->all();
        $branches = $this->branchService->findAll($data);

        return returnMessage(true, 'Branches Fetched Successfully', BranchResource::collection($branches)->response()->getData(true));
    }

    public function store(BranchRequest $request)
    {
        try {
            DB::beginTransaction();
            $branchData = (new BranchDto($request))->dataFromRequest();
            $managerData = (new BranchManagerDto($request))->dataFromRequest();
            $branch = $this->branchService->create($branchData, $managerData);
            DB::commit();

            return returnMessage(true, 'Branch Created Successfully', $branch);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function update(BranchUpdateRequest $request, Branch $branch)
    {
        try {
            DB::beginTransaction();
            $branchData = (new BranchDto($request))->dataFromRequest();
            $managerData = (new BranchManagerDto($request))->dataFromRequest();
            $branch = $this->branchService->update($branch, $branchData, $managerData);
            DB::commit();

            return returnMessage(true, 'Branch Updated Successfully', $branch);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function destroy(BranchRequest $request, Branch $branch)
    {
        try {
            DB::beginTransaction();
            $this->branchService->delete($branch);
            DB::commit();

            return returnMessage(true, 'Branch Deleted Successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function toggleActivate(BranchRequest $request, Branch $branch)
    {
        try {
            DB::beginTransaction();
            $this->branchService->toggleActivate($branch);
            DB::commit();

            return returnMessage(true, 'Branch Updated Successfully');
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
