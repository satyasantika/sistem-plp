<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permission = Permission::firstOrCreate(['name' => 'report/summary/plp-read']);

        foreach (['data', 'kajur', 'kepsek', 'korguru', 'admin'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role && ! $role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }
    }

    public function down(): void
    {
        $permission = Permission::query()->where('name', 'report/summary/plp-read')->first();

        if (! $permission) {
            return;
        }

        foreach (['data', 'kajur', 'kepsek', 'korguru'] as $roleName) {
            $role = Role::query()->where('name', $roleName)->first();

            if ($role && $role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
            }
        }
    }
};
