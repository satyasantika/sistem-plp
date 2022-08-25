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
        $this->middleware('permission:usulan/coordinators-read', ['only' => ['index','show']]);
        $this->middleware('permission:usulan/coordinators-create', ['only' => ['create','store']]);
        $this->middleware('permission:usulan/coordinators-update', ['only' => ['edit','update']]);
        $this->middleware('permission:usulan/coordinators-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->pluck('id');
        // dd($myschool);
        $coordinators = SchoolUserProposal::whereIn('school_id',$myschool)->get();
        // dd($coordinators);
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
        $myschool = School::where('headmaster_id',$id)->get();
        // dd($myschool);
        // $coordinators = SchoolUserProposal::whereIn('school_id',$myschool)->get();

        return view('usulan.korgur-edit', compact('coordinator','myschool'));
    }

    public function update(Request $request, SchoolUserProposal $schooluserproposal)
    {

        $school->candidat_name = $request->candidat_name;
        $school->address = $request->address;
        $school->headmaster_id = $request->headmaster_id;
        $school->coordinator_id = $request->coordinator_id;
        $school->save();

        return response()->json([
            'status' => 'success',
            'message' => 'School <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(SchoolUserProposal $coordinator)
    {
        $coordinator->delete();
        return $this->index();
    }
}
