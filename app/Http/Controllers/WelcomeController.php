<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        $school_id = Map::select('school_id')
                            ->where('year',2023)
                            ->groupBy('school_id')
                            ->get();
        $schools = School::whereIn('id',$school_id)->get();
        return view('welcome', compact('schools'));
    }
}
