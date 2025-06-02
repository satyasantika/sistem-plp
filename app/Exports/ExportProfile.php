<?php

namespace App\Exports;

use App\Models\Map;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportProfile implements FromView
{
    public function view(): View
    {
        return view('exports.profile', [
            'teacher_maps' => Map::select('teacher_id','school_id')->where('year',date('Y'))->where('plp',1)->groupBy('teacher_id','school_id')->get(),
            'headmaster_maps' => Map::select('school_id')->where('year',date('Y'))->where('plp',1)->groupBy('school_id')->get(),
            'coordinator_maps' => Map::select('school_id')->where('year',date('Y'))->where('plp',1)->groupBy('school_id')->get(),
        ]);
    }
}
