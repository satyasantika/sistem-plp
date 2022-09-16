<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\User;
use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\DataTables\MapDataTable;

class MapController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/maps-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/maps-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/maps-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/maps-delete', ['only' => ['destroy']]);
    }

    public function index(MapDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.map');
    }

    public function create()
    {
        $map = new Map();
        return view('konfigurasi.map-action', array_merge(
            ['map'=> $map],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Map::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data telah ditambahkan'
        ]);
    }

    public function edit(Map $map)
    {
        return view('konfigurasi.map-action', array_merge(
            ['map'=> $map],
            $this->_dataSelection($map->subject_id),
            ));
    }

    public function update(Request $request, Map $map)
    {
        $data = $request->all();
        $map->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data telah diperbarui'
        ]);
    }

    public function destroy(Map $map)
    {
        $name = $map->candidate_name;
        $map->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data telah dihapus'
        ]);
    }

    private function _dataSelection($subject_id = '')
    {
        $student_in_map = Map::whereNotNull('student_id')->where('subject_id',$subject_id)->pluck('student_id');
        $students = User::role('mahasiswa')->where('subject_id',$subject_id)->whereNotIn('id',$student_in_map);
        $lectures = User::role('dosen')->where('subject_id',$subject_id);
        $teachers = User::role('guru')->where('subject_id',$subject_id);
        if ($subject_id == '') {
            $student_in_map = Map::whereNotNull('student_id')->pluck('student_id');
            $students = User::role('mahasiswa')->whereNotIn('id',$student_in_map);
            $lectures = User::role('dosen');
            $teachers = User::role('guru');
        }

        return [
            'students' => $students->select('id','name')->orderBy('name')->get(),
            'lectures' => $lectures->select('id','name')->orderBy('name')->get(),
            'teachers' => $teachers->select('id','name')->orderBy('name')->get(),
            'schools' =>  School::select('id','name')->orderBy('id')->get(),
            'subjects' =>  Subject::select('id','name')->orderBy('name')->get(),
        ];
    }

}
