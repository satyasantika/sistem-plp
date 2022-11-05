<?php

namespace App\Exports;

use App\Models\Map;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportYudisium implements FromView
{
    public function view(): View
    {
        $plp_order = request()->segment(2);
        // dd($plp_order);
        return view('exports.yudisium', [
            'maps' => Map::where('year',2022)->where($plp_order,1)->orderBy('subject_id')->get(),
        ]);
    }

}
