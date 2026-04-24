<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Form;
use App\Models\Map;
use App\Models\School;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProgressReportService
{
  private const LEGACY_DPL_FORMS = [
    1 => ['2022N2', '2022N8'],
    2 => ['2022N2', '2022N6', '2022N7'],
  ];

  private const LEGACY_GP_FORMS = ['2022N1', '2022N3', '2022N4', '2022N5', '2022N6', '2022N7'];
  private const ONLY_DPL_FORMS = ['2024N2', '2024N6', '2024N7'];
  private const ONLY_GP_FORMS = ['2022N1', '2022N3', '2022N4', '2022N5', '2022N6', '2022N7'];

  public function getLegacyProgressData(int $year, int $plpOrder, User $user): array
  {
    $cacheKey = "progress:legacy:{$year}:{$plpOrder}:{$user->id}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $plpOrder, $user) {
      return [
        'department' => $this->buildDepartmentData(
          year: $year,
          user: $user,
          plpFlag: 'plp' . $plpOrder,
          forms: self::LEGACY_DPL_FORMS[$plpOrder] ?? [],
          assessor: 'dosen',
          plpOrder: $plpOrder,
          title: 'Progress Penilaian DPL'
        ),
        'school' => $plpOrder === 2
          ? $this->buildSchoolData(
            year: $year,
            user: $user,
            plpFlag: 'plp2',
            forms: self::LEGACY_GP_FORMS,
            assessor: 'guru',
            plpOrder: 2,
            title: 'Progress Penilaian GP'
          )
          : null,
      ];
    });
  }

  public function getOnlyProgressData(int $year, User $user): array
  {
    $cacheKey = "progress:only:{$year}:{$user->id}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $user) {
      return [
        'department' => $this->buildDepartmentData(
          year: $year,
          user: $user,
          plpFlag: 'plp',
          forms: self::ONLY_DPL_FORMS,
          assessor: 'dosen',
          plpOrder: null,
          title: 'Progress Penilaian DPL',
          departmentLabel: $user->hasRole('kajur') ? optional($user->subjects)->departement : null
        ),
        'school' => $this->buildSchoolData(
          year: $year,
          user: $user,
          plpFlag: 'plp',
          forms: self::ONLY_GP_FORMS,
          assessor: 'guru',
          plpOrder: null,
          title: 'Progress Penilaian GP'
        ),
      ];
    });
  }

  private function buildDepartmentData(
    int $year,
    User $user,
    string $plpFlag,
    array $forms,
    string $assessor,
    ?int $plpOrder,
    string $title,
    ?string $departmentLabel = null
  ): array {
    $subjects = $user->hasRole('kajur') && $user->subject_id
      ? Subject::query()->where('id', $user->subject_id)->get(['id', 'name', 'departement'])
      : Subject::query()->where('id', '!=', '03')->orderBy('name')->get(['id', 'name', 'departement']);

    $maps = Map::query()
      ->where('year', $year)
      ->where($plpFlag, 1)
      ->whereNotNull('student_id')
      ->whereIn('subject_id', $subjects->pluck('id'))
      ->with(['lectures:id,name,phone'])
      ->get(['id', 'subject_id', 'lecture_id']);

    $formTimes = $this->getFormTimes($forms);
    $assessmentCounts = $this->getAssessmentCounts($maps->pluck('id')->all(), $forms, $assessor, $plpOrder);

    $cards = [];
    foreach ($subjects as $subject) {
      $subjectMaps = $maps->where('subject_id', $subject->id)->values();
      $participantCount = $subjectMaps->count();
      $totalSlotsPerMap = $this->totalFormSlots($formTimes);
      $completedWeight = $this->calculateCompletedWeight($subjectMaps, $forms, $formTimes, $assessmentCounts);
      $percent = $participantCount === 0 || $totalSlotsPerMap === 0
        ? 0
        : round(($completedWeight / ($totalSlotsPerMap * $participantCount)) * 100, 2);

      $lectureRows = [];
      foreach ($subjectMaps->groupBy('lecture_id') as $lectureId => $lectureMaps) {
        if (!$lectureId) {
          continue;
        }

        $lecture = optional($lectureMaps->first()->lectures);
        $statuses = $this->buildStatusRows($lectureMaps, $forms, $formTimes, $assessmentCounts);
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
    string $plpFlag,
    array $forms,
    string $assessor,
    ?int $plpOrder,
    string $title
  ): array {
    $schools = $user->hasAnyRole(['kepsek', 'korguru'])
      ? School::query()
        ->where('headmaster_id', $user->id)
        ->orWhere('coordinator_id', $user->id)
        ->orderBy('name')
        ->get(['id', 'name'])
      : School::query()->orderBy('name')->get(['id', 'name']);

    $maps = Map::query()
      ->where('year', $year)
      ->where($plpFlag, 1)
      ->whereNotNull('student_id')
      ->whereIn('school_id', $schools->pluck('id'))
      ->with(['teachers:id,name,phone'])
      ->get(['id', 'school_id', 'teacher_id']);

    $formTimes = $this->getFormTimes($forms);
    $assessmentCounts = $this->getAssessmentCounts($maps->pluck('id')->all(), $forms, $assessor, $plpOrder);

    $cards = [];
    foreach ($schools as $school) {
      $schoolMaps = $maps->where('school_id', $school->id)->values();
      $participantCount = $schoolMaps->count();
      $totalSlotsPerMap = $this->totalFormSlots($formTimes);
      $completedWeight = $this->calculateCompletedWeight($schoolMaps, $forms, $formTimes, $assessmentCounts);
      $percent = $participantCount === 0 || $totalSlotsPerMap === 0
        ? 0
        : round(($completedWeight / ($totalSlotsPerMap * $participantCount)) * 100, 2);

      $teacherRows = [];
      foreach ($schoolMaps->groupBy('teacher_id') as $teacherId => $teacherMaps) {
        if (!$teacherId) {
          continue;
        }

        $teacher = optional($teacherMaps->first()->teachers);
        $statuses = $this->buildStatusRows($teacherMaps, $forms, $formTimes, $assessmentCounts);
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

  private function getFormTimes(array $forms): array
  {
    if (empty($forms)) {
      return [];
    }

    return Form::query()->whereIn('id', $forms)->pluck('times', 'id')->map(fn($times) => (int) $times)->all();
  }

  private function getAssessmentCounts(array $mapIds, array $forms, string $assessor, ?int $plpOrder): array
  {
    if (empty($mapIds) || empty($forms)) {
      return [];
    }

    $query = Assessment::query()
      ->whereIn('map_id', $mapIds)
      ->where('assessor', $assessor)
      ->whereIn('form_id', $forms)
      ->selectRaw('map_id, form_id, form_order, COUNT(*) as assessment_count')
      ->groupBy('map_id', 'form_id', 'form_order');

    if ($plpOrder !== null) {
      $query->where('plp_order', $plpOrder);
    }

    $counts = [];
    foreach ($query->get() as $assessment) {
      $counts[$assessment->map_id][$assessment->form_id][(int) $assessment->form_order] = (int) $assessment->assessment_count;
    }

    return $counts;
  }

  private function totalFormSlots(array $formTimes): int
  {
    return array_sum($formTimes);
  }

  private function calculateCompletedWeight(Collection $maps, array $forms, array $formTimes, array $assessmentCounts): float
  {
    $completedWeight = 0.0;

    foreach ($maps as $map) {
      foreach ($forms as $formId) {
        $times = $formTimes[$formId] ?? 0;
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

  private function buildStatusRows(Collection $maps, array $forms, array $formTimes, array $assessmentCounts): array
  {
    $quotaCount = max($maps->count(), 1);
    $statuses = [];

    foreach ($forms as $formId) {
      $times = $formTimes[$formId] ?? 0;
      for ($order = 1; $order <= $times; $order++) {
        $score = 0.0;
        foreach ($maps as $map) {
          $count = $assessmentCounts[$map->id][$formId][$order] ?? 0;
          if ($count > 0) {
            $score += 1 / ($count * $quotaCount);
          }
        }

        $statuses[] = [
          'label' => $times === 1 ? substr($formId, -2) : substr($formId, -2) . '.' . $order,
          'status' => $score >= 0.9999 ? 'success' : ($score > 0 ? 'warning' : 'danger'),
          'icon' => $score >= 0.9999 ? 'ti-check' : ($score > 0 ? 'ti-reload' : 'ti-close'),
        ];
      }
    }

    return $statuses;
  }

  private function statusesAreComplete(array $statuses): bool
  {
    if (empty($statuses)) {
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
      if (!empty($row['is_complete'])) {
        $completedRows[] = $row;
        continue;
      }

      $pendingRows[] = $row;
    }

    return [$pendingRows, $completedRows];
  }
}
