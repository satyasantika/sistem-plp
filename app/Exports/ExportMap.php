<?php

namespace App\Exports;

use App\Models\Map;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExportMap implements FromView
{
    public function view(): View
    {
        return view('exports.maps', [
            'maps' => Map::all()
        ]);
    }

}
