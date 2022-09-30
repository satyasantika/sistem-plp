<?php

namespace App\Http\Controllers\Configuration;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

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
        $userPermissions = $user->permissions->pluck('id','id')->all();


        return view('konfigurasi.userpermission-action',compact('user','permissions','userPermissions'));
    }

    public function update(Request $request, $id)
    {
        DB::table('model_has_permissions')->where('model_id',$id)->delete();
        User::find($id)->givePermissionTo($request->permission);

        return response()->json([
            'success' => true,
            'message' => 'Permission untuk <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }
}
