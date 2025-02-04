<?php

namespace App\Http\Controllers\School\Only;

use App\Models\Map;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DiaryVerificationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/diaryverifications/plp-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/diaryverifications/plp-update', ['only' => ['edit','update']]);
    }

    public function index()
    {
        return view('aktivitas.only.studentlogbook-list');
    }

    public function show($map_id)
    {
        $diaries = Diary::where('map_id',$map_id)->get();
        return view('aktivitas.only.studentlogbook',compact('diaries'));
    }

    public function update($map_id, Diary $diaryverification)
    {
        $diaryverification->verified = 1;
        $diaryverification->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Logbook diverifikasi'
        ]);
    }

}
