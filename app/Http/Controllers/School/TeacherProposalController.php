<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherProposalController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:usulan/teachers-read', ['only' => ['index','show']]);
        $this->middleware('permission:usulan/teachers-create', ['only' => ['create','store']]);
        $this->middleware('permission:usulan/teachers-update', ['only' => ['edit','update']]);
        $this->middleware('permission:usulan/teachers-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->pluck('id');
        $teachers = SchoolUserProposal::whereIn('school_id',$myschool)->where('candidate_role','korgur')->get();

        return view('usulan.korgur', compact('teachers'));
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

    public function edit(SchoolUserProposal $teacher)
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->get();

        return view('usulan.korgur-edit', compact('teacher','myschool'));
    }

    public function update(Request $request, SchoolUserProposal $teacher)
    {

        $teacher->candidate_name = $request->candidate_name;
        $teacher->school_id = $request->school_id;
        $teacher->save();

        return $this->index();
    }

    public function destroy(SchoolUserProposal $teacher)
    {
        $teacher->delete();
        return $this->index();
    }}
