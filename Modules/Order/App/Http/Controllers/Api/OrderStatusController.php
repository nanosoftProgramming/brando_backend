<?php

namespace Modules\Order\App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Modules\Order\App\Resources\OrderStatusResource;
use Modules\Order\Service\OrderStatusService;

class OrderStatusController extends Controller
{
    protected $orderStatusService;

    public function __construct(OrderStatusService $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    public function getStatuses()
    {
        $statuses = $this->orderStatusService->getAll();

        return returnMessage(true, 'Status Fetched Successfully', OrderStatusResource::collection($statuses));

    }
}
