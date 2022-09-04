<?php

namespace App\Http\Controllers\School;

use App\Models\Map;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentDiaryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/studentdiaries-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/studentdiaries-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/studentdiaries-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/studentdiaries-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->user()->id;
        $myMapId = Map::firstWhere('student_id',$id)->id;
        $diaries = Diary::where('map_id',$myMapId)->where('plp_order',1)->orderBy('day_order')->get();

        return view('aktivitas.logbook',compact('diaries'));
    }

    public function create()
    {
        $studentdiary = new Diary();
        return view('aktivitas.logbook-action', array_merge(
            ['studentdiary'=> $studentdiary],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Diary::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Diary <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Diary $studentdiary)
    {
        return view('aktivitas.logbook-action', array_merge(
            ['studentdiary'=>$studentdiary],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Diary $studentdiary)
    {
        $data = $request->all();
        $studentdiary->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Diary <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Diary $studentdiary)
    {
        $name = $studentdiary->id;

        $studentdiary->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Diary <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        $days = [];
        for ($i=1; $i <=30; $i++) {
            array_push($days,$i);
        }

        return [
            'days' => $days,
            'myMapId' => Map::firstWhere('student_id', auth()->user()->id)->id,
        ];
    }

}
