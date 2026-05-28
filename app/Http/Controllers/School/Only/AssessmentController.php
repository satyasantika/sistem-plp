<?php

namespace App\Http\Controllers\School\Only;

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
        $this->middleware('permission:aktivitas/schoolassessments/plp-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp-update', ['only' => ['edit', 'update', 'recalculateGrades']]);
        $this->middleware('permission:aktivitas/schoolassessments/plp-delete', ['only' => ['destroy']]);
    }

    public function recalculateGrades(Request $request)
    {
        $user = auth()->user();
        $activeYear = (int) Map::activeYear($user);
        $maps = $this->_myMap($activeYear);

        $processed = 0;
        $errors = 0;

        foreach ($maps as $map) {
            try {
                $this->gradeCalculator->recalculateMapFully($map);
                $processed++;
            } catch (\Throwable) {
                $errors++;
            }
        }

        $roleLabel = $this->_assessorForUser($user) === 'guru' ? 'guru pamong' : 'dosen pembimbing';

        return response()->json([
            'success' => $errors === 0,
            'processed' => $processed,
            'errors' => $errors,
            'message' => $processed > 0
                ? "Nilai akhir {$processed} mahasiswa ({$roleLabel}, tahun {$activeYear}) telah dihitung ulang."
                    .($errors > 0 ? " {$errors} map gagal diproses." : '')
                : 'Tidak ada mahasiswa bimbingan untuk dihitung ulang pada tahun aktif ini.',
        ]);
    }

    // Rekap Penilaian
    public function index(Request $request)
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $allMaps = $this->_myMap($activeYear);
        $assessor = $this->_assessorForUser($user);
        $allSections = $this->_buildSections($allMaps, $assessor, $activeYear);

        $plpTabs = [];
        foreach ($allSections as $order => $section) {
            $plpTabs[] = [
                'order' => $order,
                'label' => Map::plpBucketLabel($order),
                'url' => route('schoolassessments.only.index', ['plp' => $order]),
                'formCount' => count($section['forms']),
            ];
        }

        $focusPlp = null;
        if ($request->has('plp') && in_array($request->integer('plp'), [0, 1, 2], true)) {
            $focusPlp = $request->integer('plp');
        }

        $sections = $allSections;
        if ($focusPlp !== null) {
            $sections = array_key_exists($focusPlp, $allSections)
                ? [$focusPlp => $allSections[$focusPlp]]
                : [];
        }

        return view('aktivitas.only.assessment-resume', [
            'sections'      => $sections,
            'allSections'   => $allSections,
            'plpTabs'         => $plpTabs,
            'totalMaps'       => $allMaps->count(),
            'user'            => $user,
            'activeYear'      => $activeYear,
            'focusPlp'        => $focusPlp,
            'focusPlpLabel'   => $focusPlp !== null ? Map::plpBucketLabel($focusPlp) : null,
        ]);
    }

    private function _buildSections(\Illuminate\Support\Collection $allMaps, string $assessor, int $activeYear): array
    {
        $sections = [];
        foreach ([0, 1, 2] as $plpOrder) {
            $maps = $allMaps->filter(fn ($m) => $m->participatesInPlpOrder($plpOrder))->values();
            if ($maps->isEmpty()) {
                continue;
            }
            $rules = PlpFinalGradeFormRule::query()
                ->where('year', $activeYear)
                ->where('assessor', $assessor)
                ->where('plp_order', $plpOrder)
                ->orderBy('form_id')
                ->get(['form_id', 'times']);
            if ($rules->isEmpty()) {
                continue;
            }
            $formIds = $rules->pluck('form_id')->all();
            $formRuleTimes = $rules->pluck('times', 'form_id')
                ->map(fn ($t) => max(1, (int) ($t ?? 1)))
                ->all();
            $formModels = Form::whereIn('id', $formIds)->get()->keyBy('id');
            $sections[$plpOrder] = [
                'plpOrder'      => $plpOrder,
                'maps'          => $maps,
                'forms'         => $formIds,
                'formModels'    => $formModels,
                'formRuleTimes' => $formRuleTimes,
                'assessor'      => $assessor,
            ];
        }

        ksort($sections);

        return $sections;
    }

    public function create($form_id, $form_order, $map_id, Request $request)
    {
        $schoolassessment = new Assessment();

        return view('aktivitas.only.assessment-action', array_merge(
            ['schoolassessment' => $schoolassessment],
            $this->_dataSelection($form_id, $form_order, $map_id, $this->resolveBucketForAssessment($request, $form_id, (int) $map_id))
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

        $plpOrder = $this->resolveStoragePlpOrder($request, $form_id, (int) $map_id);
        $request->merge([
            'map_id' => (int) $map_id,
            'form_id' => (string) $form_id,
            'form_order' => (int) $form_order,
            'plp_order' => $plpOrder,
            'grade' => $final_grade,
            'assessor' => $this->normalizedAssessor($request),
        ]);
        if ($response = $this->assertAssessmentSlotAvailable($request)) {
            return $response;
        }
        if ($response = $this->assertFormOrderWithinRule($request)) {
            return $response;
        }

        Assessment::create($request->all());

        $this->refreshMapGrades((int) $map_id, $plpOrder);

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
        $assessor = $this->_assessorForUser($user);
        $allowedForms = $this->_allowedFormsForUser($user, $activeYear, $assessor);
        $maps = in_array($form_id, $allowedForms, true)
            ? $this->_myMap($activeYear)
            : collect();

        $focusMapId = $request->integer('map_id');
        $isFocusedAssessment = false;
        $focusPlp = $this->bucketPlpFromRequest($request);
        $focusMap = $focusMapId ? $maps->firstWhere('id', $focusMapId) : null;

        if ($focusMapId) {
            $maps = $maps->where('id', $focusMapId)->values();
            $isFocusedAssessment = $maps->count() === 1;
            $focusMap = $maps->first();
        }

        if ($focusPlp === null && $focusMap instanceof Map) {
            $focusPlp = $this->_inferBucketForMapAndForm($focusMap, $form_id, $assessor, $activeYear);
        }

        if ($focusPlp === null) {
            $focusPlp = 0;
        }

        $form_times = $this->_formRuleTimes($form_id, $assessor, $activeYear, $focusPlp) ?? 1;
        $focusPlpLabel = Map::plpBucketLabel($focusPlp);

        return view('aktivitas.only.assessment', compact(
            'maps',
            'form_id',
            'user',
            'activeYear',
            'focusMapId',
            'isFocusedAssessment',
            'focusPlp',
            'focusPlpLabel',
            'form_times',
            'assessor'
        ));
    }

    private function _allowedFormsForUser($user, int $activeYear = 0, ?string $assessor = null): array
    {
        if ($activeYear === 0) {
            $activeYear = (int) Map::activeYear($user);
        }
        $assessor ??= $this->_assessorForUser($user);

        return PlpFinalGradeFormRule::query()
            ->where('year', $activeYear)
            ->where('assessor', $assessor)
            ->pluck('form_id')
            ->unique()
            ->values()
            ->all();
    }

    private function _assessorForUser($user): string
    {
        if ($user->hasRole('guru') && ! $user->hasRole('dosen')) {
            return 'guru';
        }

        return 'dosen';
    }

    private function _formRuleTimes(string $formId, string $assessor, int $year, int $bucketPlp): ?int
    {
        $rule = PlpFinalGradeFormRule::query()
            ->where('year', $year)
            ->where('assessor', $assessor)
            ->where('form_id', $formId)
            ->where('plp_order', $bucketPlp)
            ->first(['times']);

        return $rule ? max(1, (int) $rule->times) : null;
    }

    /**
     * Bucket PLP (0/1/2) dari URL ?plp= atau tebak dari map + form di sebaran.
     */
    private function _inferBucketForMapAndForm(Map $map, string $formId, string $assessor, int $year): int
    {
        foreach ([0, 1, 2] as $bucket) {
            if (! $map->participatesInPlpOrder($bucket)) {
                continue;
            }
            if ($this->_formRuleTimes($formId, $assessor, $year, $bucket) !== null) {
                return $bucket;
            }
        }

        return 0;
    }

    private function _resolveBucketFromRequest(Request $request, string $formId, string $assessor, int $year): ?int
    {
        $bucket = $this->bucketPlpFromRequest($request);
        if ($bucket !== null) {
            return $bucket;
        }

        if ($request->has('plp_bucket') && in_array($request->integer('plp_bucket'), [0, 1, 2], true)) {
            return $request->integer('plp_bucket');
        }

        $map = Map::find($request->input('map_id'));
        if ($map instanceof Map) {
            return $this->_inferBucketForMapAndForm($map, $formId, $assessor, $year);
        }

        return null;
    }

    protected function assertFormOrderWithinRule(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $activeYear = (int) Map::activeYear();
        $formId = (string) $request->input('form_id');
        $assessor = $this->normalizedAssessor($request);
        $formOrder = (int) $request->input('form_order');
        $bucket = $this->_resolveBucketFromRequest($request, $formId, $assessor, $activeYear);

        if ($bucket === null) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi PLP tidak dikenali. Buka penilaian dari rekap dengan parameter ?plp=.',
            ], 422);
        }

        $times = $this->_formRuleTimes($formId, $assessor, $activeYear, $bucket);
        if ($times === null) {
            return response()->json([
                'success' => false,
                'message' => 'Form ini tidak terdaftar di Sebaran Form untuk kombinasi PLP dan peran penilai.',
            ], 422);
        }

        if ($formOrder < 1 || $formOrder > $times) {
            return response()->json([
                'success' => false,
                'message' => 'Urutan pengisian harus antara 1 dan '.$times.' (sesuai Sebaran Form).',
            ], 422);
        }

        return null;
    }

    public function edit($form_id, $form_order, $map_id, Assessment $schoolassessment, Request $request)
    {
        $bucket = $this->resolveBucketForAssessment($request, $form_id, (int) $map_id, $schoolassessment);

        return view('aktivitas.only.assessment-action', array_merge(
            ['schoolassessment' => $schoolassessment],
            $this->_dataSelection($form_id, $form_order, $map_id, $bucket)
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
        $schoolassessment->plp_order = $this->resolveStoragePlpOrder($request, $form_id, (int) $map_id, $schoolassessment);

        $request->merge([
            'map_id' => (int) $map_id,
            'form_id' => (string) $form_id,
            'form_order' => (int) $form_order,
            'plp_order' => $schoolassessment->plp_order,
            'assessor' => $this->normalizedAssessor($request),
        ]);
        if ($response = $this->assertAssessmentSlotAvailable($request, $schoolassessment)) {
            return $response;
        }
        if ($response = $this->assertFormOrderWithinRule($request)) {
            return $response;
        }

        $schoolassessment->fill($data)->save();

        $this->refreshMapGrades((int) $map_id, (int) $schoolassessment->plp_order);

        return response()->json([
            'status' => 'success',
            'message' => 'assessment <strong>'.$request->id.'</strong> telah diperbarui'
        ]);
    }

    private function bucketPlpFromRequest(Request $request): ?int
    {
        if (! $request->has('plp')) {
            return null;
        }

        $plp = $request->integer('plp');

        return in_array($plp, [0, 1, 2], true) ? $plp : null;
    }

    /**
     * plp_order yang disimpan di assessment = bucket PLP dari tampilan (0/1/2).
     */
    private function storagePlpOrderForMap(Map $map, int $bucketPlpOrder): int
    {
        return $map->assessmentPlpOrderForBucket($bucketPlpOrder);
    }

    private function resolveBucketForAssessment(
        Request $request,
        string $formId,
        int $mapId,
        ?Assessment $existing = null
    ): int {
        $bucket = $this->_resolveBucketFromRequest(
            $request,
            $formId,
            $this->normalizedAssessor($request),
            (int) Map::activeYear()
        );

        if ($bucket !== null) {
            return $bucket;
        }

        if ($existing !== null && in_array((int) $existing->plp_order, [0, 1, 2], true)) {
            return (int) $existing->plp_order;
        }

        $map = Map::find($mapId);
        if ($map instanceof Map) {
            return $this->_inferBucketForMapAndForm(
                $map,
                $formId,
                $this->_assessorForUser(auth()->user()),
                (int) Map::activeYear()
            );
        }

        return 0;
    }

    private function resolveStoragePlpOrder(
        Request $request,
        string $formId,
        int $mapId,
        ?Assessment $existing = null
    ): int {
        $map = Map::find($mapId);
        if (! $map instanceof Map) {
            return 0;
        }

        $bucket = $this->resolveBucketForAssessment($request, $formId, $mapId, $existing);

        return $this->storagePlpOrderForMap($map, $bucket);
    }

    private function _dataSelection($form_id, $form_order, $map_id, ?int $bucketPlpOrder = null)
    {
        $map = Map::with(['students', 'schools', 'subjects', 'lectures', 'teachers'])->find($map_id);
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $assessorRole = $this->_assessorForUser($user);
        $bucket = $bucketPlpOrder ?? 0;
        $plpOrder = $map
            ? $this->storagePlpOrderForMap($map, $bucket)
            : $bucket;
        $ruleTimes = $this->_formRuleTimes($form_id, $assessorRole, $activeYear, $bucket) ?? 1;

        return [
            'form' => Form::find($form_id),
            'map' => $map,
            'form_guides' => $this->_formByComponent($form_id, 'petunjuk'),
            'form_items' => $this->_formByComponent($form_id, 'item'),
            'form_extras' => $this->_formByComponent($form_id, 'tambahan'),
            'kebaikan' => ['sangat kurang', 'kurang', 'baik', 'sangat baik'],
            'keterpenuhan' => ['tidak terpenuhi semua aspek', 'hanya 1 aspek ada', '2 aspek ada', '3 aspek ada'],
            'activeYear' => $activeYear,
            'assessmentLockedByYear' => (int) $activeYear !== (int) config('plp.default_year'),
            'bucketPlpOrder' => $bucketPlpOrder,
            'bucketPlpLabel' => $bucketPlpOrder !== null ? Map::plpBucketLabel($bucketPlpOrder) : null,
            'ruleTimes' => $ruleTimes,
            'assessorRole' => $assessorRole,
            'parameters' => [
                'form_id' => $form_id,
                'form_order' => $form_order,
                'map_id' => $map_id,
                'plp_order' => $plpOrder,
            ],
        ];
    }

    private function _resolvePlpOrder($map_id)
    {
        $map = Map::select('id', 'plp', 'plp1', 'plp2')->find($map_id);

        if (! $map) {
            return 2;
        }

        return $map->resolvedAssessmentPlpOrder();
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

    private function refreshMapGrades(int $mapId, int $plpOrder): void
    {
        $this->gradeCalculator->recalculateMapForPlp($mapId, $plpOrder);
        $this->gradeCalculator->recalculateCombinedDisplay($mapId);
    }
}
