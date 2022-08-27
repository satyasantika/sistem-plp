<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\SchoolUserProposal;
use App\DataTables\SchoolUserProposalDataTable;

class SchoolUserProposalController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/schooluserproposals-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/schooluserproposals-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/schooluserproposals-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/schooluserproposals-delete', ['only' => ['destroy']]);
    }

    public function index(SchoolUserProposalDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.schooluserproposal');
    }

    public function create()
    {
        $schooluserproposal = new SchoolUserProposal();
        return view('konfigurasi.schooluserproposal-action', array_merge(
            ['schooluserproposal'=> $schooluserproposal],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {
        SchoolUserProposal::create($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Data <strong>'.$request->name.'</strong> telah ditambahkan'
        ]);
    }

    public function edit(SchoolUserProposal $schooluserproposal)
    {
        return view('konfigurasi.schooluserproposal-action', array_merge(
            ['schooluserproposal'=> $schooluserproposal],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, SchoolUserProposal $schooluserproposal)
    {
        $data = $request->all();
        $schooluserproposal->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data <strong>'.$request->name.'</strong> telah diperbarui'
        ]);
    }

    public function destroy(SchoolUserProposal $schooluserproposal)
    {
        $name = $schooluserproposal->candidate_name;
        $schooluserproposal->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data <strong>'.$name.'</strong> telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'schools' =>  School::select('id','name')->orderBy('name')->get(),
            'subjects' =>  Subject::select('id','name')->orderBy('name')->get(),
        ];
    }

}
