<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExportPlpSummary implements WithMultipleSheets
{
    /**
     * @param  array<int, array<string, mixed>>  $dplRows
     * @param  array<int, array<string, mixed>>  $gpRows
     */
    public function __construct(
        private array $dplRows,
        private array $gpRows
    ) {
    }

    public function sheets(): array
    {
        return [
            new ExportPlpSummaryDplSheet($this->dplRows),
            new ExportPlpSummaryGpSheet($this->gpRows),
        ];
    }
}

class ExportPlpSummaryDplSheet implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function __construct(private array $rows)
    {
    }

    public function collection(): Collection
    {
        return collect($this->rows)->map(function (array $row): array {
            return [
                (int) ($row['no'] ?? 0),
                (string) ($row['nama'] ?? ''),
                (string) ($row['jurusan'] ?? ''),
                (int) ($row['mahasiswa'] ?? 0),
            ];
        });
    }

    public function headings(): array
    {
        return ['NO', 'NAMA', 'jurusan', 'mahasiswa'];
    }

    public function title(): string
    {
        return 'DPL';
    }
}

class ExportPlpSummaryGpSheet implements FromCollection, WithHeadings, WithTitle
{
    /**
     * @param  array<int, array<string, mixed>>  $rows
     */
    public function __construct(private array $rows)
    {
    }

    public function collection(): Collection
    {
        return collect($this->rows)->map(function (array $row): array {
            return [
                (int) ($row['no'] ?? 0),
                (string) ($row['nama'] ?? ''),
                (string) ($row['mapel'] ?? ''),
                (string) ($row['sekolah'] ?? ''),
                (int) ($row['mahasiswa'] ?? 0),
            ];
        });
    }

    public function headings(): array
    {
        return ['NO', 'NAMA', 'mapel', 'sekolah', 'mahasiswa'];
    }

    public function title(): string
    {
        return 'GP';
    }
}
