<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Support\RolePermissionUi;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-update', ['only' => ['edit','update']]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::orderBy('name')->get(['id', 'name']);

        $rolePermissions = DB::table('role_has_permissions')
            ->where('role_has_permissions.role_id', $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $permissionUi = RolePermissionUi::build($permissions, $rolePermissions);

        return view('konfigurasi.rolepermission-action', compact('role', 'rolePermissions', 'permissionUi'));
    }

    public function update(Request $request, $id)
    {
        Role::find($id)->syncPermissions($request->permission);

        return response()->json([
            'success' => true,
            'message' => 'Permission untuk <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

}
