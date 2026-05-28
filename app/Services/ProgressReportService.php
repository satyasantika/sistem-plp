<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Form;
use App\Models\Map;
use App\Models\PlpFinalGradeFormRule;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProgressReportService
{
    private const LEGACY_DPL_FORMS = [
        1 => ['2022N2', '2022N8'],
        2 => ['2022N2', '2022N6', '2022N7'],
    ];

    private const LEGACY_GP_FORMS = ['2022N1', '2022N3', '2022N4', '2022N5', '2022N6', '2022N7'];

    public function bucketIsActive(int $year, int $bucket): bool
    {
        if (! in_array($bucket, [0, 1, 2], true)) {
            return false;
        }

        if (! PlpFinalGradeFormRule::query()->where('year', $year)->where('plp_order', $bucket)->exists()) {
            return false;
        }

        $query = Map::query()->where('year', $year)->whereNotNull('student_id');
        $this->applyMapParticipationFilter($query, $bucket);

        return $query->exists();
    }

    public function getLegacyProgressData(int $year, int $plpOrder, User $user): array
    {
        $cacheKey = "progress:legacy:{$year}:{$plpOrder}:{$user->id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $plpOrder, $user) {
            $dosenForms = self::LEGACY_DPL_FORMS[$plpOrder] ?? [];
            $formTimes = $this->getFormTimesFromDb($dosenForms);

            return [
                'department' => $this->buildDepartmentData(
                    $year,
                    $user,
                    $plpOrder,
                    $dosenForms,
                    $formTimes,
                    true,
                    'Progress Penilaian DPL'
                ),
                'school' => $plpOrder === 2
                    ? $this->buildSchoolData(
                        $year,
                        $user,
                        2,
                        self::LEGACY_GP_FORMS,
                        $this->getFormTimesFromDb(self::LEGACY_GP_FORMS),
                        true,
                        'Progress Penilaian GP'
                    )
                    : null,
            ];
        });
    }

    public function getBucketProgressData(int $year, int $bucket, User $user): array
    {
        $cacheKey = "progress:bucket:{$year}:{$bucket}:{$user->id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $bucket, $user) {
            $forms = $this->getFormListsForBucket($year, $bucket);
            $departmentLabel = $user->hasRole('kajur') ? optional($user->subjects)->departement : null;

            $department = empty($forms['dosenForms'])
                ? null
                : $this->buildDepartmentData(
                    $year,
                    $user,
                    $bucket,
                    $forms['dosenForms'],
                    $forms['formTimesDosen'],
                    false,
                    'Progress Penilaian DPL',
                    $departmentLabel
                );

            $school = empty($forms['guruForms'])
                ? null
                : $this->buildSchoolData(
                    $year,
                    $user,
                    $bucket,
                    $forms['guruForms'],
                    $forms['formTimesGuru'],
                    false,
                    'Progress Penilaian GP'
                );

            return [
                'department' => $department,
                'school' => $school,
            ];
        });
    }

    /**
     * @deprecated Gunakan getBucketProgressData(…, 0, …) untuk tahun modern.
     */
    public function getOnlyProgressData(int $year, User $user): array
    {
        return $this->getBucketProgressData($year, 0, $user);
    }

    /**
     * @return array{dosenForms: array, guruForms: array, formTimesDosen: array<string, int>, formTimesGuru: array<string, int>}
     */
    private function getFormListsForBucket(int $year, int $bucket): array
    {
        $rules = PlpFinalGradeFormRule::query()
            ->where('year', $year)
            ->where('plp_order', $bucket)
            ->orderBy('form_id')
            ->get(['form_id', 'assessor', 'times']);

        $dosenForms = [];
        $guruForms = [];
        $formTimesDosen = [];
        $formTimesGuru = [];

        foreach ($rules as $rule) {
            $formId = (string) $rule->form_id;
            $times = max(1, (int) $rule->times);

            if ($rule->assessor === 'dosen') {
                $dosenForms[] = $formId;
                $formTimesDosen[$formId] = $times;
            } elseif ($rule->assessor === 'guru') {
                $guruForms[] = $formId;
                $formTimesGuru[$formId] = $times;
            }
        }

        return [
            'dosenForms' => array_values(array_unique($dosenForms)),
            'guruForms' => array_values(array_unique($guruForms)),
            'formTimesDosen' => $formTimesDosen,
            'formTimesGuru' => $formTimesGuru,
        ];
    }

    private function buildDepartmentData(
        int $year,
        User $user,
        int $bucket,
        array $forms,
        array $formTimes,
        bool $legacyPlpFlagFilter,
        string $title,
        ?string $departmentLabel = null
    ): array {
        $subjects = $user->hasRole('kajur') && $user->subject_id
            ? Subject::query()->where('id', $user->subject_id)->get(['id', 'name', 'departement'])
            : Subject::query()->where('id', '!=', '03')->orderBy('name')->get(['id', 'name', 'departement']);

        $mapsQuery = Map::query()
            ->where('year', $year)
            ->whereNotNull('student_id')
            ->whereIn('subject_id', $subjects->pluck('id'));

        if ($legacyPlpFlagFilter) {
            $mapsQuery->where('plp'.$bucket, 1);
        } else {
            $this->applyMapParticipationFilter($mapsQuery, $bucket);
        }

        $maps = $mapsQuery
            ->with(['lectures:id,name,phone'])
            ->get(['id', 'subject_id', 'lecture_id']);

        $resolvedFormTimes = $this->resolveFormTimes($forms, $formTimes);
        $assessmentCounts = $this->getAssessmentCounts($maps->pluck('id')->all(), $forms, 'dosen', $bucket);

        $cards = [];
        foreach ($subjects as $subject) {
            $subjectMaps = $maps->where('subject_id', $subject->id)->values();
            $participantCount = $subjectMaps->count();
            $totalSlotsPerMap = $this->totalFormSlots($resolvedFormTimes, $forms);
            $completedWeight = $this->calculateCompletedWeight($subjectMaps, $forms, $resolvedFormTimes, $assessmentCounts);
            $percent = $participantCount === 0 || $totalSlotsPerMap === 0
                ? 0
                : round(($completedWeight / ($totalSlotsPerMap * $participantCount)) * 100, 2);

            $lectureRows = [];
            foreach ($subjectMaps->groupBy('lecture_id') as $lectureId => $lectureMaps) {
                if (! $lectureId) {
                    continue;
                }

                $lecture = optional($lectureMaps->first()->lectures);
                $statuses = $this->buildStatusRows($lectureMaps, $forms, $resolvedFormTimes, $assessmentCounts);
                $lectureRows[] = [
                    'name' => $lecture->name ?? '',
                    'phone' => $lecture->phone ?? null,
                    'statuses' => $statuses,
                    'is_complete' => $this->statusesAreComplete($statuses),
                ];
            }

            [$pendingRows, $completedRows] = $this->splitRowsByCompletion($lectureRows);

            $cards[] = [
                'id' => $subject->id,
                'name' => $subject->name,
                'percent' => $percent,
                'rows' => $pendingRows,
                'completed_rows' => $completedRows,
            ];
        }

        return [
            'title' => $title,
            'departmentLabel' => $departmentLabel,
            'cards' => $cards,
        ];
    }

    private function buildSchoolData(
        int $year,
        User $user,
        int $bucket,
        array $forms,
        array $formTimes,
        bool $legacyPlpFlagFilter,
        string $title
    ): array {
        $schools = $user->hasAnyRole(['kepsek', 'korguru'])
            ? School::query()
                ->where('headmaster_id', $user->id)
                ->orWhere('coordinator_id', $user->id)
                ->orderBy('name')
                ->get(['id', 'name'])
            : School::query()->orderBy('name')->get(['id', 'name']);

        $mapsQuery = Map::query()
            ->where('year', $year)
            ->whereNotNull('student_id')
            ->whereIn('school_id', $schools->pluck('id'));

        if ($legacyPlpFlagFilter) {
            $mapsQuery->where('plp'.$bucket, 1);
        } else {
            $this->applyMapParticipationFilter($mapsQuery, $bucket);
        }

        $maps = $mapsQuery
            ->with(['teachers:id,name,phone'])
            ->get(['id', 'school_id', 'teacher_id']);

        $resolvedFormTimes = $this->resolveFormTimes($forms, $formTimes);
        $assessmentCounts = $this->getAssessmentCounts($maps->pluck('id')->all(), $forms, 'guru', $bucket);

        $cards = [];
        foreach ($schools as $school) {
            $schoolMaps = $maps->where('school_id', $school->id)->values();
            $participantCount = $schoolMaps->count();
            $totalSlotsPerMap = $this->totalFormSlots($resolvedFormTimes, $forms);
            $completedWeight = $this->calculateCompletedWeight($schoolMaps, $forms, $resolvedFormTimes, $assessmentCounts);
            $percent = $participantCount === 0 || $totalSlotsPerMap === 0
                ? 0
                : round(($completedWeight / ($totalSlotsPerMap * $participantCount)) * 100, 2);

            $teacherRows = [];
            foreach ($schoolMaps->groupBy('teacher_id') as $teacherId => $teacherMaps) {
                if (! $teacherId) {
                    continue;
                }

                $teacher = optional($teacherMaps->first()->teachers);
                $statuses = $this->buildStatusRows($teacherMaps, $forms, $resolvedFormTimes, $assessmentCounts);
                $teacherRows[] = [
                    'name' => $teacher->name ?? '',
                    'phone' => $teacher->phone ?? null,
                    'statuses' => $statuses,
                    'is_complete' => $this->statusesAreComplete($statuses),
                ];
            }

            [$pendingRows, $completedRows] = $this->splitRowsByCompletion($teacherRows);

            $cards[] = [
                'id' => $school->id,
                'name' => $school->name,
                'percent' => $percent,
                'rows' => $pendingRows,
                'completed_rows' => $completedRows,
                'expanded' => $user->hasAnyRole(['kepsek', 'korguru']),
            ];
        }

        return [
            'title' => $title,
            'cards' => $cards,
        ];
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
     * @param  array<string, int>  $formTimes
     * @return array<string, int>
     */
    private function resolveFormTimes(array $forms, array $formTimes): array
    {
        if ($formTimes !== []) {
            $resolved = [];
            foreach ($forms as $formId) {
                $resolved[$formId] = max(1, (int) ($formTimes[$formId] ?? 1));
            }

            return $resolved;
        }

        return $this->getFormTimesFromDb($forms);
    }

    /**
     * @param  array<int, string>  $forms
     * @return array<string, int>
     */
    private function getFormTimesFromDb(array $forms): array
    {
        if ($forms === []) {
            return [];
        }

        return Form::query()
            ->whereIn('id', $forms)
            ->pluck('times', 'id')
            ->map(fn ($times) => max(1, (int) $times))
            ->all();
    }

    private function getAssessmentCounts(array $mapIds, array $forms, string $assessor, int $plpOrder): array
    {
        if ($mapIds === [] || $forms === []) {
            return [];
        }

        $counts = [];
        foreach (Assessment::query()
            ->whereIn('map_id', $mapIds)
            ->where('assessor', $assessor)
            ->whereIn('form_id', $forms)
            ->where('plp_order', $plpOrder)
            ->selectRaw('map_id, form_id, form_order, COUNT(*) as assessment_count')
            ->groupBy('map_id', 'form_id', 'form_order')
            ->get() as $assessment) {
            $counts[$assessment->map_id][$assessment->form_id][(int) $assessment->form_order] = (int) $assessment->assessment_count;
        }

        return $counts;
    }

    /**
     * @param  array<string, int>  $formTimes
     */
    private function totalFormSlots(array $formTimes, array $forms): int
    {
        $total = 0;
        foreach ($forms as $formId) {
            $total += max(1, (int) ($formTimes[$formId] ?? 1));
        }

        return $total;
    }

    /**
     * @param  array<string, int>  $formTimes
     */
    private function calculateCompletedWeight(Collection $maps, array $forms, array $formTimes, array $assessmentCounts): float
    {
        $completedWeight = 0.0;

        foreach ($maps as $map) {
            foreach ($forms as $formId) {
                $times = max(1, (int) ($formTimes[$formId] ?? 1));
                for ($order = 1; $order <= $times; $order++) {
                    $count = $assessmentCounts[$map->id][$formId][$order] ?? 0;
                    if ($count > 0) {
                        $completedWeight += 1 / $count;
                    }
                }
            }
        }

        return $completedWeight;
    }

    /**
     * @param  array<string, int>  $formTimes
     */
    private function buildStatusRows(Collection $maps, array $forms, array $formTimes, array $assessmentCounts): array
    {
        $quotaCount = max($maps->count(), 1);
        $statuses = [];

        foreach ($forms as $formId) {
            $times = max(1, (int) ($formTimes[$formId] ?? 1));
            for ($order = 1; $order <= $times; $order++) {
                $score = 0.0;
                foreach ($maps as $map) {
                    $count = $assessmentCounts[$map->id][$formId][$order] ?? 0;
                    if ($count > 0) {
                        $score += 1 / ($count * $quotaCount);
                    }
                }

                $statuses[] = [
                    'label' => $times === 1 ? substr($formId, -2) : substr($formId, -2).'.'.$order,
                    'status' => $score >= 0.9999 ? 'success' : ($score > 0 ? 'warning' : 'danger'),
                    'icon' => $score >= 0.9999 ? 'ti-check' : ($score > 0 ? 'ti-reload' : 'ti-close'),
                ];
            }
        }

        return $statuses;
    }

    private function statusesAreComplete(array $statuses): bool
    {
        if ($statuses === []) {
            return false;
        }

        foreach ($statuses as $status) {
            if (($status['status'] ?? null) !== 'success') {
                return false;
            }
        }

        return true;
    }

    private function splitRowsByCompletion(array $rows): array
    {
        $pendingRows = [];
        $completedRows = [];

        foreach ($rows as $row) {
            if (! empty($row['is_complete'])) {
                $completedRows[] = $row;

                continue;
            }

            $pendingRows[] = $row;
        }

        return [$pendingRows, $completedRows];
    }
}
