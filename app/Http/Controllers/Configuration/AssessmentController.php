<?php

namespace App\Http\Controllers\Configuration;

use App\Models\Map;
use App\Models\Form;
use App\Models\Assessment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\AssessmentDataTable;
use App\Http\Controllers\Controller\Map\YudisiumController;

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
        $map_id = $request->map_id;
        $request->plp_order == 1 ? $this->_yudicium1($map_id) : $this->_yudicium2($map_id) ;

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
        $map_id = $assessment->map_id;
        $assessment->plp_order == 1 ? $this->_yudicium1($map_id) : $this->_yudicium2($map_id) ;

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
            'maps' =>  Map::where('year',2023)->whereNotNull('student_id')->get(),
            'forms' =>  Form::whereNot('type','yes_no'),
            'form_order' => [1,2,3,4,5,6],
            'items' => ['score1','score2','score3','score4','score5','score6','score7','score8','score9'],
        ];
    }

    private function _yudicium1($map_id)
    {
        $map = Map::find($map_id);
        $lecture_forms = ['2022N2','2022N8'];

        $total_grade = Assessment::where([
                                            'assessor'=>'dosen',
                                            'plp_order'=>1,
                                            'map_id'=>$map_id,
                                        ])
                                    ->sum('grade');
        // dd($map);
        $grade = $total_grade/count($lecture_forms);

        $map->grade1 = round($grade,2);
        $map->letter1 = $this->_convertToLetter($grade);
        $map->save();

    }

    private function _yudicium2($map_id)
    {
        $map = Map::find($map_id);
        $lecture_forms = ['2022N2','2022N6','2022N7'];
        $teacher_forms = ['2022N1','2022N3','2022N4','2022N5','2022N6','2022N7'];
        // penilaian dari dosen
        $assessment_by_lecture = Assessment::where([
                                            'assessor'=>'dosen',
                                            'plp_order'=>2,
                                            'map_id'=>$map_id,
                                        ])
                                        ->whereIn('form_id',$lecture_forms)
                                        ->sum('grade');
        $lecture_form_times = Form::whereIn('id',$lecture_forms)->sum('times');
        $lecture_total = round($assessment_by_lecture/$lecture_form_times,0);
        // penilaian dari guru
        $assessment_by_teacher = Assessment::where([
                                            'assessor'=>'guru',
                                            'plp_order'=>2,
                                            'map_id'=>$map_id,
                                        ])
                                        ->whereIn('form_id',$teacher_forms)
                                        ->sum('grade');
        $teacher_form_times = Form::whereIn('id',$teacher_forms)->sum('times');
        $teacher_total = $assessment_by_teacher/$teacher_form_times;

        $grade = 0.4 * $lecture_total + 0.6 * $teacher_total;

        $map->grade2 = round($grade,2);
        $map->letter2 = $this->_convertToLetter($grade);
        $map->save();
    }

    private function _convertToLetter($grade)
    {
        if ($grade >= 85)
        { return 'A'; }
        elseif ($grade >= 77)
        { return 'A-'; }
        elseif ($grade >= 69)
        { return 'B+'; }
        elseif ($grade >= 61)
        { return 'B'; }
        elseif ($grade >= 53)
        { return 'B-'; }
        elseif ($grade >= 45)
        { return 'C+'; }
        elseif ($grade >= 37)
        { return 'C'; }
        elseif ($grade >= 29)
        { return 'C-'; }
        elseif ($grade >= 21)
        { return 'D'; }
        else
        { return 'E'; }
    }

    private function _convertToLetter5($grade)
    {
        if ($grade < 56)
        { return 'E'; }
        elseif ($grade < 66)
        { return 'D'; }
        elseif ($grade < 76)
        { return 'C'; }
        elseif ($grade < 86)
        { return 'B'; }
        else
        { return 'A'; }

    }

}
