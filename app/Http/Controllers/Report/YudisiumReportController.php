<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Services\YudisiumReportService;

class YudisiumReportController extends Controller
{
  public function __construct(private YudisiumReportService $service)
  {
  }

  public function show(int $plp_order)
  {
    return redirect()->to(url('yudisium/plp') . '?tab=plp' . $plp_order);
  }

  public function showOnly()
  {
    $user = auth()->user();
    $activeYear = Map::activeYear($user);

    $baseMapQuery = Map::query()
      ->visibleToUser($user)
      ->where('year', $activeYear)
      ->whereNotNull('student_id');

    $hasPlp1 = (clone $baseMapQuery)->where('plp1', 1)->exists();
    $hasPlp2 = (clone $baseMapQuery)->where('plp2', 1)->exists();
    $useLegacyTabs = $activeYear <= 2023 && ($hasPlp1 || $hasPlp2);
    $requestedTab = (string) request('tab', '');

    $legacyTabs = [];
    if ($useLegacyTabs) {
      foreach ([1, 2] as $plpOrder) {
        $tabExists = $plpOrder === 1 ? $hasPlp1 : $hasPlp2;
        if (!$tabExists) {
          continue;
        }

        $dekanatSummary = null;
        $jurusanData = [
          'rows' => [],
          'lectureForms' => [],
          'teacherForms' => [],
        ];

        if ($user->hasAnyRole(['ketua', 'dekanat'])) {
          $dekanatSummary = $this->service->getDekanatSummary($activeYear, $plpOrder);
        }

        if ($user->hasRole('kajur')) {
          $jurusanData = $this->service->getJurusanRows($activeYear, $user->subject_id, $plpOrder);
        }

        $legacyTabs[] = [
          'key' => 'plp' . $plpOrder,
          'label' => 'PLP ' . $plpOrder,
          'plp_order' => $plpOrder,
          'dekanatSummary' => $dekanatSummary,
          'jurusanRows' => $jurusanData['rows'],
          'lectureForms' => $jurusanData['lectureForms'],
          'teacherForms' => $jurusanData['teacherForms'],
        ];
      }
    }

    $activeLegacyTab = count($legacyTabs) > 0 ? $legacyTabs[0]['key'] : null;
    foreach ($legacyTabs as $tab) {
      if ($tab['key'] === $requestedTab) {
        $activeLegacyTab = $requestedTab;
        break;
      }
    }

    $dekanatSummary = null;
    $jurusanData = [
      'rows' => [],
      'lectureForms' => [],
      'teacherForms' => [],
    ];

    if (!$useLegacyTabs) {
      if ($user->hasAnyRole(['ketua', 'dekanat'])) {
        $dekanatSummary = $this->service->getOnlyDekanatSummary($activeYear);
      }

      if ($user->hasRole('kajur')) {
        $jurusanData = $this->service->getOnlyJurusanRows($activeYear, $user->subject_id);
      }
    }

    return view('report.only.yudisium', [
      'activeYear' => $activeYear,
      'useLegacyTabs' => $useLegacyTabs,
      'legacyTabs' => $legacyTabs,
      'activeLegacyTab' => $activeLegacyTab,
      'dekanatSummary' => $dekanatSummary,
      'jurusanRows' => $jurusanData['rows'],
      'lectureForms' => $jurusanData['lectureForms'],
      'teacherForms' => $jurusanData['teacherForms'],
    ]);
  }
}
