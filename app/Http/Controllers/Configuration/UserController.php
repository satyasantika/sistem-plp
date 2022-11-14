<?php

namespace App\Http\Controllers\Configuration;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:users-read', ['only' => ['index','show']]);
        $this->middleware('permission:users-create', ['only' => ['create','store']]);
        $this->middleware('permission:users-update', ['only' => ['edit','update']]);
        $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.user');
    }

    public function create()
    {
        $user = new User();

        return view('konfigurasi.user-action', array_merge(
            [ 'user' => $user ],
            $this->_dataSelection(),
        ));
    }

    public function store(UserRequest $request)
    {
        $data = $request->safe()->merge([
            'password'=> bcrypt($request->password),
        ]);
        User::create($data->all())->assignRole($request->role);
        return response()->json([
            'success' => true,
            'message' => 'User <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);

    }

    public function edit(User $user)
    {
        return view('konfigurasi.user-action', array_merge(
            [ 'user' => $user ],
            $this->_dataSelection(),
        ));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $user->fill($data)->save();
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

    private function _dataSelection()
    {
        return [
            'roles' =>  Role::all()->pluck('name')->sort(),
            'subjects' =>  Subject::select('id', 'name')->orderBy('name')->get(),
            'providers' => ['Telkomsel','Indosat Oreedoo'],
            'is_pns' => ['nonPNS','PNS'],
            'golongans' => ['I','II','III','IV'],
            'banks' => ['Mandiri','BRI','BJB','BTN','BCA','BNI'],
        ];
    }

}
