<?php

namespace App\Http\Controllers\School\Only;

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
        $this->middleware('permission:aktivitas/schoolassessments/plp-read', ['only' => ['index','show']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp-create', ['only' => ['create','store']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp-update', ['only' => ['edit','update']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp-delete', ['only' => ['destroy']]);
    }

    // Rekap Penilaian
    public function index()
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $maps = $this->_myMap($activeYear);

        $forms = $this->_allowedFormsForUser($user);

        return view('aktivitas.only.assessment-resume', compact('maps', 'forms', 'user', 'activeYear'));
    }

    public function create($form_id, $form_order, $map_id)
    {
        $schoolassessment = new Assessment();
        return view('aktivitas.only.assessment-action', array_merge(
            ['schoolassessment'=> $schoolassessment],
            $this->_dataSelection($form_id, $form_order, $map_id)
            ));
    }

    public function store($form_id, $form_order, $map_id, Request $request)
    {
        $form = Form::find($form_id);
        $grade = 0;
        for ($i=0; $i < $form->count; $i++) {
            $score = 'score' . ($i + 1);
            $grade += $request->$score;
        }
        $final_grade = ($form->type == 'skor_4') ? round(100 * $grade/(4*$form->count),2) : $grade;

        $plpOrder = $request->input('plp_order', $this->_resolvePlpOrder($map_id));
        $data = $request->merge([
            'grade' => $final_grade,
            'plp_order' => $plpOrder,
        ]);
        Assessment::create($data->all());

        $this->_yudicium($map_id);

        return response()->json([
            'success' => true,
            'message' => 'assessment <strong>'.$request->id.'</strong> telah ditambahkan'
        ]);
    }

    // Menu Setiap Form
    public function show($form_id, Request $request)
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $allowedForms = $this->_allowedFormsForUser($user);
        $maps = in_array($form_id, $allowedForms, true)
            ? $this->_myMap($activeYear)
            : collect();

        $focusMapId = $request->integer('map_id');
        $isFocusedAssessment = false;

        if ($focusMapId) {
            $maps = $maps->where('id', $focusMapId)->values();
            $isFocusedAssessment = $maps->count() === 1;
        }

        return view('aktivitas.only.assessment', compact('maps', 'form_id', 'user', 'activeYear', 'focusMapId', 'isFocusedAssessment'));
    }

    private function _allowedFormsForUser($user)
    {
        if ($user->hasRole('dosen')) {
            return ['2024N2', '2024N6', '2024N7'];
        }

        return ['2024N1', '2024N3', '2024N4', '2024N5', '2024N6', '2024N7'];
    }

    public function edit($form_id, $form_order, $map_id, Assessment $schoolassessment)
    {
        return view('aktivitas.only.assessment-action', array_merge(
            ['schoolassessment'=>$schoolassessment],
            $this->_dataSelection($form_id, $form_order, $map_id)
            ));
    }

    public function update($form_id, $form_order, $map_id, Request $request, Assessment $schoolassessment)
    {
        $data = $request->all();

        $form = Form::find($form_id);
        $grade = 0;
        for ($i=0; $i < $form->count; $i++) {
            $score = 'score' . ($i + 1);
            $grade += $request->$score;
        }

        $final_grade = ($form->type == 'skor_4') ? round(100 * $grade/(4*$form->count),2) : $grade;
        $schoolassessment->grade = $final_grade;
        $schoolassessment->plp_order = $request->input('plp_order', $schoolassessment->plp_order ?? $this->_resolvePlpOrder($map_id));

        $schoolassessment->fill($data)->save();

        $this->_yudicium($map_id) ;

        return response()->json([
            'status' => 'success',
            'message' => 'assessment <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    private function _dataSelection($form_id, $form_order, $map_id)
    {
        $plpOrder = $this->_resolvePlpOrder($map_id);

        return [
            'form' => Form::find($form_id),
            'map' => Map::with(['students', 'schools', 'subjects', 'lectures', 'teachers'])->find($map_id),
            'form_guides' => $this->_formByComponent($form_id,'petunjuk'),
            'form_items' => $this->_formByComponent($form_id,'item'),
            'form_extras' => $this->_formByComponent($form_id,'tambahan'),
            'kebaikan' => ['sangat kurang','kurang','baik', 'sangat baik'],
            'keterpenuhan' => ['tidak terpenuhi semua aspek','hanya 1 aspek ada','2 aspek ada', '3 aspek ada'],
            'parameters' => [
                'form_id'=>$form_id,
                'form_order' => $form_order,
                'map_id' => $map_id,
                'plp_order' => $plpOrder,
                ]
        ];
    }

    private function _resolvePlpOrder($map_id)
    {
        $map = Map::select('id', 'plp1', 'plp2')->find($map_id);

        if (!$map) {
            return 2;
        }

        if ((int) $map->plp2 === 1) {
            return 2;
        }

        if ((int) $map->plp1 === 1) {
            return 1;
        }

        // Fallback default for current academic cycle.
        return 2;
    }

    private function _formByComponent($form_id, $component)
    {
        return FormItem::where('form_id',$form_id)->where('component',$component)->orderBy('component_order')->get();
    }

    private function _myMap($year)
    {
        $user = auth()->user();
        $isLecture = $user->hasRole('dosen') || $user->can('dashboard/dosen-read');
        $isTeacher = $user->hasRole('guru') || $user->can('dashboard/guru-read');

        $query = Map::forYear($year)
            ->whereNotNull('student_id')
            ->with(['students', 'schools', 'subjects', 'lectures', 'teachers']);

        if (!empty($user->subject_id)) {
            $query->where('subject_id', $user->subject_id);
        }

        if ($user->hasRole('dosen') && !$user->hasRole('guru')) {
            $query->where('lecture_id', $user->id);
        } elseif ($user->hasRole('guru') && !$user->hasRole('dosen')) {
            $query->where('teacher_id', $user->id);
        } elseif ($isLecture && !$isTeacher) {
            $query->where('lecture_id', $user->id);
        } elseif ($isTeacher && !$isLecture) {
            $query->where('teacher_id', $user->id);
        } elseif ($user->hasRole('dosen') && $user->hasRole('guru')) {
            // Prefer dosen scope for users with double role so list remains consistent with dashboard card entry.
            $query->where('lecture_id', $user->id);
        } elseif ($isLecture && $isTeacher) {
            $query->where(function ($builder) use ($user) {
                $builder->where('lecture_id', $user->id)
                    ->orWhere('teacher_id', $user->id);
            });
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query->orderBy('student_id')->get();
    }

    private function _yudicium($map_id)
    {
        $map = Map::find($map_id);
        $lecture_forms = ['2024N2','2024N6','2024N7'];
        $teacher_forms = ['2024N1','2024N3','2024N4','2024N5','2024N6','2024N7'];
        // penilaian dari dosen
        $assessment_by_lecture = Assessment::where([
                                            'assessor'=>'dosen',
                                            'map_id'=>$map_id,
                                        ])
                                        ->whereIn('form_id',$lecture_forms)
                                        ->sum('grade');
        $lecture_form_times = Form::whereIn('id',$lecture_forms)->sum('times');
        $lecture_total = round($assessment_by_lecture/$lecture_form_times,0);
        // penilaian dari guru
        $assessment_by_teacher = Assessment::where([
                                            'assessor'=>'guru',
                                            'map_id'=>$map_id,
                                        ])
                                        ->whereIn('form_id',$teacher_forms)
                                        ->sum('grade');
        $teacher_form_times = Form::whereIn('id',$teacher_forms)->sum('times');
        $teacher_total = $assessment_by_teacher/$teacher_form_times;

        $grade = 0.4 * $lecture_total + 0.6 * $teacher_total;

        $map->grade = round($grade,2);
        $map->letter = $this->_convertToLetter($grade);
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
