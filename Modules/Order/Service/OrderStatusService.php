<?php

namespace Modules\Order\Service;

use Modules\Order\App\Models\OrderStatus;

class OrderStatusService
{
    public function getAll()
    {
        return OrderStatus::all();
    }

    public function getById($id)
    {
        return OrderStatus::findOrFail($id);
    }

    public function create(array $data)
    {
        return OrderStatus::create($data);
    }

    public function update($id, array $data)
    {
        $status = OrderStatus::findOrFail($id);
        $status->update($data);

        return $status;
    }

    public function delete($id)
    {
        $status = OrderStatus::findOrFail($id);
        $status->delete();

        return true;
    }
}
