<?php

namespace App\Http\Controllers\School\Only;

use App\Models\Map;
use App\Models\Diary;
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
        $user = auth()->user();
        $activeYear = Map::activeYear($user);

        $maps = Map::with(['students'])
            ->where('lecture_id', $user->id)
            ->where('subject_id', $user->subject_id)
            ->forYear($activeYear)
            ->withCount([
                'diaries as verified_count' => function ($query) {
                    $query->where('verified', 1);
                },
                'diaries as unverified_count' => function ($query) {
                    $query->where('verified', 0);
                },
                'diaries as total_count',
            ])
            ->orderByDesc('id')
            ->get();

        return view('aktivitas.only.studentlogbook-list', compact('maps', 'activeYear', 'user'));
    }

    public function show($map_id)
    {
        $map = Map::with(['students', 'schools', 'subjects', 'lectures', 'teachers'])->findOrFail($map_id);
        $diaries = Diary::where('map_id', $map_id)->orderBy('day_order')->get();

        return view('aktivitas.only.studentlogbook', compact('diaries', 'map'));
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
