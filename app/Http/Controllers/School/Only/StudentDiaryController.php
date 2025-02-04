<?php

namespace App\Http\Controllers\School\Only;

use App\Models\Map;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentDiaryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/studentdiaries/plp-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/studentdiaries/plp-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/studentdiaries/plp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/studentdiaries/plp-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->user()->id;
        $myMapId = Map::where('year',2025)->firstWhere('student_id',$id)->id;
        $diaries = Diary::where('map_id',$myMapId)->orderBy('day_order')->get();

        return view('aktivitas.only.logbook',compact('diaries'));
    }

    public function create()
    {
        $studentdiary = new Diary();
        return view('aktivitas.only.logbook-action', array_merge(
            [
                'studentdiary'=> $studentdiary,
            ],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        Diary::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Catatan hari ke-<strong>'.$request->day_order.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(Diary $studentdiary)
    {
        return view('aktivitas.only.logbook-action', array_merge(
            [
                'studentdiary'=> $studentdiary,
            ],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Diary $studentdiary)
    {
        $data = $request->all();
        $studentdiary->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Catatan hari ke-<strong>'.$request->day_order.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(Diary $studentdiary)
    {
        $name = $studentdiary->day_order;

        $studentdiary->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Catatan hari ke-<strong>'.$name.'</strong> telah dihapus'
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
            'myMapId' => Map::where('year',2025)->firstWhere('student_id', auth()->user()->id)->id,
        ];
    }

}
