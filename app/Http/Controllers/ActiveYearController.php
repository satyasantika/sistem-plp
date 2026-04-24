<?php

namespace App\Http\Controllers;

use App\Models\Map;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ActiveYearController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function set(Request $request)
    {
        $request->validate([
            'year' => [
                'required',
                'integer',
                Rule::in(Map::availableYearsForUser($request->user())->all()),
            ],
        ]);

        session(['active_year' => (int) $request->year]);

        return back();
    }
}
