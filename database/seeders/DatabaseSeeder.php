<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\AdminDatabaseSeeder;
use Modules\Country\Database\Seeders\CountryDatabaseSeeder;
use Modules\Order\Database\Seeders\OrderStatusSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminDatabaseSeeder::class,
            CountryDatabaseSeeder::class,
            OrderStatusSeeder::class,


        ]);
    }
}
