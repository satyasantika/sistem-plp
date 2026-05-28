<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportYudisiumJurusan implements FromCollection, WithHeadings
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
            $grade = $row['grade_display'] ?? null;
            if ($grade === null && (float) ($row['grade'] ?? 0) > 0) {
                $grade = number_format((float) $row['grade'], 2, '.', '');
            }

            $letter = (string) ($row['letter_display'] ?? $row['letter'] ?? '');

            return [
                (string) ($row['student_nim'] ?? ''),
                (string) ($row['student_name'] ?? ''),
                $grade ?? '',
                $letter,
            ];
        });
    }

    public function headings(): array
    {
        return ['NIM', 'Nama Mahasiswa', 'Nilai Akhir', 'Nilai Huruf'];
    }
}
