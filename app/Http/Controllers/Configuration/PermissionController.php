<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\PermissionDataTable;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:roles-read', ['only' => ['index','show']]);
        $this->middleware('permission:roles-create', ['only' => ['create','store']]);
        $this->middleware('permission:roles-update', ['only' => ['edit','update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }

    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.permission');
    }

    public function create()
    {
        return view('konfigurasi.permission-action',['permission'=>new Permission()]);
    }

    public function store(PermissionRequest $request)
    {
        Permission::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Permission <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Permission $permission)
    {
        return view('konfigurasi.permission-action', compact('permission'));
    }

    public function update(PermissionRequest $request, Permission $permission)
    {

        $data = $request->all();
        $permission->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Permission $permission)
    {
        $name = $permission->name;
        $permission->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Permission <strong>'.$name.'</strong> telah dihapus'
        ]);
    }
}
