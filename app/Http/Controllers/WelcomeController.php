<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index()
    {
        $schools = School::all();
        return view('welcome', compact('schools'));
    }
}
