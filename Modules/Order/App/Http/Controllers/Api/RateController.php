<?php

namespace Modules\Order\App\Http\Controllers\Api;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Order\App\Http\Requests\RateRequest;
use Modules\Order\App\Models\Order;
use Modules\Order\DTO\RateDto;
use Modules\Order\Service\RateService;

class RateController extends Controller
{
    protected $rateService;

    public function __construct(RateService $rateService)
    {
        $this->middleware('auth:client');
        $this->rateService = $rateService;
    }

    public function store(RateRequest $request, Order $order)
    {
        try {
            $data = (new RateDto($request, $order))->dataFromRequest();
            DB::beginTransaction();
            $rate = $this->rateService->save($data);
            DB::commit();

            return returnMessage(true, 'Rate created successfully', $rate);
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
