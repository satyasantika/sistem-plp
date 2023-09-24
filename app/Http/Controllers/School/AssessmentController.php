<?php

namespace App\Http\Controllers\School;

use App\Models\Map;
use App\Models\Form;
use App\Models\FormItem;
use App\Models\Assessment;
use Illuminate\Http\Request;
// use Illuminate\Auth\Access\Gate;
use App\Http\Controllers\Controller;

class AssessmentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:aktivitas/schoolassessments/plp1-read|aktivitas/schoolassessments/plp2-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp1-create|aktivitas/schoolassessments/plp2-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp1-update|aktivitas/schoolassessments/plp2-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp1-delete|aktivitas/schoolassessments/plp2-delete', ['only' => ['destroy']]);
        $this->middleware('permission:'.request()->segment(3).'-read');
    }

    // Rekap Penilaian
    public function index($plp_order)
    {
        $maps = $this->_myMap(2023,$plp_order);

        if (auth()->user()->hasrole('dosen'))
        {
            $plp1_dosen_menus = ['2022N2','2022N8'];
            $plp2_dosen_menus = ['2022N2','2022N6','2022N7'];
            $forms = ($plp_order == 1) ? $plp1_dosen_menus : $plp2_dosen_menus ;
        } else {
            $forms = ['2022N1','2022N3','2022N4','2022N5','2022N6','2022N7'];
        }

        return view('aktivitas.assessment-resume',compact('maps','forms'));
    }

    public function create($plp_order, $form_id, $form_order, $map_id)
    {
        $schoolassessment = new Assessment();
        return view('aktivitas.assessment-action', array_merge(
            ['schoolassessment'=> $schoolassessment],
            $this->_dataSelection($plp_order, $form_id, $form_order, $map_id)
            ));
    }

    public function store($plp_order, $form_id, $form_order, $map_id, Request $request)
    {
        $form = Form::find($form_id);
        $grade = 0;
        for ($i=0; $i < $form->count; $i++) {
            $score = 'score'.$i+1;
            $grade += $request->$score;
        }
        $final_grade = ($form->type == 'skor_4') ? round(100 * $grade/(4*$form->count),2) : $grade;

        $data = $request->merge([
            'grade' => $final_grade,
        ]);
        Assessment::create($data->all());

        $plp_order == 1 ? $this->_yudicium1($map_id) : $this->_yudicium2($map_id) ;

        return response()->json([
            'success' => true,
            'message' => 'assessment <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    // Menu Setiap Form
    public function show($plp_order, $form_id)
    {
        $maps = $this->_myMap(2023,$plp_order);

        return view('aktivitas.assessment',compact('plp_order','maps','form_id'));
    }

    public function edit($plp_order, $form_id, $form_order, $map_id, Assessment $schoolassessment)
    {
        return view('aktivitas.assessment-action', array_merge(
            ['schoolassessment'=>$schoolassessment],
            $this->_dataSelection($plp_order, $form_id, $form_order, $map_id)
            ));
    }

    public function update($plp_order, $form_id, $form_order, $map_id, Request $request, Assessment $schoolassessment)
    {
        $data = $request->all();

        $form = Form::find($form_id);
        $grade = 0;
        for ($i=0; $i < $form->count; $i++) {
            $score = 'score'.$i+1;
            $grade += $request->$score;
        }

        $final_grade = ($form->type == 'skor_4') ? round(100 * $grade/(4*$form->count),2) : $grade;
        $schoolassessment->grade = $final_grade;

        $schoolassessment->fill($data)->save();

        $plp_order == 1 ? $this->_yudicium1($map_id) : $this->_yudicium2($map_id) ;

        return response()->json([
            'status' => 'success',
            'message' => 'assessment <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    private function _dataSelection($plp_order, $form_id, $form_order, $map_id)
    {
        return [
            'form' => Form::find($form_id),
            'form_guides' => $this->_formByComponent($form_id,'petunjuk'),
            'form_items' => $this->_formByComponent($form_id,'item'),
            'form_extras' => $this->_formByComponent($form_id,'tambahan'),
            'kebaikan' => ['sangat kurang','kurang','baik', 'sangat baik'],
            'keterpenuhan' => ['tidak terpenuhi semua aspek','hanya 1 aspek ada','2 aspek ada', '3 aspek ada'],
            'parameters' => [
                'form_id'=>$form_id,
                'plp_order' => $plp_order,
                'form_order' => $form_order,
                'map_id' => $map_id,
                ]
        ];
    }

    private function _formByComponent($form_id, $component)
    {
        return FormItem::where('form_id',$form_id)->where('component',$component)->orderBy('component_order')->get();
    }

    private function _myMap($year, $plp_order)
    {
        $plp = 'plp'.$plp_order;
        return  Map::where('year',$year)
                ->where($plp,1)
                ->where(function($query) {
                $query->where('teacher_id',auth()->user()->id)
                        ->orWhere('lecture_id',auth()->user()->id);
                })
                ->whereNotNull('student_id')
                ->get();
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
