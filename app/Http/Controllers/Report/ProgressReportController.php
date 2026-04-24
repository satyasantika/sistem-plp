<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Services\ProgressReportService;

class ProgressReportController extends Controller
{
  public function __construct(private ProgressReportService $service)
  {
  }

  public function show(int $plp_order)
  {
    return redirect()->to(url('data/progress/plp') . '?tab=plp' . $plp_order);
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
      if ($hasPlp1) {
        $progressData = $this->service->getLegacyProgressData($activeYear, 1, $user);
        $legacyTabs[] = [
          'key' => 'plp1',
          'label' => 'PLP 1',
          'plp_order' => 1,
          'departmentData' => $progressData['department'],
          'schoolData' => $progressData['school'],
        ];
      }

      if ($hasPlp2) {
        $progressData = $this->service->getLegacyProgressData($activeYear, 2, $user);
        $legacyTabs[] = [
          'key' => 'plp2',
          'label' => 'PLP 2',
          'plp_order' => 2,
          'departmentData' => $progressData['department'],
          'schoolData' => $progressData['school'],
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

    $progressData = $useLegacyTabs
      ? ['department' => null, 'school' => null]
      : $this->service->getOnlyProgressData($activeYear, $user);

    return view('report.only.assessment-result', [
      'activeYear' => $activeYear,
      'useLegacyTabs' => $useLegacyTabs,
      'legacyTabs' => $legacyTabs,
      'activeLegacyTab' => $activeLegacyTab,
      'departmentData' => $progressData['department'],
      'schoolData' => $progressData['school'],
    ]);
  }
}
