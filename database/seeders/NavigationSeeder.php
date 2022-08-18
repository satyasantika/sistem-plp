<?php

namespace Database\Seeders;

use App\Models\Navigation;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $admin = Role::findByName('admin');

        $konfigurasi = Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-settings',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'konfigurasi-read']);

        $konfigurasi->children()->create([
            'name' => 'Role',
            'url' => 'konfigurasi/roles',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'konfigurasi/roles-read']);

        $konfigurasi->children()->create([
            'name' => 'Permission',
            'url' => 'konfigurasi/permissions',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'konfigurasi/permissions-read']);

        $konfigurasi->children()->create([
            'name' => 'Role-Permission',
            'url' => 'konfigurasi/rolepermissions',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'konfigurasi/rolepermissions-read']);

        $konfigurasi->children()->create([
            'name' => 'Navigation',
            'url' => 'konfigurasi/navigations',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'konfigurasi/navigations-read']);

        $konfigurasi->children()->create([
            'name' => 'User',
            'url' => 'konfigurasi/users',
            'icon' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'konfigurasi/users-read']);

        // $admin->givePermissionTo(Permission::all());
    }
}
