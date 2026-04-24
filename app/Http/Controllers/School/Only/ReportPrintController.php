<?php

namespace App\Http\Controllers\School\Only;

use PDF;
use App\Models\Map;
use App\Http\Controllers\Controller;

class ReportPrintController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/reportprint/plp-read');
    }

    public function generateCover()
    {
        $year = Map::activeYear();
        $my_map = Map::forActiveYear()->firstWhere('student_id', auth()->user()->id);
        $my_school_id = $my_map->school_id;
        $my_lecture_id = $my_map->lecture_id;
        $maps = Map::where([
            'school_id' => $my_school_id,
            'lecture_id' => $my_lecture_id,
            'year' => $year,
        ])->get();

        $data = [
            'my_map' => $my_map,
            'maps' => $maps,
        ];

        $pdf = PDF::loadView('pdf.only.laporan-master', $data);
        return $pdf->download('cover-laporan.pdf');
    }
}
