<?php

namespace App\Http\Controllers\Configuration;

use App\Models\User;
use App\Models\Subject;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use App\DataTables\UserDataTable;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Storage;
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
        // $user->is_active = $user->is_active ? 0 : 1;
        // $user->save();
        $user->can('active-read') ? $user->revokePermissionTo('active-read') : $user->givePermissionTo('active-read');
        $status = $user->can('active-read') ? 'aktiv':'non-aktiv';
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
            'is_pns' => ['nonPNS','PNS','PPPK'],
            'golongans' => ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII','XIII','XIV','XV','XVI','XVII'],
            'banks' => ['Mandiri','BRI','BJB','BTN','BCA','BNI'],
        ];
    }

    public function exportExcel()
	{
		return Excel::download(new SiswaExport, 'siswa.xlsx');
	}

    public function importCreate()
    {
        return view('konfigurasi.import-action');
    }

    public function importExcel(Request $request)
	{
		// validasi
		$this->validate($request, [
			'file' => 'required|mimes:csv,xls,xlsx'
		]);

		// menangkap file excel
		$file = $request->file('file');

        // membuat nama file unik
		$file_name = rand().$file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
		$file->move('import_file',$file_name);

        // import data
		// Excel::import(new UsersImport, public_path('/import_file/'.$file_name));
		Excel::import(new UsersImport, public_path('/import_file/'.$file_name));
		// Excel::import(new UsersImport, $request->file('file')->store('temp'));
        // Storage::delete('/import_file/'.$file_name);
        // notifikasi dengan session
		// Session::flash('sukses','Data Siswa Berhasil Diimport!');

        // alihkan halaman kembali
		// return to_route('users.index');
		// return back();
        return response()->json([
            'status' => 'success',
            'message' => 'User Baru telah ditambahkan'
        ]);
	}

}
