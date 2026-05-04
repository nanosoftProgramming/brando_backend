<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\App\Models\Branch;
use Modules\Client\App\Models\Address;
use Modules\Client\App\Models\Client;
use Modules\Order\App\Models\Order;
use Modules\Order\App\Models\OrderItem;
use Modules\Order\App\Models\OrderStatus;
use Modules\Product\App\Models\BranchProduct;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $clients = Client::all();
        $addresses = Address::all();
        $branches = Branch::all();
        $statuses = OrderStatus::all();
        $branchProducts = BranchProduct::all();

        if ($clients->count() == 0 || $addresses->count() == 0 || $branches->count() == 0 || $statuses->count() == 0 || $branchProducts->count() == 0) {
            $this->command->info('Make sure you have data in clients, addresses, branches, order_statuses, and branch_products tables');

            return;
        }

        for ($i = 1; $i <= 5; $i++) {
            $client = $clients->random();
            $address = $addresses->where('client_id', $client->id)->random();
            $branch = $branches->random();
            $status = $statuses->random();

            $order = Order::create([
                'client_id' => $client->id,
                'address_id' => $address->id,
                'branch_id' => $branch->id,
                'payment_type' => ['cash', 'visa'][array_rand(['cash', 'visa'])],
                'status_id' => $status->id,
                'total' => 0,
                'sub_total' => 0,
                'taxes' => 0,
            ]);

            $total = 0;
            $sub_total = 0;
            $taxes = 0;

            $itemsCount = rand(1, 4);
            for ($j = 1; $j <= $itemsCount; $j++) {
                $product = $branchProducts->random();
                $quantity = rand(1, 10);
                $price = $product->price ?? rand(50, 500);
                $totalPrice = $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'branch_product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $totalPrice,
                ]);

                $sub_total += $totalPrice;
            }

            $taxes = $sub_total * 0.15;
            $total = $sub_total + $taxes;

            $order->update([
                'total' => $total,
                'sub_total' => $sub_total,
                'taxes' => $taxes,
            ]);
        }

        $this->command->info('5 orders with items created successfully.');
    }
}
