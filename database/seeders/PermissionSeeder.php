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
        $permissions = [
            'roles-read',
            'roles-create',
            'roles-edit',
            'roles-delete',
            'permissions-read',
            'permissions-create',
            'permissions-edit',
            'permissions-delete'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }    }
}
