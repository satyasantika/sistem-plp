<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\DataTables\RoleDataTable;
use App\Http\Requests\RoleRequest;

class RoleController extends Controller
{
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
            'message' => 'Role berhasil ditambahkan'
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit(Role $role)
    {
        return view('konfigurasi.role-action', compact('role'));
    }

    public function update(RoleRequest $request, Role $role)
    {

        $role->name = $request->name;
        $role->guard_name = $request->guard_name;
        $role->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil diperbarui'
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Role berhasil dihapus'
        ]);
    }
}
