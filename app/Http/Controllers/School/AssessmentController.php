<?php

namespace App\Http\Controllers\School;

use App\Models\Map;
use App\Models\Form;
use App\Models\FormItem;
use App\Models\Assessment;
use App\Models\PlpFinalGradeFormRule;
use Illuminate\Http\Request;
// use Illuminate\Auth\Access\Gate;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\GuardsAssessmentDuplicates;
use App\Services\MapFinalGradeCalculator;

class AssessmentController extends Controller
{
    use GuardsAssessmentDuplicates;

    public function __construct(private MapFinalGradeCalculator $gradeCalculator)
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
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $maps = $this->_myMap($activeYear, $plp_order);
        $assessor = $user->hasRole('dosen') ? 'dosen' : 'guru';

        $forms = PlpFinalGradeFormRule::query()
            ->where('year', $activeYear)
            ->where('assessor', $assessor)
            ->where('plp_order', (int) $plp_order)
            ->orderBy('form_id')
            ->pluck('form_id')
            ->all();

        return view('aktivitas.assessment-resume', compact('maps', 'forms', 'assessor'));
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
        $request->merge([
            'map_id' => (int) $map_id,
            'plp_order' => (int) $plp_order,
            'form_id' => (string) $form_id,
            'form_order' => (int) $form_order,
        ]);
        $request->merge(['assessor' => $this->normalizedAssessor($request)]);
        if ($response = $this->assertAssessmentSlotAvailable($request)) {
            return $response;
        }

        $form = Form::find($form_id);
        $grade = 0;
        for ($i=0; $i < $form->count; $i++) {
            $score = 'score'.($i+1);
            $grade += $request->$score;
        }
        $final_grade = ($form->type == 'skor_4') ? round(100 * $grade/(4*$form->count),2) : $grade;

        $data = $request->merge([
            'grade' => $final_grade,
        ]);
        Assessment::create($data->all());

        $this->refreshMapGrades((int) $map_id, (int) $plp_order);

        return response()->json([
            'success' => true,
            'message' => 'assessment <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    // Menu Setiap Form
    public function show($plp_order, $form_id)
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $maps = $this->_myMap($activeYear, $plp_order);

        return view('aktivitas.assessment', compact('plp_order', 'maps', 'form_id'));
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
        $request->merge([
            'map_id' => (int) $map_id,
            'plp_order' => (int) $plp_order,
            'form_id' => (string) $form_id,
            'form_order' => (int) $form_order,
        ]);
        $request->merge(['assessor' => $this->normalizedAssessor($request)]);
        if ($response = $this->assertAssessmentSlotAvailable($request, $schoolassessment)) {
            return $response;
        }

        $data = $request->all();

        $form = Form::find($form_id);
        $grade = 0;
        for ($i=0; $i < $form->count; $i++) {
            $score = 'score'.($i+1);
            $grade += $request->$score;
        }

        $final_grade = ($form->type == 'skor_4') ? round(100 * $grade/(4*$form->count),2) : $grade;
        $schoolassessment->grade = $final_grade;

        $schoolassessment->fill($data)->save();

        $this->refreshMapGrades((int) $map_id, (int) $plp_order);

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

    private function refreshMapGrades(int $mapId, int $plpOrder): void
    {
        $this->gradeCalculator->recalculateMapForPlp($mapId, $plpOrder);
        $this->gradeCalculator->recalculateCombinedDisplay($mapId);
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
}
