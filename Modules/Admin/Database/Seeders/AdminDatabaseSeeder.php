<?php

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Admin\App\Models\Admin;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = $this->adminCreation();
        $this->permissionCreation();
        $role = $this->roleCreation();
        $role2 = $this->role2Creation();
        $admin->assignRole($role);
        // $role3 = $this->role3Creation();
    }

    public function adminCreation()
    {
        return $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'phone' => '0123456789',
            'password' => Hash::make('123123'),
            'is_active' => 1,
        ]);
    }

    public function permissionCreation()
    {
        $permissions = [
            ['Index-admin', 'Admin', 'Index'],
            ['Create-admin', 'Admin', 'Create'],
            ['Edit-admin', 'Admin', 'Edit'],
            ['Delete-admin', 'Admin', 'Delete'],

            ['Index-role', 'Roles', 'Index'],
            ['Create-role', 'Roles', 'Create'],
            ['Edit-role', 'Roles', 'Edit'],
            ['Delete-role', 'Roles', 'Delete'],

            ['Index-client', 'Client', 'Index'],
            ['Create-client', 'Client', 'Create'],
            ['Edit-client', 'Client', 'Edit'],
            ['Delete-client', 'Client', 'Delete'],

            ['Index-driver', 'Driver', 'Index'],
            ['Create-driver', 'Driver', 'Create'],
            ['Edit-driver', 'Driver', 'Edit'],
            ['Delete-driver', 'Driver', 'Delete'],

            ['Index-category', 'Category', 'Index'],
            ['Create-category', 'Category', 'Create'],
            ['Edit-category', 'Category', 'Edit'],
            ['Delete-category', 'Category', 'Delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission[0], 'category' => $permission[1], 'guard_name' => 'admin', 'display' => $permission[2]]);
        }
    }

    public function roleCreation()
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $permissions = Permission::all();
        $role->syncPermissions($permissions);

        return $role;
    }

    public function role2Creation()
    {
        $role = Role::create(['name' => 'Restaurant Manager', 'guard_name' => 'admin']);

        return $role;
    }
    // function role3Creation()
    // {
    //     $role = Role::create(['name' => 'Branch Manager', 'guard_name' => 'admin']);
    //     return $role;
    // }
}
