<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.user');
    }

    public function create()
    {
        return view('konfigurasi.user-action',['user'=>new User()]);
    }

    public function store(UserRequest $request)
    {
        $request->password = bcrypt($request->password);
        User::create($request->all())->assignRole($request->role);
        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan'
        ]);

    }

    public function show($id)
    {
        //
    }

    public function edit(User $user)
    {
        return view('konfigurasi.user-action', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->subject_id = $request->subject_id;
        $user->save();
        $user->syncRoles($request->role);
        return response()->json([
            'status' => 'success',
            'message' => 'User telah diperbarui'
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User telah dihapus'
        ]);
    }
}
