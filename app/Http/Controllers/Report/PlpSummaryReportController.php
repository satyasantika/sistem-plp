<?php

namespace App\Http\Controllers\Report;

use App\Exports\ExportPlpSummary;
use App\Http\Controllers\Controller;
use App\Models\Map;
use App\Services\PlpSummaryReportService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PlpSummaryReportController extends Controller
{
    public function __construct(private PlpSummaryReportService $service)
    {
    }

    public function show()
    {
        $user = auth()->user();
        $activeYear = Map::activeYear($user);
        $report = $this->service->build($activeYear, $user);

        return view('report.only.summary', array_merge($report, [
            'activeYear' => $activeYear,
        ]));
    }

    public function exportXlsx(): BinaryFileResponse
    {
        $user = auth()->user();

        if (! $user->hasRole('data')) {
            abort(403, 'Hanya role data yang dapat mengunduh laporan summary PLP.');
        }

        $activeYear = Map::activeYear($user);
        $exportData = $this->service->buildExportRows($activeYear, $user);
        $fileName = sprintf('summary-plp-%d-%s.xlsx', $activeYear, date('YmdHis'));

        return Excel::download(
            new ExportPlpSummary($exportData['dpl'], $exportData['gp']),
            $fileName
        );
    }
}
