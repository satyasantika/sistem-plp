<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExportYudisiumJurusan;
use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Services\MapFinalGradeCalculator;
use App\Services\YudisiumReportService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class YudisiumReportController extends Controller
{
    public function __construct(
        private YudisiumReportService $service,
        private MapFinalGradeCalculator $gradeCalculator,
    ) {
    }

    public function recalculateGrades(Request $request)
    {
        $user = auth()->user();

        if (! $user->hasRole('kajur')) {
            abort(403, 'Hanya kajur yang dapat menghitung ulang nilai.');
        }

        $subjectId = (string) ($user->subject_id ?? '');
        if ($subjectId === '') {
            return response()->json([
                'success' => false,
                'message' => 'Akun kajur belum memiliki jurusan (subject) yang terhubung.',
            ], 422);
        }

        $activeYear = (int) Map::activeYear($user);
        $maps = Map::query()
            ->where('year', $activeYear)
            ->where('subject_id', $subjectId)
            ->whereNotNull('student_id')
            ->get();

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

        $this->service->clearCaches($activeYear, $subjectId);

        $departement = $user->subjects->departement ?? 'jurusan Anda';
        $reloadUrl = url('yudisium/plp');
        $tab = (string) $request->query('tab', '');
        if ($tab !== '') {
            $reloadUrl .= '?tab='.urlencode($tab);
        }

        return response()->json([
            'success' => $errors === 0,
            'processed' => $processed,
            'errors' => $errors,
            'reload_url' => $reloadUrl,
            'message' => $processed > 0
                ? "Nilai akhir {$processed} mahasiswa ({$departement}, tahun {$activeYear}) telah dihitung ulang."
                    .($errors > 0 ? " {$errors} map gagal diproses." : '')
                : 'Tidak ada mahasiswa pada jurusan Anda untuk dihitung ulang pada tahun aktif ini.',
        ]);
    }

    public function show(int $plp_order)
    {
        if (! in_array($plp_order, [0, 1, 2], true)) {
            abort(404);
        }

        return redirect()->to(url('yudisium/plp').'?tab=plp'.$plp_order);
    }

    public function showOnly(Request $request)
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $useLegacyTabs = $this->usesLegacyTabs($activeYear, $user);
        $tabStubs = $this->collectTabStubs($activeYear, $user, $useLegacyTabs);
        $activeTab = $this->resolveActiveTabKey($tabStubs, (string) $request->query('tab', ''));

        $plpTabs = [];
        foreach ($tabStubs as $stub) {
            $plpTabs[] = $stub['key'] === $activeTab
                ? $this->buildTabPayload($activeYear, $user, $stub, $useLegacyTabs, true)
                : $this->emptyTabShell($stub);
        }

        return view('report.only.yudisium', [
            'activeYear' => $activeYear,
            'useLegacyTabs' => $useLegacyTabs,
            'plpTabs' => $plpTabs,
            'activeTab' => $activeTab,
        ]);
    }

    public function exportXlsx(Request $request): BinaryFileResponse
    {
        $user = auth()->user();

        if (! $user->hasRole('kajur')) {
            abort(403, 'Hanya kajur yang dapat mengunduh rekap yudisium jurusan.');
        }

        $subjectId = (string) ($user->subject_id ?? '');
        if ($subjectId === '') {
            abort(422, 'Akun kajur belum memiliki jurusan (subject) yang terhubung.');
        }

        $activeYear = (int) Map::activeYear($user);
        $useLegacyTabs = $this->usesLegacyTabs($activeYear, $user);
        $tabStubs = $this->collectTabStubs($activeYear, $user, $useLegacyTabs);
        $tabKey = $this->resolveActiveTabKey($tabStubs, (string) $request->query('tab', ''));

        if ($tabKey === null) {
            abort(404, 'Tab yudisium tidak ditemukan.');
        }

        $bucket = $this->tabKeyToBucket($tabKey);
        if ($bucket === null) {
            abort(404, 'Tab yudisium tidak valid.');
        }

        $jurusanData = $useLegacyTabs
            ? $this->service->getJurusanRows($activeYear, $subjectId, $bucket)
            : $this->service->getBucketJurusanRows($activeYear, $subjectId, $bucket);

        $label = Map::plpBucketLabel($bucket);
        $safeLabel = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $label) ?: 'plp';
        $fileName = sprintf('yudisium-%s-%d-%s.xlsx', $safeLabel, $activeYear, date('YmdHis'));

        return Excel::download(
            new ExportYudisiumJurusan($jurusanData['rows']),
            $fileName
        );
    }

    public function loadTab(Request $request)
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $useLegacyTabs = $this->usesLegacyTabs($activeYear, $user);
        $tabStubs = $this->collectTabStubs($activeYear, $user, $useLegacyTabs);
        $tabKey = (string) $request->query('tab', '');

        $stub = collect($tabStubs)->firstWhere('key', $tabKey);
        if ($stub === null) {
            abort(404);
        }

        $tab = $this->buildTabPayload($activeYear, $user, $stub, $useLegacyTabs, true);

        return view('report.partials.yudisium-tab-pane', ['tab' => $tab]);
    }

    private function usesLegacyTabs(int $activeYear, $user): bool
    {
        $baseMapQuery = Map::query()
            ->visibleToUser($user)
            ->where('year', $activeYear)
            ->whereNotNull('student_id');

        return $activeYear <= 2023
            && ((clone $baseMapQuery)->where('plp1', 1)->exists()
                || (clone $baseMapQuery)->where('plp2', 1)->exists());
    }

    /**
     * @return array<int, array{key: string, label: string, plp_order: int}>
     */
    private function collectTabStubs(int $activeYear, $user, bool $useLegacyTabs): array
    {
        $stubs = [];

        if ($useLegacyTabs) {
            $baseMapQuery = Map::query()
                ->visibleToUser($user)
                ->where('year', $activeYear)
                ->whereNotNull('student_id');

            foreach ([1, 2] as $plpOrder) {
                $tabExists = $plpOrder === 1
                    ? (clone $baseMapQuery)->where('plp1', 1)->exists()
                    : (clone $baseMapQuery)->where('plp2', 1)->exists();

                if (! $tabExists || ! $this->userCanViewBucket($user, $plpOrder)) {
                    continue;
                }

                $stubs[] = [
                    'key' => 'plp'.$plpOrder,
                    'label' => 'PLP '.$plpOrder,
                    'plp_order' => $plpOrder,
                ];
            }

            return $stubs;
        }

        foreach ([0, 1, 2] as $bucket) {
            if (! $this->service->bucketIsActive($activeYear, $bucket)) {
                continue;
            }

            if (! $this->userCanViewBucket($user, $bucket)) {
                continue;
            }

            $stubs[] = [
                'key' => 'plp'.$bucket,
                'label' => Map::plpBucketLabel($bucket),
                'plp_order' => $bucket,
            ];
        }

        return $stubs;
    }

    /**
     * @param  array<int, array{key: string}>  $tabStubs
     */
    private function resolveActiveTabKey(array $tabStubs, string $requestedTab): ?string
    {
        if ($tabStubs === []) {
            return null;
        }

        foreach ($tabStubs as $stub) {
            if ($stub['key'] === $requestedTab) {
                return $requestedTab;
            }
        }

        return $tabStubs[0]['key'];
    }

    /**
     * @param  array{key: string, label: string, plp_order: int}  $stub
     */
    private function emptyTabShell(array $stub): array
    {
        return [
            'key' => $stub['key'],
            'label' => $stub['label'],
            'plp_order' => $stub['plp_order'],
            'plp_label' => $stub['label'],
            'loaded' => false,
            'dekanatSummary' => null,
            'jurusanRows' => [],
            'lectureForms' => [],
            'teacherForms' => [],
            'gradeRecap' => null,
        ];
    }

    /**
     * @param  array{key: string, label: string, plp_order: int}  $stub
     */
    private function buildTabPayload(int $activeYear, $user, array $stub, bool $useLegacyTabs, bool $loaded): array
    {
        $payload = $useLegacyTabs
            ? $this->buildLegacyTab($activeYear, $user, $stub['plp_order'])
            : $this->buildModernTab($activeYear, $user, $stub['plp_order']);

        $payload['loaded'] = $loaded;

        return $payload;
    }

    private function buildLegacyTab(int $activeYear, $user, int $plpOrder): array
    {
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

        return [
            'key' => 'plp'.$plpOrder,
            'label' => 'PLP '.$plpOrder,
            'plp_order' => $plpOrder,
            'plp_label' => 'PLP '.$plpOrder,
            'dekanatSummary' => $dekanatSummary,
            'jurusanRows' => $jurusanData['rows'],
            'lectureForms' => $jurusanData['lectureForms'],
            'teacherForms' => $jurusanData['teacherForms'],
            'gradeRecap' => $jurusanData['gradeRecap'] ?? null,
        ];
    }

    private function buildModernTab(int $activeYear, $user, int $bucket): array
    {
        $dekanatSummary = null;
        $jurusanData = [
            'rows' => [],
            'lectureForms' => [],
            'teacherForms' => [],
        ];

        if ($user->hasAnyRole(['ketua', 'dekanat'])) {
            $dekanatSummary = $this->service->getBucketDekanatSummary($activeYear, $bucket);
        }

        if ($user->hasRole('kajur')) {
            $jurusanData = $this->service->getBucketJurusanRows($activeYear, $user->subject_id, $bucket);
        }

        return [
            'key' => 'plp'.$bucket,
            'label' => Map::plpBucketLabel($bucket),
            'plp_order' => $bucket,
            'plp_label' => Map::plpBucketLabel($bucket),
            'dekanatSummary' => $dekanatSummary,
            'jurusanRows' => $jurusanData['rows'],
            'lectureForms' => $jurusanData['lectureForms'],
            'teacherForms' => $jurusanData['teacherForms'],
            'gradeRecap' => $jurusanData['gradeRecap'] ?? null,
        ];
    }

    private function tabKeyToBucket(string $tabKey): ?int
    {
        if (! preg_match('/^plp([0-2])$/', $tabKey, $matches)) {
            return null;
        }

        return (int) $matches[1];
    }

    private function userCanViewBucket($user, int $bucket): bool
    {
        return match ($bucket) {
            0 => $user->can('yudisium/plp-read') || $user->can('yudisium/plp1-read') || $user->can('yudisium/plp2-read'),
            1 => $user->can('yudisium/plp1-read'),
            2 => $user->can('yudisium/plp2-read'),
            default => false,
        };
    }
}
