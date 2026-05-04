<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('order_statuses')->insert([
            ['title' => 'Pending',     'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Confirmed',   'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Processing',  'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Shipped',     'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Delivered',   'created_at' => now(), 'updated_at' => now()],
            ['title' => 'Cancelled',   'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
