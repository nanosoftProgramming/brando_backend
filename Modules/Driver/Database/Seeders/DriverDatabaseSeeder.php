<?php

namespace Modules\Driver\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Driver\App\Models\Driver;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DriverDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // create role
        $role = Role::firstOrCreate([
            'name' => 'driver',
            'guard_name' => 'driver'
        ]);

        // create driver 1
        $driver1 = Driver::create([
            'name' => 'Test Driver',
            'email' => 'driver@test.com',
            'phone' => '01000000000',
            'password' => Hash::make('123123'),
            'is_active' => 1,
        ]);

        $driver1->assignRole($role);

        // create driver 2
        $driver2 = Driver::create([
            'name' => 'Driver 2',
            'email' => 'driver2@test.com',
            'phone' => '01000000001',
            'password' => Hash::make('123123'),
            'is_active' => 1,
            'license_id' => 'LIC-0001',
        ]);

        $driver2->assignRole($role);
    }
}