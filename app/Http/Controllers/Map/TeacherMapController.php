<?php

namespace App\Http\Controllers\Map;

use App\Models\Map;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use App\Models\SchoolUserProposal;
use App\Http\Controllers\Controller;

class TeacherMapController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:mapping/teachermaps-read', ['only' => ['index','show']]);
        $this->middleware('permission:mapping/teachermaps-update', ['only' => ['edit','update']]);
    }

    public function index()
    {
        $my_subject_maps = Map::where('school_id',$this->_mySchoolId())
                                ->where('year',2022)
                                ->orderBy('subject_id')
                                ->get();
        return view('map.teachermap',compact('my_subject_maps'));
    }

    public function edit(Map $teachermap)
    {
        $teachers = SchoolUserProposal::where('school_id',$this->_mySchoolId())
                        ->orderBy('subject_id')
                        ->orderBy('name')
                        ->get();
        return view('map.teachermap-action', [
                'teachers' => $teachers,
                'teachermap'=> $teachermap,
                'school'=> $teachermap->schools->name,
            ]);
    }

    public function update(Request $request, Map $teachermap)
    {
        $data = $request->all();
        $teachermap->fill($data)->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah diperbarui',
        ]);
    }

    private function _mySchoolId()
    {
        $id = auth()->user()->id;
        return School::where('headmaster_id',$id)->orWhere('headmaster_id',$id)->first()->id;
    }
}
