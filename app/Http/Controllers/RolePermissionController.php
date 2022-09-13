<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RolePermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-update', ['only' => ['edit','update']]);
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::orderBy('name')->pluck('name','id');
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        return view('konfigurasi.edit.rolepermission',compact('role','permissions','rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        Role::find($id)->syncPermissions($request->permission);

        return to_route('roles.index')
                        ->with('success','Role updated successfully');
    }

}
