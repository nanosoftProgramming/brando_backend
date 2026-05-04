<?php

namespace Modules\Product\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Product\App\Resources\BranchProductResource;
use Modules\Product\Service\BranchProductService;

class BranchProductController extends Controller
{
    protected $branchProductService;

    public function __construct(BranchProductService $branchProductService)
    {

        $this->middleware(middleware: 'auth:client');
        $this->branchProductService = $branchProductService;
    }

    public function index(Request $request, $branchId)
    {
        $filters = $request->all();
        $products = $this->branchProductService->getByBranch($branchId, $filters);

        return returnMessage(true, 'Branch Products fetched successfully', BranchProductResource::collection($products)->response()->getData(true));
    }
}
