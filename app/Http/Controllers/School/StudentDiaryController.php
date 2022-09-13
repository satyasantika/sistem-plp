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
        $this->middleware('permission:aktivitas/studentdiaries/plp1-read|aktivitas/studentdiaries/plp2-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/studentdiaries/plp1-create|aktivitas/studentdiaries/plp2-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/studentdiaries/plp1-update|aktivitas/studentdiaries/plp2-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/studentdiaries/plp1-delete|aktivitas/studentdiaries/plp2-delete', ['only' => ['destroy']]);
        $this->middleware('permission:'.request()->segment(3).'-read');
    }

    public function index($plp)
    {
        $id = auth()->user()->id;
        $myMapId = Map::firstWhere('student_id',$id)->id;
        $diaries = Diary::where('map_id',$myMapId)->where('plp_order',$plp)->orderBy('day_order')->get();

        return view('aktivitas.logbook',compact('diaries'));
    }

    public function create($plp)
    {
        $studentdiary = new Diary();
        return view('aktivitas.logbook-action', array_merge(
            [
                'studentdiary'=> $studentdiary,
                'plp'=> $plp,
            ],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request, $plp)
    {
        Diary::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Catatan hari ke-<strong>'.$request->day_order.'</strong> telah ditambahkan'
        ]);
    }

    public function edit($plp, Diary $studentdiary)
    {
        return view('aktivitas.logbook-action', array_merge(
            [
                'studentdiary'=> $studentdiary,
                'plp'=> $plp,
            ],
            $this->_dataSelection()
            ));
    }

    public function update($plp, Request $request, Diary $studentdiary)
    {
        $data = $request->all();
        $studentdiary->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Catatan hari ke-<strong>'.$request->day_order.'</strong> telah diperbarui'
        ]);
    }

    public function destroy($plp, Diary $studentdiary)
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
            'myMapId' => Map::firstWhere('student_id', auth()->user()->id)->id,
        ];
    }

}
