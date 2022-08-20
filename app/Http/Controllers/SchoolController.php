<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use App\DataTables\SchoolDataTable;

class SchoolController extends Controller
{
    public function index(SchoolDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.school');
    }

    public function create()
    {
        return view('konfigurasi.school-action', ['school'=>new School()]);
    }

    public function store(Request $request)
    {
        School::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'School <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit(School $school)
    {
        return view('konfigurasi.school-action', compact('school'));
    }

    public function update(Request $request, School $school)
    {

        $school->name = $request->name;
        $school->address = $request->address;
        $school->headmaster_id = $request->headmaster_id;
        $school->coordinator_id = $request->coordinator_id;
        $school->save();

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
}
