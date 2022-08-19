<?php

namespace App\Http\Controllers;


use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-update|users-update', ['only' => ['edit','update']]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        $userRoles = DB::table("model_has_roles")->where("model_has_roles.model_id",$id)
            ->pluck('model_has_roles.role_id','model_has_roles.role_id')
            ->all();

        return view('konfigurasi.edit.userrole',compact('user','roles','userRoles'));
    }

    public function update(Request $request, $id)
    {
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        User::find($id)->assignRole($request->roles);

        return to_route('users.index')
                        ->with('success','User updated successfully');
    }

}
