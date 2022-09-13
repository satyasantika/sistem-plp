<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use DB;
class UserPermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-update|users-update', ['only' => ['edit','update']]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $permissions = Permission::orderBy('name')->pluck('name','id');
        $userPermissions = $user->getAllPermissions()->pluck('id','id')->all();


        return view('konfigurasi.edit.userpermission',compact('user','permissions','userPermissions'));
    }

    public function update(Request $request, $id)
    {
        DB::table('model_has_permissions')->where('model_id',$id)->delete();
        User::find($id)->givePermissionTo($request->permission);

        return to_route('users.index')
                        ->with('success','User updated successfully');
    }
}
