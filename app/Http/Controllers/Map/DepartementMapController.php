<?php

namespace App\Http\Controllers\Map;

use App\Models\Map;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartementMapController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:mapping/departementmaps-read', ['only' => ['index','show']]);
        $this->middleware('permission:mapping/departementmaps-create', ['only' => ['create','store']]);
        $this->middleware('permission:mapping/departementmaps-update', ['only' => ['edit','update']]);
        $this->middleware('permission:mapping/departementmaps-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $my_subject_maps = $this->_mySubjectMap();
        return view('map.departementmap',compact('my_subject_maps'));
    }

    public function edit(Map $departementmap)
    {
        return view('map.departementmap-action', array_merge(
            $this->_dataSelection(),
            [
                'departementmap'=> $departementmap,
                'school'=> $departementmap->schools->name,
            ],
            ));
    }

    public function update(Request $request, Map $departementmap)
    {
        $data = $request->all();
        $departementmap->fill($data)->save();
        // return index();
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah diperbarui',
        ]);
    }

    private function _mySubjectId()
    {
        return auth()->user()->subject_id;
    }

    private function _mySubjectMap()
    {
        return Map::where('subject_id',$this->_mySubjectId())->get();

    }
    private function _dataSelection()
    {
        $my_student_id_in_maps = Map::where('subject_id',$this->_mySubjectId())
                                    ->whereNotNull('student_id')
                                    ->pluck('student_id');
        $my_lectures = User::role('dosen')->where('subject_id',$this->_mySubjectId())->get();

        return [
            'students' => User::role('mahasiswa')
                                ->select('id','name')
                                ->where('subject_id',$this->_mySubjectId())
                                ->whereNotIn('id',$my_student_id_in_maps)
                                ->orderBy('name')
                                ->get(),
            'lectures' => User::role('dosen')
                                ->where('subject_id',$this->_mySubjectId())
                                ->orderBy('name')
                                ->get(),
        ];
    }

}
