<?php

namespace App\Http\Controllers\School;

use App\Models\Map;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiaryVerificationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/diaryverifications/plp1-read|aktivitas/diaryverifications/plp2-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/diaryverifications/plp1-update|aktivitas/diaryverifications/plp2-update', ['only' => ['edit','update']]);
    }

    public function index($plp_order)
    {
        return view('aktivitas.studentlogbook-list',compact('plp_order'));
    }

    public function show($plp_order, $map_id)
    {
        $diaries = Diary::where('plp_order',$plp_order)->where('map_id',$map_id)->get();
        return view('aktivitas.studentlogbook',compact('diaries','plp_order'));
    }

    public function update($plp_order, $map_id, Diary $diaryverification)
    {
        $diaryverification->verified = 1;
        $diaryverification->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook diverifikasi'
        ]);
    }

}
