<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        // PermissionSeeder mengandaikan peran admin sudah ada (dibuat CreateAdminUserSeeder).
        Role::create(['name' => 'admin']);
        $this->seed(PermissionSeeder::class);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function test_admin_can_show_roles_page()
    {
        $user = User::factory()->create()->assignRole('admin');
        $this->assertTrue($user->can('roles-read'));

        $response = $this->actingAs($user)
                    ->get(route('roles.index'));
        $response->assertOk();
    }

    public function test_non_admin_cannot_show_roles_page()
    {
        $role = Role::whereNotIn('name', ['admin'])->get()->random()->name;
        $user = User::factory()->create()->assignRole($role);
        $this->assertFalse($user->can('roles-read'));

        $response = $this->actingAs($user)
                        ->get(route('roles.index'));
        $response->assertForbidden();
    }
}
