<?php

namespace App\Http\Controllers\School;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\SchoolUserProposal;
use App\Http\Controllers\Controller;

class TeacherProposalController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:usulan/schoolteachers-read', ['only' => ['index','show']]);
        $this->middleware('permission:usulan/schoolteachers-create', ['only' => ['create','store']]);
        $this->middleware('permission:usulan/schoolteachers-update', ['only' => ['edit','update']]);
        $this->middleware('permission:usulan/schoolteachers-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $id = auth()->id();
        $myschool = School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->pluck('id');
        $teachers = SchoolUserProposal::whereIn('school_id',$myschool)->where('role','guru')->get();

        return view('usulan.guru', compact('teachers'));
    }

    public function create()
    {
        $schoolteacher = new SchoolUserProposal();
        return view('usulan.guru-action', array_merge(
            [
                'schoolteacher'=> $schoolteacher,
            ], $this->_dataSelection()
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

    public function edit(SchoolUserProposal $schoolteacher)
    {
        return view('usulan.guru-action', array_merge(
            [
                'schoolteacher'=> $schoolteacher,
            ], $this->_dataSelection()
        ));
    }

    public function update(Request $request, SchoolUserProposal $schoolteacher)
    {
        $data = $request->all();
        $schoolteacher->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Usulan <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }
    public function destroy(SchoolUserProposal $schoolteacher)
    {
        $name = $schoolteacher->name;

        $schoolteacher->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Usulan <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        $id = auth()->id();
        return [
            'myschool'=> School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->get(),
            'subjects'=> Subject::orderBy('id')->get(),
        ];
    }
}
