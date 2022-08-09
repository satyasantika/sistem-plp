<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    public function testCanShowRolesPage()
    {
        $user = User::role('admin')->first();
        $response = $this->actingAs($user)
                    ->get('/roles');
        $response->assertOk();
    }
    public function testCannotShowRolesPage()
    {
        $role = Role::whereNotIn('name',['admin'])->get()->random()->name;
        $user = User::role($role)->first();
        $response = $this->actingAs($user)
                        ->get('/roles');
        $response->assertForbidden();
    }
}
