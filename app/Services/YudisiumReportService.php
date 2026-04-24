<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Form;
use App\Models\Map;
use App\Models\Subject;
use Illuminate\Support\Facades\Cache;

class YudisiumReportService
{
  private const LETTERS = ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'E'];

  public function getDekanatSummary(int $year, int $plpOrder): array
  {
    $plpFlag = 'plp' . $plpOrder;
    $gradeColumn = 'grade' . $plpOrder;
    $letterColumn = 'letter' . $plpOrder;

    $cacheKey = "yudisium:dekanat:{$year}:{$plpOrder}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $plpFlag, $gradeColumn, $letterColumn) {
      return $this->buildDekanatSummary($year, $plpFlag, $gradeColumn, $letterColumn);
    });
  }

  public function getOnlyDekanatSummary(int $year): array
  {
    $cacheKey = "yudisium:only:dekanat:{$year}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year) {
      return $this->buildDekanatSummary($year, 'plp', 'grade', 'letter');
    });
  }

  public function getJurusanRows(int $year, ?string $subjectId, int $plpOrder): array
  {
    if (!$subjectId) {
      return [
        'rows' => [],
        'lectureForms' => [],
        'teacherForms' => [],
      ];
    }

    $cacheKey = "yudisium:jurusan:{$year}:{$subjectId}:{$plpOrder}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $subjectId, $plpOrder) {
      $lectureForms = $plpOrder === 1
        ? ['2022N2', '2022N8']
        : ['2022N2', '2022N6', '2022N7'];

      $teacherForms = ['2022N1', '2022N3', '2022N4', '2022N5', '2022N6', '2022N7'];

      $rows = $this->buildJurusanRows($year, $subjectId, $plpOrder, $lectureForms, $teacherForms, true);

      return [
        'rows' => $rows,
        'lectureForms' => $lectureForms,
        'teacherForms' => $teacherForms,
      ];
    });
  }

  public function getOnlyJurusanRows(int $year, ?string $subjectId): array
  {
    if (!$subjectId) {
      return [
        'rows' => [],
        'lectureForms' => [],
        'teacherForms' => [],
      ];
    }

    $cacheKey = "yudisium:only:jurusan:{$year}:{$subjectId}";

    return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($year, $subjectId) {
      $lectureForms = ['2024N2', '2024N6', '2024N7'];
      $teacherForms = ['2024N1', '2024N3', '2024N4', '2024N5', '2024N6', '2024N7'];

      $rows = $this->buildJurusanRows($year, $subjectId, null, $lectureForms, $teacherForms, false);

      return [
        'rows' => $rows,
        'lectureForms' => $lectureForms,
        'teacherForms' => $teacherForms,
      ];
    });
  }

  private function buildDekanatSummary(int $year, string $plpFlag, string $gradeColumn, string $letterColumn): array
  {
    $subjects = Subject::query()
      ->where('id', '!=', '03')
      ->orderBy('name')
      ->get(['id', 'name']);

    $query = Map::query()
      ->where('year', $year)
      ->where($plpFlag, 1)
      ->whereNotNull('student_id');

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

  private function buildJurusanRows(
    int $year,
    string $subjectId,
    ?int $plpOrder,
    array $lectureForms,
    array $teacherForms,
    bool $applyPlpOrder
  ): array {
    $mapsQuery = Map::query()
      ->join('users as students_order', 'students_order.id', '=', 'maps.student_id')
      ->where('maps.year', $year)
      ->where('maps.subject_id', $subjectId)
      ->whereNotNull('maps.student_id')
      ->orderBy('students_order.name')
      ->select('maps.*')
      ->with([
        'students:id,name',
        'lectures:id,name',
        'teachers:id,name',
      ]);

    if ($applyPlpOrder && $plpOrder) {
      $mapsQuery->where('maps.plp' . $plpOrder, 1);
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
      ->selectRaw('map_id, assessor, form_id, SUM(grade) as grade_sum')
      ->groupBy('map_id', 'assessor', 'form_id');

    if ($applyPlpOrder && $plpOrder) {
      $assessmentQuery->where('plp_order', $plpOrder);
    }

    $assessmentGroups = $assessmentQuery->get();
    $assessmentByMap = [];

    foreach ($assessmentGroups as $assessment) {
      $assessmentByMap[$assessment->map_id][$assessment->assessor][$assessment->form_id] = (float) $assessment->grade_sum;
    }

    $formTimes = Form::query()->whereIn('id', $forms)->pluck('times', 'id')->all();

    $rows = [];
    foreach ($maps as $map) {
      if ($applyPlpOrder && $plpOrder) {
        $grade = $plpOrder === 1 ? $map->grade1 : $map->grade2;
        $letter = $plpOrder === 1 ? $map->letter1 : $map->letter2;
      } else {
        $grade = $map->grade;
        $letter = $map->letter;
      }

      $lectureByForm = [];
      foreach ($lectureForms as $formId) {
        $sum = (float) ($assessmentByMap[$map->id]['dosen'][$formId] ?? 0);
        $times = (int) ($formTimes[$formId] ?? 1);
        $lectureByForm[$formId] = $sum > 0 ? round($sum / max($times, 1), 2) : 0;
      }

      $teacherByForm = [];
      foreach ($teacherForms as $formId) {
        $sum = (float) ($assessmentByMap[$map->id]['guru'][$formId] ?? 0);
        $times = (int) ($formTimes[$formId] ?? 1);
        $teacherByForm[$formId] = $sum > 0 ? round($sum / max($times, 1), 2) : 0;
      }

      $status = 'danger';
      if ((float) $grade >= 85) {
        $status = 'primary';
      } elseif ((float) $grade >= 61) {
        $status = 'dark';
      }

      $rows[] = [
        'student_name' => $map->students->name ?? '',
        'lecture_name' => $map->lectures->name ?? '',
        'teacher_name' => $map->teachers->name ?? 'belum diset',
        'grade' => (float) ($grade ?? 0),
        'letter' => $letter,
        'status' => $status,
        'has_assessment' => isset($assessmentByMap[$map->id]),
        'lecture_forms' => $lectureByForm,
        'teacher_forms' => $teacherByForm,
      ];
    }

    return $rows;
  }

  private function letterAlias(string $letter): string
  {
    return 'letter_' . str_replace(['+', '-'], ['plus', 'minus'], strtolower($letter));
  }
}
