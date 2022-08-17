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
        $admin = Role::findByName('admin');
        $konfigurasi = Navigation::create([
            'name' => 'Konfigurasi',
            'url' => 'konfigurasi',
            'icon' => 'ti-settings',
            'parent_id' => null,
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'read konfigurasi']);

        $konfigurasi->children()->create([
            'name' => 'Role',
            'url' => 'konfigurasi/roles',
            'icon' => '',
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'read konfigurasi/roles']);

        $konfigurasi->children()->create([
            'name' => 'User',
            'url' => 'konfigurasi/users',
            'icon' => 'ti-user',
            'order' => Navigation::count() + 1,
        ]);
        Permission::create(['name' => 'read konfigurasi/users']);

        $admin->givePermissionTo(Permission::all());
    }
}
