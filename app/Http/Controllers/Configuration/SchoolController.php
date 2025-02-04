<?php

namespace App\Http\Controllers\Configuration;

use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use App\DataTables\SchoolDataTable;
use App\Http\Controllers\Controller;

class SchoolController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/schools-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/schools-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/schools-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/schools-delete', ['only' => ['destroy']]);
    }

    public function index(SchoolDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.school');
    }

    public function create()
    {
        $school = new School();
        return view('konfigurasi.school-action', array_merge(
            ['school'=> $school],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        School::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'School <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(School $school)
    {
        return view('konfigurasi.school-action', array_merge(
            ['school'=>$school],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, School $school)
    {
        $data = $request->all();
        $school->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'School <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(School $school)
    {
        $name = $school->name;

        $school->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'School <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'kepsek' =>  User::role('kepsek')->permission('active-read')
                        ->select('id','name')
                        ->orderBy('name')
                        ->get(),
            'korgur' =>  User::role('korguru')->permission('active-read')
                        ->select('id','name')
                        // ->where('is_active',1)
                        ->orderBy('name')
                        ->get(),
        ];
    }

}
