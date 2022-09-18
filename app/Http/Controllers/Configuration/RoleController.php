<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Role;
use Illuminate\Http\Request;
use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-read', ['only' => ['index','show']]);
        $this->middleware('permission:roles-create', ['only' => ['create','store']]);
        $this->middleware('permission:roles-update', ['only' => ['edit','update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.role');
    }

    public function create()
    {
        return view('konfigurasi.role-action',['role'=>new Role()]);
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Role <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Role $role)
    {
        return view('konfigurasi.role-action', compact('role'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $data = $request->all();
        $role->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Role <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Role $role)
    {
        $name = $role->name;

        $role->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Role <strong>'.$name.'</strong> telah dihapus'
        ]);
    }
}
