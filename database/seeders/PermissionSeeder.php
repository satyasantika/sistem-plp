<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $action = ['read', 'create', 'update', 'delete'];
        $access = [
            'roles',
            'users',
            'permissions',
            'konfigurasi',
            'konfigurasi/roles',
            'konfigurasi/permissions',
            'konfigurasi/users',
            'konfigurasi/rolepermissions',
            'konfigurasi/userroles',
            'konfigurasi/userpermissions',
            'konfigurasi/schools',
            'konfigurasi/navigations',
        ];
        $permissions = [];
        foreach ($access as $value1) {
            foreach ($action as $value2) {
                array_push($permissions,$value1.'-'.$value2);
            }
        }

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }    }
}
