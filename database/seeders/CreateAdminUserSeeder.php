<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin PLP',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('asdfasdf'),
            ]
        );

        $role = Role::firstOrCreate(['name' => 'admin']);

        // Required to unlock sidebar rendering block in nav view.
        $activePermission = Permission::firstOrCreate(['name' => 'active-read']);
        if (!$role->hasPermissionTo($activePermission)) {
            $role->givePermissionTo($activePermission);
        }

        // Ensure admin can see every configured static menu item.
        $menuPermissions = collect(config('menu.items', []))
            ->pluck('permission')
            ->filter()
            ->unique()
            ->values();

        foreach ($menuPermissions as $permissionName) {
            $permission = Permission::firstOrCreate(['name' => $permissionName]);
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }

        if (!$user->hasRole($role->name)) {
            $user->assignRole($role);
        }
    }
}
