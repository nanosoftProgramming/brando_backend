<?php

namespace Modules\Order\Service;

use Modules\Order\App\Models\Order;

class OrderAdminService
{
    public function findAll(array $filters = [], array $paginationData = [])
    {
        $query = Order::with(['client', 'items.branch_product', 'address', 'rate.products.product'])
            ->filter($filters)
            ->available()
            ->latest();

        return getCaseCollection($query, $paginationData);
    }


    public function delete($id)
    {
        $order = Order::findOrFail($id);

        return $order->delete();
    }
    public function findOne($id)
{
    return Order::with([
        'details.branch_product.product',
        'status',
        'client',
        'address.city',
        'branch',
        'rate'
    ])->findOrFail($id);
}

    public function updateStatus(Order $order, array $data): Order
    {
        $order->update([
            'status_id' => $data['status_id'],
        ]);

        return $order->load('status'); // 👈 هذا مهم جدًا
    }
}
