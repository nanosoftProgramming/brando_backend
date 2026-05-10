<?php

namespace Modules\Order\App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Order\App\Http\Requests\UpdateOrderStatusRequest;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Resources\OrderAdminResource;
use Modules\Order\DTO\OrderDto;
use Modules\Order\Service\OrderAdminService;

class OrderAdminController extends Controller
{
    protected $orderAdminService;

    public function __construct(OrderAdminService $orderService)
    {
        $this->middleware('auth:admin');
        $this->middleware('role:Super Admin|Restaurant Manager|Branch Manager');
        $this->orderAdminService = $orderService;
    }

    public function index(Request $request)
    {
        $dto = new OrderDto($request);
        $orders = $this->orderAdminService->findAll($dto->dataFromRequest());

        return returnMessage(true, 'Order geted successfully', OrderAdminResource::collection($orders)->response()->getData(true), 200);
    }
public function show($id)
{
    $order = $this->orderAdminService->findOne($id);

    return returnMessage(
        true,
        'Order fetched successfully',
        new OrderAdminResource($order),
        200
    );
}
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        DB::beginTransaction();
        try {
            $dto = new OrderDto($request);
            $order = $this->orderAdminService->updateStatus($order, $dto->dataFromRequest());

            DB::commit();

            return returnMessage(true, 'Order updated successfully', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $this->orderAdminService->delete($id);

            DB::commit();

            return returnMessage(true, 'Order deleted successfully', [], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return returnMessage(false, $e->getMessage(), null, 500);

        }
    }
}
