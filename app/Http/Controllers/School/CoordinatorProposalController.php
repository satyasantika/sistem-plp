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
        $this->middleware('permission:usulan/schoolcoordinators-read', ['only' => ['index','show']]);
        $this->middleware('permission:usulan/schoolcoordinators-create', ['only' => ['create','store']]);
        $this->middleware('permission:usulan/schoolcoordinators-update', ['only' => ['edit','update']]);
        $this->middleware('permission:usulan/schoolcoordinators-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)
                        ->orWhere('coordinator_id',$id)
                        ->pluck('id');
        $coordinators = SchoolUserProposal::whereIn('school_id',$myschool)
                        ->where('role','korguru')
                        ->get();

        return view('usulan.korguru', compact('coordinators'));
    }

    public function create()
    {
        $schoolcoordinator = new SchoolUserProposal();
        return view('usulan.korguru-action', array_merge(
            [
                'schoolcoordinator'=> $schoolcoordinator,
                'myschool'=> School::where('headmaster_id',auth()->id())->get(),
            ]
        ));
    }

    public function store(Request $request)
    {
        SchoolUserProposal::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Usulan <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }


    public function edit(SchoolUserProposal $schoolcoordinator)
    {
        return view('usulan.korguru-action', array_merge(
            [
                'schoolcoordinator'=> $schoolcoordinator,
                'myschool'=> School::where('headmaster_id',auth()->id())->get(),
            ]
        ));
    }

    public function update(Request $request, SchoolUserProposal $schoolcoordinator)
    {
        $data = $request->all();
        $schoolcoordinator->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Usulan <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(SchoolUserProposal $schoolcoordinator)
    {
        $name = $schoolcoordinator->name;

        $schoolcoordinator->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Usulan <strong>'.$name.'</strong> telah dihapus'
        ]);
    }
}
