<?php

namespace App\Http\Controllers\School;

use App\Models\School;
use App\Models\SchoolUserProposal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoordinatorProposalController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:usulan/school_coordinators-read', ['only' => ['index','show']]);
        $this->middleware('permission:usulan/school_coordinators-create', ['only' => ['create','store']]);
        $this->middleware('permission:usulan/school_coordinators-update', ['only' => ['edit','update']]);
        $this->middleware('permission:usulan/school_coordinators-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->pluck('id');
        $coordinators = SchoolUserProposal::whereIn('school_id',$myschool)->where('candidate_role','korgur')->get();

        return view('usulan.korgur', compact('coordinators'));
    }

    public function create()
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->get();

        return view('usulan.korgur-create',compact('myschool'));
    }

    public function store(Request $request)
    {
        SchoolUserProposal::create($request->all());
        return $this->index();
    }

    public function show($id)
    {
        //
    }

    public function edit(SchoolUserProposal $coordinator)
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->get();

        return view('usulan.korgur-edit', compact('coordinator','myschool'));
    }

    public function update(Request $request, SchoolUserProposal $coordinator)
    {

        $coordinator->candidate_name = $request->candidate_name;
        $coordinator->school_id = $request->school_id;
        $coordinator->save();

        return $this->index();
    }

    public function destroy(SchoolUserProposal $coordinator)
    {
        $coordinator->delete();
        return $this->index();
    }
}
