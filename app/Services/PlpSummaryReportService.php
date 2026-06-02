<?php

namespace App\Services;

use App\Models\Map;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PlpSummaryReportService
{
    private const EXCLUDED_SUBJECT_ID = '03';

    public function build(int $year, ?User $user = null): array
    {
        $scope = $this->resolveScope($user);
        $baseQuery = $this->scopedMapQuery($year, $scope);

        $subjectRecap = $this->buildSubjectRecap($scope, $baseQuery);
        $dplCards = $this->buildDplCards($year, $scope, $baseQuery);
        $gpCards = $this->buildGpCards($scope, $baseQuery);

        $totals = [
            'students' => (int) (clone $baseQuery)
                ->where('plp', 1)
                ->whereNotNull('student_id')
                ->count(),
            'dpl' => (int) (clone $baseQuery)
                ->whereNotNull('lecture_id')
                ->distinct('lecture_id')
                ->count('lecture_id'),
            'gp' => (int) (clone $baseQuery)
                ->whereNotNull('teacher_id')
                ->distinct('teacher_id')
                ->count('teacher_id'),
        ];

        return compact('subjectRecap', 'dplCards', 'gpCards', 'totals', 'scope');
    }

    public function buildExportRows(int $year, ?User $user = null): array
    {
        $scope = $this->resolveScope($user);
        $baseQuery = $this->scopedMapQuery($year, $scope);

        $dplRows = (clone $baseQuery)
            ->where('plp', 1)
            ->whereNotNull('student_id')
            ->whereNotNull('lecture_id')
            ->where('subject_id', '!=', self::EXCLUDED_SUBJECT_ID)
            ->select(
                'lecture_id',
                'subject_id',
                DB::raw('COUNT(*) as student_count')
            )
            ->groupBy('lecture_id', 'subject_id')
            ->with(['lectures', 'subjects'])
            ->get()
            ->sortBy(fn ($row) => [
                $row->subjects->departement ?? '',
                $row->lectures->name ?? '',
            ])
            ->values()
            ->map(function ($row, int $index): array {
                return [
                    'no' => $index + 1,
                    'nama' => $row->lectures->name ?? '-',
                    'jurusan' => $row->subjects->departement ?? '-',
                    'mahasiswa' => (int) $row->student_count,
                ];
            })
            ->all();

        $gpRows = (clone $baseQuery)
            ->where('plp', 1)
            ->whereNotNull('student_id')
            ->whereNotNull('teacher_id')
            ->select(
                'teacher_id',
                'subject_id',
                'school_id',
                DB::raw('COUNT(*) as student_count')
            )
            ->groupBy('teacher_id', 'subject_id', 'school_id')
            ->with(['teachers', 'subjects', 'schools'])
            ->get()
            ->sortBy(fn ($row) => [
                $row->schools->name ?? '',
                $row->teachers->name ?? '',
                $row->subjects->name ?? '',
            ])
            ->values()
            ->map(function ($row, int $index): array {
                return [
                    'no' => $index + 1,
                    'nama' => $row->teachers->name ?? '-',
                    'mapel' => $row->subjects->name ?? '-',
                    'sekolah' => $row->schools->name ?? '-',
                    'mahasiswa' => (int) $row->student_count,
                ];
            })
            ->all();

        return [
            'dpl' => $dplRows,
            'gp' => $gpRows,
        ];
    }

    private function resolveScope(?User $user): array
    {
        if (! $user) {
            return $this->allScope();
        }

        if ($user->hasAnyRole(['data', 'admin'])) {
            return $this->allScope();
        }

        if ($user->hasRole('kajur')) {
            return [
                'type' => 'jurusan',
                'label' => optional($user->subjects)->departement,
                'subject_ids' => $user->subject_id ? collect([$user->subject_id]) : collect(),
                'school_ids' => collect(),
                'show_dpl_section' => true,
                'show_gp_section' => true,
            ];
        }

        if ($user->hasAnyRole(['kepsek', 'korguru'])) {
            $schoolIds = $this->resolveSchoolIds($user);

            return [
                'type' => 'sekolah',
                'label' => $this->resolveSchoolLabel($user, $schoolIds),
                'subject_ids' => collect(),
                'school_ids' => $schoolIds,
                'show_dpl_section' => true,
                'show_gp_section' => true,
            ];
        }

        return $this->allScope();
    }

    private function allScope(): array
    {
        return [
            'type' => 'all',
            'label' => null,
            'subject_ids' => collect(),
            'school_ids' => collect(),
            'show_dpl_section' => true,
            'show_gp_section' => true,
        ];
    }

    private function resolveSchoolIds(User $user): Collection
    {
        return $user->headmasters()
            ->pluck('id')
            ->merge($user->coordinators()->pluck('id'))
            ->unique()
            ->values();
    }

    private function resolveSchoolLabel(User $user, Collection $schoolIds): ?string
    {
        if ($schoolIds->isEmpty()) {
            return null;
        }

        $names = $user->headmasters()
            ->whereIn('id', $schoolIds)
            ->pluck('name')
            ->merge(
                $user->coordinators()
                    ->whereIn('id', $schoolIds)
                    ->pluck('name')
            )
            ->unique()
            ->values();

        if ($names->count() === 1) {
            return $names->first();
        }

        return $names->count().' sekolah';
    }

    private function scopedMapQuery(int $year, array $scope): Builder
    {
        $query = Map::query()->forYear($year);

        if ($scope['type'] === 'jurusan') {
            if ($scope['subject_ids']->isEmpty()) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereIn('subject_id', $scope['subject_ids']);
        }

        if ($scope['type'] === 'sekolah') {
            if ($scope['school_ids']->isEmpty()) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereIn('school_id', $scope['school_ids']);
        }

        return $query;
    }

    private function scopedSubjects(int $year, array $scope): Collection
    {
        $query = Subject::query()
            ->where('id', '!=', self::EXCLUDED_SUBJECT_ID)
            ->orderBy('name');

        if ($scope['type'] === 'jurusan' && $scope['subject_ids']->isNotEmpty()) {
            $query->whereIn('id', $scope['subject_ids']);
        }

        if ($scope['type'] === 'sekolah' && $scope['school_ids']->isNotEmpty()) {
            $subjectIds = Map::query()
                ->forYear($year)
                ->whereIn('school_id', $scope['school_ids'])
                ->whereNotNull('subject_id')
                ->distinct()
                ->pluck('subject_id');

            $query->whereIn('id', $subjectIds);
        }

        return $query->get();
    }

    private function buildSubjectRecap(array $scope, Builder $baseQuery): array
    {
        $rows = (clone $baseQuery)
            ->where('subject_id', '!=', self::EXCLUDED_SUBJECT_ID)
            ->select(
                'subject_id',
                DB::raw('SUM(CASE WHEN plp = 1 AND student_id IS NOT NULL THEN 1 ELSE 0 END) as student_count'),
                DB::raw('COUNT(DISTINCT lecture_id) as dpl_count'),
                DB::raw('COUNT(DISTINCT teacher_id) as gp_count')
            )
            ->groupBy('subject_id')
            ->with('subjects')
            ->get()
            ->sortBy(fn ($row) => $row->subjects->name ?? $row->subject_id)
            ->values();

        return $rows->map(function ($row) {
            return [
                'subject_id' => $row->subject_id,
                'name' => $row->subjects->name ?? '-',
                'department' => $row->subjects->departement ?? '-',
                'student_count' => (int) $row->student_count,
                'dpl_count' => (int) $row->dpl_count,
                'gp_count' => (int) $row->gp_count,
            ];
        })->all();
    }

    private function buildDplCards(int $year, array $scope, Builder $baseQuery): array
    {
        if (! $scope['show_dpl_section']) {
            return [];
        }

        $subjects = $this->scopedSubjects($year, $scope);

        $studentCounts = (clone $baseQuery)
            ->where('plp', 1)
            ->whereNotNull('student_id')
            ->whereNotNull('lecture_id')
            ->where('subject_id', '!=', self::EXCLUDED_SUBJECT_ID)
            ->select(
                'subject_id',
                'lecture_id',
                DB::raw('COUNT(*) as student_count')
            )
            ->groupBy('subject_id', 'lecture_id')
            ->with('lectures')
            ->get()
            ->groupBy('subject_id');

        $schoolBreakdowns = (clone $baseQuery)
            ->where('plp', 1)
            ->whereNotNull('student_id')
            ->whereNotNull('lecture_id')
            ->where('subject_id', '!=', self::EXCLUDED_SUBJECT_ID)
            ->select(
                'subject_id',
                'lecture_id',
                'school_id',
                DB::raw('COUNT(*) as student_count')
            )
            ->groupBy('subject_id', 'lecture_id', 'school_id')
            ->with('schools')
            ->get()
            ->groupBy(fn ($row) => $row->subject_id.'|'.$row->lecture_id);

        return $subjects->map(function (Subject $subject) use ($studentCounts, $schoolBreakdowns) {
            $rows = ($studentCounts->get($subject->id) ?? collect())
                ->map(function ($row) use ($schoolBreakdowns, $subject) {
                    $key = $subject->id.'|'.$row->lecture_id;
                    $schools = ($schoolBreakdowns->get($key) ?? collect())
                        ->map(fn ($schoolRow) => [
                            'name' => $schoolRow->schools->name ?? '-',
                            'student_count' => (int) $schoolRow->student_count,
                        ])
                        ->sortByDesc('student_count')
                        ->values()
                        ->all();

                    return [
                        'id' => (int) $row->lecture_id,
                        'name' => $row->lectures->name ?? '-',
                        'student_count' => (int) $row->student_count,
                        'schools' => $schools,
                    ];
                })
                ->sortByDesc('student_count')
                ->values()
                ->all();

            return [
                'subject_id' => $subject->id,
                'name' => $subject->name,
                'department' => $subject->departement,
                'student_total' => array_sum(array_column($rows, 'student_count')),
                'dpl_total' => count($rows),
                'rows' => $rows,
            ];
        })
            ->filter(fn (array $card) => $card['dpl_total'] > 0 || $card['student_total'] > 0)
            ->values()
            ->all();
    }

    private function buildGpCards(array $scope, Builder $baseQuery): array
    {
        if (! $scope['show_gp_section']) {
            return [];
        }

        $studentCounts = (clone $baseQuery)
            ->where('plp', 1)
            ->whereNotNull('student_id')
            ->whereNotNull('teacher_id')
            ->select(
                'school_id',
                'teacher_id',
                DB::raw('COUNT(*) as student_count')
            )
            ->groupBy('school_id', 'teacher_id')
            ->with(['schools', 'teachers'])
            ->get()
            ->groupBy('school_id');

        $subjectBreakdowns = (clone $baseQuery)
            ->where('plp', 1)
            ->whereNotNull('student_id')
            ->whereNotNull('teacher_id')
            ->select(
                'school_id',
                'teacher_id',
                'subject_id',
                DB::raw('COUNT(*) as student_count')
            )
            ->groupBy('school_id', 'teacher_id', 'subject_id')
            ->with(['subjects'])
            ->get()
            ->groupBy(fn ($row) => $row->school_id.'|'.$row->teacher_id);

        return $studentCounts
            ->map(function (Collection $rows, $schoolId) use ($subjectBreakdowns) {
                $schoolName = $rows->first()?->schools->name ?? '-';

                $gpRows = $rows
                    ->map(function ($row) use ($subjectBreakdowns, $schoolId) {
                        $key = $schoolId.'|'.$row->teacher_id;
                        $subjects = ($subjectBreakdowns->get($key) ?? collect())
                            ->map(fn ($subjectRow) => [
                                'name' => $subjectRow->subjects->name ?? '-',
                                'student_count' => (int) $subjectRow->student_count,
                            ])
                            ->sortByDesc('student_count')
                            ->values()
                            ->all();

                        return [
                            'id' => (int) $row->teacher_id,
                            'name' => $row->teachers->name ?? '-',
                            'student_count' => (int) $row->student_count,
                            'subjects' => $subjects,
                        ];
                    })
                    ->sortByDesc('student_count')
                    ->values()
                    ->all();

                return [
                    'school_id' => (int) $schoolId,
                    'name' => $schoolName,
                    'student_total' => array_sum(array_column($gpRows, 'student_count')),
                    'gp_total' => count($gpRows),
                    'rows' => $gpRows,
                ];
            })
            ->sortByDesc('student_total')
            ->values()
            ->all();
    }
}
