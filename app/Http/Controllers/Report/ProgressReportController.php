<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Services\ProgressReportService;
use Illuminate\Http\Request;

class ProgressReportController extends Controller
{
    public function __construct(private ProgressReportService $service)
    {
    }

    public function show(int $plp_order)
    {
        if (! in_array($plp_order, [0, 1, 2], true)) {
            abort(404);
        }

        return redirect()->to(url('data/progress/plp').'?tab=plp'.$plp_order);
    }

    public function showOnly(Request $request)
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
        $requestedTab = (string) $request->query('tab', '');

        $plpTabs = [];

        if ($useLegacyTabs) {
            if ($hasPlp1 && $this->userCanViewBucket($user, 1)) {
                $progressData = $this->service->getLegacyProgressData($activeYear, 1, $user);
                $plpTabs[] = $this->buildLegacyTab(1, $progressData);
            }

            if ($hasPlp2 && $this->userCanViewBucket($user, 2)) {
                $progressData = $this->service->getLegacyProgressData($activeYear, 2, $user);
                $plpTabs[] = $this->buildLegacyTab(2, $progressData);
            }
        } else {
            foreach ([0, 1, 2] as $bucket) {
                if (! $this->service->bucketIsActive($activeYear, $bucket)) {
                    continue;
                }

                if (! $this->userCanViewBucket($user, $bucket)) {
                    continue;
                }

                $progressData = $this->service->getBucketProgressData($activeYear, $bucket, $user);
                $plpTabs[] = $this->buildModernTab($bucket, $progressData);
            }
        }

        $activeTab = count($plpTabs) > 0 ? $plpTabs[0]['key'] : null;
        foreach ($plpTabs as $tab) {
            if ($tab['key'] === $requestedTab) {
                $activeTab = $requestedTab;
                break;
            }
        }

        return view('report.only.assessment-result', [
            'activeYear' => $activeYear,
            'useLegacyTabs' => $useLegacyTabs,
            'plpTabs' => $plpTabs,
            'activeTab' => $activeTab,
        ]);
    }

    /**
     * @param  array{department: ?array, school: ?array}  $progressData
     */
    private function buildLegacyTab(int $plpOrder, array $progressData): array
    {
        return [
            'key' => 'plp'.$plpOrder,
            'label' => 'PLP '.$plpOrder,
            'plp_order' => $plpOrder,
            'departmentData' => $progressData['department'],
            'schoolData' => $progressData['school'],
            'hasSchool' => $plpOrder === 2 && $progressData['school'] !== null,
        ];
    }

    /**
     * @param  array{department: ?array, school: ?array}  $progressData
     */
    private function buildModernTab(int $bucket, array $progressData): array
    {
        return [
            'key' => 'plp'.$bucket,
            'label' => Map::plpBucketLabel($bucket),
            'plp_order' => $bucket,
            'departmentData' => $progressData['department'],
            'schoolData' => $progressData['school'],
            'hasSchool' => $progressData['school'] !== null,
        ];
    }

    private function userCanViewBucket($user, int $bucket): bool
    {
        return match ($bucket) {
            0 => $user->can('data/progress/plp-read')
                || $user->can('data/progress/plp1-read')
                || $user->can('data/progress/plp2-read'),
            1 => $user->can('data/progress/plp1-read'),
            2 => $user->can('data/progress/plp2-read'),
            default => false,
        };
    }
}
