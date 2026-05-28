<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Map;
use App\Models\PlpFinalGradeFormRule;
use App\Models\Subject;
use App\Support\Grading;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class YudisiumReportService
{
    private const LETTERS = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'E'];

    /** @var array<int, array{letter: string, min: float, label: string}> */
    private const GRADE_SCALE = [
        ['letter' => 'A', 'min' => 85, 'label' => '≥ 85'],
        ['letter' => 'A-', 'min' => 77, 'label' => '77 – 84,99'],
        ['letter' => 'B+', 'min' => 69, 'label' => '69 – 76,99'],
        ['letter' => 'B', 'min' => 61, 'label' => '61 – 68,99'],
        ['letter' => 'B-', 'min' => 53, 'label' => '53 – 60,99'],
        ['letter' => 'C+', 'min' => 45, 'label' => '45 – 52,99'],
        ['letter' => 'C', 'min' => 37, 'label' => '37 – 44,99'],
        ['letter' => 'C-', 'min' => 29, 'label' => '29 – 36,99'],
        ['letter' => 'D', 'min' => 21, 'label' => '21 – 28,99'],
        ['letter' => 'E', 'min' => 0, 'label' => '< 21'],
    ];

    /** @var array<int, array<int, bool>> */
    private array $bucketActiveByYear = [];

    public function bucketIsActive(int $year, int $bucket): bool
    {
        if (! in_array($bucket, [0, 1, 2], true)) {
            return false;
        }

        if (! isset($this->bucketActiveByYear[$year])) {
            $this->bucketActiveByYear[$year] = $this->resolveActiveBucketsForYear($year);
        }

        return $this->bucketActiveByYear[$year][$bucket] ?? false;
    }

    /**
     * @return array<int, bool>
     */
    private function resolveActiveBucketsForYear(int $year): array
    {
        return Cache::remember("yudisium:active-buckets:{$year}", now()->addMinutes(10), function () use ($year) {
            $rulesBuckets = PlpFinalGradeFormRule::query()
                ->where('year', $year)
                ->whereIn('plp_order', [0, 1, 2])
                ->distinct()
                ->pluck('plp_order')
                ->map(fn ($order) => (int) $order)
                ->all();

            $active = [0 => false, 1 => false, 2 => false];

            foreach ($rulesBuckets as $bucket) {
                if (! in_array($bucket, [0, 1, 2], true)) {
                    continue;
                }

                $query = Map::query()->where('year', $year)->whereNotNull('student_id');
                $this->applyMapParticipationFilter($query, $bucket);
                $active[$bucket] = $query->exists();
            }

            return $active;
        });
    }

    public function getDekanatSummary(int $year, int $plpOrder): array
    {
        $cacheKey = "yudisium:dekanat:{$year}:{$plpOrder}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $plpOrder) {
            [$gradeColumn, $letterColumn] = $this->gradeLetterColumns($plpOrder);

            return $this->buildDekanatSummary($year, $plpOrder, $gradeColumn, $letterColumn);
        });
    }

    public function getBucketDekanatSummary(int $year, int $bucket): array
    {
        $cacheKey = "yudisium:bucket:dekanat:{$year}:{$bucket}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $bucket) {
            [$gradeColumn, $letterColumn] = $this->gradeLetterColumns($bucket);

            return $this->buildDekanatSummary($year, $bucket, $gradeColumn, $letterColumn);
        });
    }

    /**
     * @deprecated Gunakan getBucketDekanatSummary(…, 0) untuk tahun modern.
     */
    public function getOnlyDekanatSummary(int $year): array
    {
        return $this->getBucketDekanatSummary($year, 0);
    }

    public function getJurusanRows(int $year, ?string $subjectId, int $plpOrder): array
    {
        if (! $subjectId) {
            return $this->emptyJurusanPayload();
        }

        $cacheKey = "yudisium:jurusan:v5:{$year}:{$subjectId}:{$plpOrder}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $subjectId, $plpOrder) {
            $forms = $this->getFormListsForBucket($year, $plpOrder);

            if ($forms['lectureForms'] === [] && $forms['teacherForms'] === []) {
                $lectureForms = $plpOrder === 1
                    ? ['2022N2', '2022N8']
                    : ['2022N2', '2022N6', '2022N7'];
                $teacherForms = ['2022N1', '2022N3', '2022N4', '2022N5', '2022N6', '2022N7'];
                $formTimes = $this->legacyFormTimes(array_merge($lectureForms, $teacherForms));
            } else {
                $lectureForms = $forms['lectureForms'];
                $teacherForms = $forms['teacherForms'];
                $formTimes = $forms['formTimesByAssessor'];
            }

            $rows = $this->buildJurusanRows(
                $year,
                $subjectId,
                $plpOrder,
                $lectureForms,
                $teacherForms,
                $formTimes,
                true
            );

            return $this->jurusanPayload($rows, $lectureForms, $teacherForms);
        });
    }

    public function getBucketJurusanRows(int $year, ?string $subjectId, int $bucket): array
    {
        if (! $subjectId) {
            return $this->emptyJurusanPayload();
        }

        $cacheKey = "yudisium:bucket:jurusan:v5:{$year}:{$subjectId}:{$bucket}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $subjectId, $bucket) {
            $forms = $this->getFormListsForBucket($year, $bucket);

            $rows = $this->buildJurusanRows(
                $year,
                $subjectId,
                $bucket,
                $forms['lectureForms'],
                $forms['teacherForms'],
                $forms['formTimesByAssessor'],
                false
            );

            return $this->jurusanPayload($rows, $forms['lectureForms'], $forms['teacherForms']);
        });
    }

    /**
     * @deprecated Gunakan getBucketJurusanRows(…, 0) untuk tahun modern.
     */
    public function getOnlyJurusanRows(int $year, ?string $subjectId): array
    {
        return $this->getBucketJurusanRows($year, $subjectId, 0);
    }

    /**
     * @return array{lectureForms: array, teacherForms: array, formTimesByAssessor: array<string, array<string, int>>}
     */
    private function getFormListsForBucket(int $year, int $bucket): array
    {
        $rules = PlpFinalGradeFormRule::query()
            ->where('year', $year)
            ->where('plp_order', $bucket)
            ->orderBy('form_id')
            ->get(['form_id', 'assessor', 'times']);

        $lectureForms = [];
        $teacherForms = [];
        $formTimesByAssessor = ['dosen' => [], 'guru' => []];

        foreach ($rules as $rule) {
            $formId = (string) $rule->form_id;
            $assessor = (string) $rule->assessor;
            $times = max(1, (int) $rule->times);

            if ($assessor === 'dosen') {
                $lectureForms[] = $formId;
                $formTimesByAssessor['dosen'][$formId] = $times;
            } elseif ($assessor === 'guru') {
                $teacherForms[] = $formId;
                $formTimesByAssessor['guru'][$formId] = $times;
            }
        }

        return [
            'lectureForms' => array_values(array_unique($lectureForms)),
            'teacherForms' => array_values(array_unique($teacherForms)),
            'formTimesByAssessor' => $formTimesByAssessor,
        ];
    }

    private function buildDekanatSummary(int $year, int $bucket, string $gradeColumn, string $letterColumn): array
    {
        $subjects = Subject::query()
            ->where('id', '!=', '03')
            ->orderBy('name')
            ->get(['id', 'name']);

        $query = Map::query()
            ->where('year', $year)
            ->whereNotNull('student_id');

        $this->applyMapParticipationFilter($query, $bucket);

        $selects = ['subject_id', 'COUNT(*) as participants'];
        foreach (self::LETTERS as $letter) {
            $alias = $this->letterAlias($letter);
            $selects[] = "SUM(CASE WHEN {$letterColumn} = '{$letter}' THEN 1 ELSE 0 END) as {$alias}";
        }
        $selects[] = "SUM(CASE WHEN {$gradeColumn} IS NULL OR {$gradeColumn} = 0 THEN 1 ELSE 0 END) as ungraded";

        $aggregates = $query
            ->selectRaw(implode(', ', $selects))
            ->groupBy('subject_id')
            ->get()
            ->keyBy('subject_id');

        $rows = [];
        $totals = [
            'participants' => 0,
            'ungraded' => 0,
            'letters' => array_fill_keys(self::LETTERS, 0),
        ];

        foreach ($subjects as $subject) {
            $aggregate = $aggregates->get($subject->id);
            $row = [
                'subject' => $subject->name,
                'participants' => (int) ($aggregate->participants ?? 0),
                'ungraded' => (int) ($aggregate->ungraded ?? 0),
                'letters' => [],
            ];

            foreach (self::LETTERS as $letter) {
                $alias = $this->letterAlias($letter);
                $count = (int) ($aggregate->{$alias} ?? 0);
                $row['letters'][$letter] = $count;
                $totals['letters'][$letter] += $count;
            }

            $totals['participants'] += $row['participants'];
            $totals['ungraded'] += $row['ungraded'];
            $rows[] = $row;
        }

        return [
            'letters' => self::LETTERS,
            'rows' => $rows,
            'totals' => $totals,
        ];
    }

    /**
     * @param  array<string, int>|array<string, array<string, int>>  $formTimes
     */
    private function buildJurusanRows(
        int $year,
        string $subjectId,
        int $bucket,
        array $lectureForms,
        array $teacherForms,
        array $formTimes,
        bool $legacyPlpFlagFilter
    ): array {
        $mapsQuery = Map::query()
            ->join('users as students_order', 'students_order.id', '=', 'maps.student_id')
            ->where('maps.year', $year)
            ->where('maps.subject_id', $subjectId)
            ->whereNotNull('maps.student_id')
            ->orderBy('students_order.name')
            ->select('maps.*')
            ->with([
                'students:id,name,username',
                'lectures:id,name',
                'teachers:id,name',
            ]);

        if ($legacyPlpFlagFilter) {
            $mapsQuery->where('maps.plp'.$bucket, 1);
        } else {
            $this->applyMapParticipationFilter($mapsQuery, $bucket, 'maps.');
        }

        $maps = $mapsQuery->get();
        $mapIds = $maps->pluck('id')->all();

        if (empty($mapIds)) {
            return [];
        }

        $forms = array_values(array_unique(array_merge($lectureForms, $teacherForms)));

        $assessmentQuery = Assessment::query()
            ->whereIn('map_id', $mapIds)
            ->whereIn('assessor', ['dosen', 'guru'])
            ->whereIn('form_id', $forms)
            ->where('plp_order', $bucket)
            ->selectRaw('map_id, assessor, form_id, SUM(grade) as grade_sum')
            ->groupBy('map_id', 'assessor', 'form_id');

        $assessmentGroups = $assessmentQuery->get();
        $assessmentByMap = [];

        foreach ($assessmentGroups as $assessment) {
            $assessmentByMap[$assessment->map_id][$assessment->assessor][$assessment->form_id] = (float) $assessment->grade_sum;
        }

        [$dosenSlotCounts, $guruSlotCounts] = $this->getAssessmentSlotCountsByAssessor(
            $mapIds,
            $lectureForms,
            $teacherForms,
            $bucket
        );
        $requiredSlots = $this->countRequiredAssessmentSlots(
            $lectureForms,
            $teacherForms,
            $formTimes
        );

        [$gradeColumn, $letterColumn] = $this->gradeLetterColumns($bucket);

        $rows = [];
        foreach ($maps as $map) {
            $grade = (float) ($map->{$gradeColumn} ?? 0);
            $letter = $map->{$letterColumn};

            $lectureByForm = [];
            foreach ($lectureForms as $formId) {
                $sum = (float) ($assessmentByMap[$map->id]['dosen'][$formId] ?? 0);
                $times = $this->resolveFormTimes($formTimes, 'dosen', $formId);
                $lectureByForm[$formId] = $sum > 0 ? round($sum / max($times, 1), 2) : 0;
            }

            $teacherByForm = [];
            foreach ($teacherForms as $formId) {
                $sum = (float) ($assessmentByMap[$map->id]['guru'][$formId] ?? 0);
                $times = $this->resolveFormTimes($formTimes, 'guru', $formId);
                $teacherByForm[$formId] = $sum > 0 ? round($sum / max($times, 1), 2) : 0;
            }

            $filledSlots = $this->countFilledAssessmentSlotsForMap(
                $map->id,
                $lectureForms,
                $teacherForms,
                $formTimes,
                $dosenSlotCounts,
                $guruSlotCounts
            );
            $gradeMeta = $this->buildGradeDisplayMeta($grade, $letter, $filledSlots, $requiredSlots);

            $rows[] = [
                'student_nim' => $map->students->username ?? '',
                'student_name' => $map->students->name ?? '',
                'lecture_name' => $map->lectures->name ?? '',
                'teacher_name' => $map->teachers->name ?? 'belum diset',
                'grade' => $gradeMeta['grade'],
                'letter' => $gradeMeta['letter'],
                'status' => $gradeMeta['status'],
                'grade_display' => $gradeMeta['grade_display'],
                'letter_display' => $gradeMeta['letter_display'],
                'pass_label' => $gradeMeta['pass_label'],
                'display_state' => $gradeMeta['display_state'],
                'has_assessment' => $filledSlots > 0,
                'assessment_filled_slots' => $filledSlots,
                'assessment_required_slots' => $requiredSlots,
                'has_final_grade' => $gradeMeta['has_final_grade'],
                'filter_pass' => $this->filterPassKey($gradeMeta),
                'filter_letter' => $this->filterLetterKey($gradeMeta),
                'lecture_forms' => $lectureByForm,
                'teacher_forms' => $teacherByForm,
            ];
        }

        return $rows;
    }

    private function applyMapParticipationFilter(Builder $query, int $bucket, string $prefix = ''): void
    {
        $plp = $prefix.'plp';
        $plp1 = $prefix.'plp1';
        $plp2 = $prefix.'plp2';

        $query->where(function (Builder $builder) use ($bucket, $plp, $plp1, $plp2) {
            if ($bucket === 0) {
                $builder->where($plp, 1)->orWhere($plp1, 1)->orWhere($plp2, 1);
            } elseif ($bucket === 1) {
                $builder->where($plp1, 1)->orWhere($plp, 1);
            } else {
                $builder->where($plp2, 1)->orWhere($plp, 1);
            }
        });
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function gradeLetterColumns(int $bucket): array
    {
        return match ($bucket) {
            0 => ['grade', 'letter'],
            1 => ['grade1', 'letter1'],
            default => ['grade2', 'letter2'],
        };
    }

    /**
     * @param  array<string, int>|array<string, array<string, int>>  $formTimes
     */
    private function resolveFormTimes(array $formTimes, string $assessor, string $formId): int
    {
        if (isset($formTimes[$assessor][$formId])) {
            return max(1, (int) $formTimes[$assessor][$formId]);
        }

        if (isset($formTimes[$formId])) {
            return max(1, (int) $formTimes[$formId]);
        }

        return 1;
    }

    /**
     * @param  array<int, string>  $forms
     * @return array<string, int>
     */
    private function legacyFormTimes(array $forms): array
    {
        $times = [];
        foreach ($forms as $formId) {
            $times[$formId] = 1;
        }

        return $times;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<int, string>  $lectureForms
     * @param  array<int, string>  $teacherForms
     */
    private function jurusanPayload(array $rows, array $lectureForms, array $teacherForms): array
    {
        return [
            'rows' => $rows,
            'lectureForms' => $lectureForms,
            'teacherForms' => $teacherForms,
            'gradeRecap' => $this->buildJurusanGradeRecap($rows),
        ];
    }

    private function emptyJurusanPayload(): array
    {
        return $this->jurusanPayload([], [], []);
    }

    /**
     * Rekap distribusi nilai huruf & status penilaian mahasiswa jurusan.
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function buildJurusanGradeRecap(array $rows): array
    {
        $participants = count($rows);
        $letterCounts = array_fill_keys(self::LETTERS, 0);
        $graded = 0;
        $partial = 0;
        $ungraded = 0;
        $pass = 0;
        $fail = 0;

        foreach ($rows as $row) {
            $state = $this->resolveRowDisplayState($row);

            if ($state === 'complete') {
                $graded++;
                $letter = (string) ($row['letter_display'] ?? $row['letter'] ?? '');
                if ($letter !== '' && isset($letterCounts[$letter])) {
                    $letterCounts[$letter]++;
                }
                if ((float) ($row['grade'] ?? 0) >= 61) {
                    $pass++;
                } else {
                    $fail++;
                }
            } elseif ($state === 'partial') {
                $partial++;
            } else {
                $ungraded++;
            }
        }

        $percent = static fn (int $count): float => $participants > 0
            ? round($count / $participants * 100, 1)
            : 0.0;

        $letters = [];
        foreach (self::LETTERS as $letter) {
            $count = $letterCounts[$letter];
            $letters[] = [
                'letter' => $letter,
                'count' => $count,
                'percent' => $percent($count),
                'percent_of_graded' => $graded > 0 ? round($count / $graded * 100, 1) : 0.0,
                'is_high' => in_array($letter, ['A', 'A-', 'B+', 'B'], true),
            ];
        }

        return [
            'participants' => $participants,
            'graded' => $graded,
            'partial' => $partial,
            'ungraded' => $ungraded,
            'pass' => $pass,
            'fail' => $fail,
            'graded_percent' => $percent($graded),
            'partial_percent' => $percent($partial),
            'ungraded_percent' => $percent($ungraded),
            'pass_percent' => $graded > 0 ? round($pass / $graded * 100, 1) : 0.0,
            'fail_percent' => $graded > 0 ? round($fail / $graded * 100, 1) : 0.0,
            'letters' => $letters,
            'scale' => self::GRADE_SCALE,
        ];
    }

    /**
     * @param  array{display_state: string, grade: float}  $gradeMeta
     */
    private function filterPassKey(array $gradeMeta): string
    {
        return match ($gradeMeta['display_state']) {
            'complete' => $gradeMeta['grade'] >= 61 ? 'lulus' : 'tidak-lulus',
            'partial' => 'belum-lengkap',
            default => 'belum-dinilai',
        };
    }

    /**
     * @param  array{display_state: string, letter: ?string}  $gradeMeta
     */
    private function filterLetterKey(array $gradeMeta): string
    {
        if ($gradeMeta['display_state'] !== 'complete') {
            return '';
        }

        $letter = (string) ($gradeMeta['letter'] ?? '');

        return in_array($letter, self::LETTERS, true) ? $letter : '';
    }

    /**
     * @return array<int, string>
     */
    public static function letterGrades(): array
    {
        return self::LETTERS;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function resolveRowDisplayState(array $row): string
    {
        $state = $row['display_state'] ?? null;
        if ($state !== null) {
            return $state;
        }

        $grade = (float) ($row['grade'] ?? 0);
        $letter = trim((string) ($row['letter'] ?? ''));
        $hasFinalGrade = ! empty($row['has_final_grade']) || $grade > 0 || $letter !== '';

        return $this->resolveAssessmentDisplayState(
            (int) ($row['assessment_filled_slots'] ?? 0),
            (int) ($row['assessment_required_slots'] ?? 0),
            $hasFinalGrade
        );
    }

    /**
     * Status penilaian berdasarkan slot form tahun kegiatan vs baris assessment.
     */
    private function resolveAssessmentDisplayState(int $filledSlots, int $requiredSlots, bool $hasFinalGrade): string
    {
        if ($requiredSlots === 0) {
            return $hasFinalGrade ? 'complete' : 'empty';
        }

        if ($filledSlots === 0) {
            return 'empty';
        }

        if ($filledSlots < $requiredSlots) {
            return 'partial';
        }

        return $hasFinalGrade ? 'complete' : 'partial';
    }

    /**
     * @param  array<int, string>  $lectureForms
     * @param  array<int, string>  $teacherForms
     * @param  array<string, int>|array<string, array<string, int>>  $formTimes
     */
    private function countRequiredAssessmentSlots(
        array $lectureForms,
        array $teacherForms,
        array $formTimes
    ): int {
        $total = 0;

        foreach ($lectureForms as $formId) {
            $total += $this->resolveFormTimes($formTimes, 'dosen', $formId);
        }

        foreach ($teacherForms as $formId) {
            $total += $this->resolveFormTimes($formTimes, 'guru', $formId);
        }

        return $total;
    }

    /**
     * Satu query assessment untuk slot dosen + guru.
     *
     * @param  array<int, int>  $mapIds
     * @param  array<int, string>  $lectureForms
     * @param  array<int, string>  $teacherForms
     * @return array{0: array<int, array<string, array<int, int>>>, 1: array<int, array<string, array<int, int>>>}
     */
    private function getAssessmentSlotCountsByAssessor(
        array $mapIds,
        array $lectureForms,
        array $teacherForms,
        int $plpOrder
    ): array {
        $dosenCounts = [];
        $guruCounts = [];
        $allForms = array_values(array_unique(array_merge($lectureForms, $teacherForms)));

        if ($mapIds === [] || $allForms === []) {
            return [$dosenCounts, $guruCounts];
        }

        foreach (Assessment::query()
            ->whereIn('map_id', $mapIds)
            ->whereIn('assessor', ['dosen', 'guru'])
            ->whereIn('form_id', $allForms)
            ->where('plp_order', $plpOrder)
            ->selectRaw('map_id, assessor, form_id, form_order, COUNT(*) as assessment_count')
            ->groupBy('map_id', 'assessor', 'form_id', 'form_order')
            ->get() as $assessment) {
            $assessor = (string) $assessment->assessor;
            $formId = (string) $assessment->form_id;
            $order = (int) $assessment->form_order;
            $count = (int) $assessment->assessment_count;

            if ($assessor === 'dosen' && in_array($formId, $lectureForms, true)) {
                $dosenCounts[$assessment->map_id][$formId][$order] = $count;
            } elseif ($assessor === 'guru' && in_array($formId, $teacherForms, true)) {
                $guruCounts[$assessment->map_id][$formId][$order] = $count;
            }
        }

        return [$dosenCounts, $guruCounts];
    }

    /**
     * @param  array<int, string>  $lectureForms
     * @param  array<int, string>  $teacherForms
     * @param  array<string, int>|array<string, array<string, int>>  $formTimes
     * @param  array<int, array<string, array<int, int>>>  $dosenSlotCounts
     * @param  array<int, array<string, array<int, int>>>  $guruSlotCounts
     */
    private function countFilledAssessmentSlotsForMap(
        int $mapId,
        array $lectureForms,
        array $teacherForms,
        array $formTimes,
        array $dosenSlotCounts,
        array $guruSlotCounts
    ): int {
        $filled = 0;

        foreach ($lectureForms as $formId) {
            $times = $this->resolveFormTimes($formTimes, 'dosen', $formId);
            for ($order = 1; $order <= $times; $order++) {
                if (($dosenSlotCounts[$mapId][$formId][$order] ?? 0) > 0) {
                    $filled++;
                }
            }
        }

        foreach ($teacherForms as $formId) {
            $times = $this->resolveFormTimes($formTimes, 'guru', $formId);
            for ($order = 1; $order <= $times; $order++) {
                if (($guruSlotCounts[$mapId][$formId][$order] ?? 0) > 0) {
                    $filled++;
                }
            }
        }

        return $filled;
    }

    /**
     * @return array{
     *   grade: float,
     *   letter: ?string,
     *   status: string,
     *   grade_display: ?string,
     *   letter_display: ?string,
     *   pass_label: ?string,
     *   display_state: string,
     *   has_final_grade: bool
     * }
     */
    private function buildGradeDisplayMeta(float $grade, ?string $letter, int $filledSlots, int $requiredSlots): array
    {
        $hasStoredLetter = $letter !== null && trim((string) $letter) !== '';
        $normalizedGrade = round(min(100, max(0, $grade)), 2);
        $resolvedLetter = $hasStoredLetter ? trim((string) $letter) : null;

        if ($normalizedGrade > 0 && $resolvedLetter === null) {
            $resolvedLetter = Grading::numericToLetter($normalizedGrade);
        }

        $hasFinalGrade = $normalizedGrade > 0 || $hasStoredLetter;
        $displayState = $this->resolveAssessmentDisplayState($filledSlots, $requiredSlots, $hasFinalGrade);
        $isComplete = $displayState === 'complete';

        $status = match (true) {
            ! $isComplete => 'secondary',
            $normalizedGrade >= 85 => 'primary',
            $normalizedGrade >= 61 => 'success',
            default => 'danger',
        };

        $passLabel = $isComplete
            ? ($normalizedGrade >= 61 ? 'Lulus' : 'Tidak lulus')
            : null;

        $gradeForDisplay = $normalizedGrade > 0
            ? $normalizedGrade
            : ($hasStoredLetter ? $normalizedGrade : null);

        return [
            'grade' => $normalizedGrade,
            'letter' => $resolvedLetter,
            'status' => $status,
            'grade_display' => $gradeForDisplay !== null
                ? number_format($gradeForDisplay, 2, ',', '')
                : null,
            'letter_display' => $resolvedLetter,
            'pass_label' => $passLabel,
            'display_state' => $displayState,
            'has_final_grade' => $hasFinalGrade,
        ];
    }

    private function letterAlias(string $letter): string
    {
        return 'letter_'.str_replace(['+', '-'], ['plus', 'minus'], strtolower($letter));
    }

    /**
     * Kosongkan cache laporan yudisium setelah hitung ulang nilai map.
     */
    public function clearCaches(int $year, ?string $subjectId = null): void
    {
        Cache::forget("yudisium:active-buckets:{$year}");

        foreach ([0, 1, 2] as $bucket) {
            Cache::forget("yudisium:bucket:dekanat:{$year}:{$bucket}");
            Cache::forget("yudisium:only:dekanat:{$year}");

            if ($subjectId !== null && $subjectId !== '') {
                Cache::forget("yudisium:bucket:jurusan:{$year}:{$subjectId}:{$bucket}");
                Cache::forget("yudisium:bucket:jurusan:v2:{$year}:{$subjectId}:{$bucket}");
                Cache::forget("yudisium:bucket:jurusan:v3:{$year}:{$subjectId}:{$bucket}");
                Cache::forget("yudisium:bucket:jurusan:v4:{$year}:{$subjectId}:{$bucket}");
                Cache::forget("yudisium:bucket:jurusan:v5:{$year}:{$subjectId}:{$bucket}");
                Cache::forget("yudisium:only:jurusan:{$year}:{$subjectId}");
            }
        }

        foreach ([1, 2] as $plpOrder) {
            Cache::forget("yudisium:dekanat:{$year}:{$plpOrder}");

            if ($subjectId !== null && $subjectId !== '') {
                Cache::forget("yudisium:jurusan:{$year}:{$subjectId}:{$plpOrder}");
                Cache::forget("yudisium:jurusan:v2:{$year}:{$subjectId}:{$plpOrder}");
                Cache::forget("yudisium:jurusan:v3:{$year}:{$subjectId}:{$plpOrder}");
                Cache::forget("yudisium:jurusan:v4:{$year}:{$subjectId}:{$plpOrder}");
                Cache::forget("yudisium:jurusan:v5:{$year}:{$subjectId}:{$plpOrder}");
            }
        }
    }
}
