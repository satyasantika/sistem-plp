<?php

namespace App\Http\Controllers\School\Only;

use App\Models\Map;
use App\Models\Diary;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GuardsDiaryDuplicates;

class StudentDiaryController extends Controller
{
    use GuardsDiaryDuplicates;

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
        $map = Map::forActiveYear()
            ->with(['students', 'schools', 'subjects', 'lectures', 'teachers'])
            ->firstWhere('student_id', $id);

        $myMapId = $map->id;
        $diaries = Diary::where('map_id', $myMapId)->orderBy('day_order')->get();

        return view('aktivitas.only.logbook', compact('diaries', 'map'));
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
        $map = Map::forActiveYear()->firstWhere('student_id', auth()->user()->id);
        if (! $map) {
            return response()->json([
                'success' => false,
                'message' => 'Plot mahasiswa untuk tahun aktif tidak ditemukan.',
            ], 422);
        }

        $incoming = $request->filled('plp_order') ? $request->integer('plp_order') : null;
        $plpOrder = $this->resolvedDiaryPlpOrder($map, $incoming);
        $request->merge([
            'map_id' => $map->id,
            'plp_order' => $plpOrder,
        ]);
        if ($response = $this->assertDiaryDateUnique($request, $map, $plpOrder)) {
            return $response;
        }

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
        $map = Map::find($studentdiary->map_id);
        if (! $map) {
            return response()->json([
                'success' => false,
                'message' => 'Plot tidak ditemukan.',
            ], 422);
        }

        $incoming = $request->filled('plp_order')
            ? $request->integer('plp_order')
            : ($studentdiary->plp_order !== null ? (int) $studentdiary->plp_order : null);
        $plpOrder = $this->resolvedDiaryPlpOrder($map, $incoming);
        $request->merge(['plp_order' => $plpOrder]);
        if ($response = $this->assertDiaryDateUnique($request, $map, $plpOrder, $studentdiary)) {
            return $response;
        }

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
            'myMapId' => Map::forActiveYear()->firstWhere('student_id', auth()->user()->id)->id,
        ];
    }

}
