<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\SchoolSchooluserDataTable;

class SchoolSchooluserProposalController extends Controller
{
    public function index(SchoolSchooluserDataTable $dataTable)
    {
        return $dataTable->render('usulan.schooluser');
    }

    public function create()
    {
        return view('usulan.schooluser-action',['schooluser'=>new Schooluser()]);
    }

    public function store(Request $request)
    {
        $data = $request->safe()->merge([
            'password'=> bcrypt($request->password),
            'birth_date'=> date($request->birth_date),
        ]);
        Schooluser::create($data->all())->assignRole($request->role);
        return response()->json([
            'success' => true,
            'message' => 'Schooluser <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);

    }

    public function show($id)
    {
        //
    }

    public function edit(Schooluser $schooluser)
    {
        return view('usulan.schooluser-action', compact('schooluser'));
    }

    public function update(Request $request, Schooluser $schooluser)
    {
        $schooluser->name = $request->name;
        $schooluser->schoolusername = $request->schoolusername;
        $schooluser->email = $request->email;
        $schooluser->subject_id = $request->subject_id;
        $schooluser->birth_place = $request->birth_place;
        if(!is_null($request->birth_date)){
            $schooluser->birth_date = date($request->birth_date);
        }
        $schooluser->gender = $request->gender;
        $schooluser->address = $request->address;
        $schooluser->phone = $request->phone;
        $schooluser->provider = $request->provider;
        $schooluser->is_pns = $request->is_pns;
        $schooluser->golongan = $request->golongan;
        $schooluser->npwp = $request->npwp;
        $schooluser->nomor_rekening = $request->nomor_rekening;
        $schooluser->bank = $request->bank;
        $schooluser->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Schooluser <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Schooluser $schooluser)
    {
        $name = $schooluser->name;
        $schooluser->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Schooluser <strong>'.$name.'</strong> telah dihapus'
        ]);
    }
}
