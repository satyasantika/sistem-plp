<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
// use Illuminate\Support\ValidatedInput;

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
        $data = $request->safe()->merge([
            'password'=> bcrypt($request->password),
            'birth_date'=> date($request->birth_date),
        ]);
        User::create($data->all())->assignRole($request->role);
        return response()->json([
            'success' => true,
            'message' => 'User <strong>'.$request->name.'</strong> telah ditambahkan'
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
        $user->birth_place = $request->birth_place;
        $user->birth_date = date($request->birth_date);
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->provider = $request->provider;
        $user->is_pns = $request->is_pns;
        $user->golongan = $request->golongan;
        $user->npwp = $request->npwp;
        $user->nomor_rekening = $request->nomor_rekening;
        $user->bank = $request->bank;
        $user->save();
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$name.'</strong> telah dihapus'
        ]);
    }
}
