<?php

namespace App\Http\Controllers\Configuration;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

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

    public function activation(User $user)
    {
        $name = strtoupper($user->name);
        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();
        $status = $user->is_active ? 'aktiv':'non-aktiv';
        $user->is_active ? $user->givePermissionTo('active-read') : $user->revokePermissionTo('active-read');
        // return to_route('users.index')->with('success','user '.$name.' telah di'.$status.'kan');
        return response()->json([
            'status' => 'success',
            'message' => 'User <strong>'.$name.'</strong> telah di'.$status.'kan'
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

    public function export()
	{
		return Excel::download(new SiswaExport, 'siswa.xlsx');
	}

    public function importCreate()
    {
        return view('konfigurasi.import-action');
    }

    public function import(Request $request)
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

        // membuat nama file unik
		$nama_file = rand().$file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
		$file->move('file_siswa',$nama_file);

        // import data
		Excel::import(new SiswaImport, public_path('/file_siswa/'.$nama_file));

        // notifikasi dengan session
		// Session::flash('sukses','Data Siswa Berhasil Diimport!');

        // alihkan halaman kembali
		return to_route('users.index');
        // return response()->json([
        //     'success' => true,
        //     'message' => 'User <strong>'.$request->name.'</strong> telah ditambahkan'
        // ]);
	}

}
