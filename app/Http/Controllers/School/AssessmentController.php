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
        $maps = $this->_myMap(2022,$plp_order);

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
        return response()->json([
            'success' => true,
            'message' => 'assessment <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    // Menu Setiap Form
    public function show($plp_order, $form_id)
    {
        $maps = $this->_myMap(2022,$plp_order);

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
        $my_id = auth()->user()->id;
        $plp = 'plp'.$plp_order;
        return  Map::where('year',$year)
                ->where($plp,1)
                ->where('teacher_id',$my_id)
                ->orWhere('lecture_id',$my_id)
                ->get();
    }

}
