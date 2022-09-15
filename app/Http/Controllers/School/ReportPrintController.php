<?php

namespace App\Http\Controllers\School;

use PDF;
use App\Models\Map;
use App\Http\Controllers\Controller;

class ReportPrintController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/reportprint/plp1-read|aktivitas/reportprint/plp2-read');
        $this->middleware('permission:'.request()->segment(3).'-read');
    }

    public function generateCover($plp_order)
    {
        $plp = 'plp'.$plp_order;
        $my_map = Map::firstWhere('student_id',auth()->user()->id);
        $my_school_id = $my_map->school_id;
        $my_lecture_id = $my_map->lecture_id;
        $maps = Map::where([
            'school_id' => $my_school_id,
            'lecture_id' => $my_lecture_id,
            $plp => 1,
        ])->get();

        $data = [
            'my_map' => $my_map,
            'maps' => $maps,
            'plp_order' => $plp_order,
        ];

        $pdf = PDF::loadView('pdf.laporan-master', $data);
        return $pdf->download('cover-laporan.pdf');
    }
}
