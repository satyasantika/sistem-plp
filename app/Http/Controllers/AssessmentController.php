<?php

namespace App\Http\Controllers;

use App\Models\Map;
use App\Models\Form;
use App\Models\Assessment;
use Illuminate\Http\Request;
use App\DataTables\AssessmentDataTable;

class AssessmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:konfigurasi/assessments-read', ['only' => ['index','show']]);
        $this->middleware('permission:konfigurasi/assessments-create', ['only' => ['create','store']]);
        $this->middleware('permission:konfigurasi/assessments-update', ['only' => ['edit','update']]);
        $this->middleware('permission:konfigurasi/assessments-delete', ['only' => ['destroy']]);
    }

    public function index(AssessmentDataTable $dataTable)
    {
        return $dataTable->render('konfigurasi.assessment');
    }

    public function create()
    {
        $assessment = new Assessment();
        return view('konfigurasi.assessment-action', array_merge(
            ['assessment'=> $assessment],
            $this->_dataSelection()
            ));
    }

    public function store(Request $request)
    {

        $data = $request->merge([
            'grade' => $request->score1
                        + $request->score2
                        + $request->score3
                        + $request->score4
                        + $request->score5
                        + $request->score6
                        + $request->score7
                        + $request->score8
                        + $request->score9
            ,
        ]);
        Assessment::create($data->all());
        return response()->json([
            'success' => true,
            'message' => 'Data Penilaian telah ditambahkan'
        ]);
    }

    public function edit(Assessment $assessment)
    {
        return view('konfigurasi.assessment-action', array_merge(
            ['assessment'=>$assessment],
            $this->_dataSelection()
            ));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $data = $request->all();
        $data['grade'] = $request->score1
                        + $request->score2
                        + $request->score3
                        + $request->score4
                        + $request->score5
                        + $request->score6
                        + $request->score7
                        + $request->score8
                        + $request->score9
            ;

        $assessment->fill($data)->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Penilaian telah diperbarui'
        ]);
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Data Penilaian telah dihapus'
        ]);
    }

    private function _dataSelection()
    {
        return [
            'maps' =>  Map::all(),
            'forms' =>  Form::whereNot('type','yes_no')->pluck('id')->sort(),
            'form_order' => [1,2,3,4,5,6],
            'items' => ['score1','score2','score3','score4','score5','score6','score7','score8','score9'],
        ];
    }

}
