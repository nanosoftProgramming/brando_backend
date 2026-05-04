<?php

namespace Modules\Order\App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Order\App\Http\Requests\OrderRequest;
use Modules\Order\App\Resources\OrderResource;
use Modules\Order\DTO\OrderDto;
use Modules\Order\Service\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->middleware('auth:client');
        $this->orderService = $orderService;
    }

    public function store(OrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $dto = new OrderDto($request);
            $orders = $this->orderService->createFromCart($dto->dataFromRequest());
            DB::commit();

            return returnMessage(
                true,
                'Order(s) created successfully',
                OrderResource::collection($orders)
            );
        } catch (Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }

    public function index(Request $request)
    {
        try {
            $orders = $this->orderService->getAllOrders(auth('client')->id());

            return returnMessage(true, 'Orders fetched successfully', OrderResource::collection($orders));
        } catch (Exception $e) {
            return returnMessage(false, $e->getMessage(), null, 'server_error');
        }
    }
}
