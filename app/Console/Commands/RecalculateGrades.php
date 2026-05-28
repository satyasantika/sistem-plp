<?php

namespace App\Console\Commands;

use App\Models\Map;
use App\Services\MapFinalGradeCalculator;
use Illuminate\Console\Command;

class RecalculateGrades extends Command
{
    protected $signature = 'plp:recalculate-grades
                            {year? : Tahun akademik (misal 2024). Kosongkan untuk melihat daftar tahun tersedia.}
                            {--dry-run : Jalankan tanpa menyimpan ke DB}';

    protected $description = 'Hitung ulang nilai akhir (grade/letter) semua mahasiswa untuk tahun tertentu.';

    public function handle(MapFinalGradeCalculator $calculator): int
    {
        $yearArg = $this->argument('year');

        if ($yearArg === null) {
            $years = Map::query()
                ->whereNotNull('year')
                ->where(fn ($q) => $q->where('plp', 1)->orWhere('plp1', 1)->orWhere('plp2', 1))
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year')
                ->all();

            if (empty($years)) {
                $this->error('Tidak ada data map dengan flag PLP ditemukan.');
                return self::FAILURE;
            }

            $this->info('Tahun tersedia: ' . implode(', ', $years));
            $this->line('Jalankan: php artisan plp:recalculate-grades <tahun>');

            return self::SUCCESS;
        }

        $year = (int) $yearArg;

        $maps = Map::query()
            ->where('year', $year)
            ->whereNotNull('student_id')
            ->where(fn ($q) => $q->where('plp', 1)->orWhere('plp1', 1)->orWhere('plp2', 1))
            ->get(['id', 'plp', 'plp1', 'plp2', 'year', 'grade', 'letter', 'grade1', 'letter1', 'grade2', 'letter2']);

        if ($maps->isEmpty()) {
            $this->error("Tidak ada map dengan flag PLP ditemukan untuk tahun {$year}.");
            return self::FAILURE;
        }

        $isDryRun = $this->option('dry-run');
        $total = $maps->count();

        $this->info("Menghitung ulang {$total} map untuk tahun {$year}" . ($isDryRun ? ' (dry-run)' : '') . '...');

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $updated = 0;
        $errors = 0;

        foreach ($maps as $map) {
            try {
                if (! $isDryRun) {
                    $calculator->recalculateMapFully($map);
                }

                $updated++;
            } catch (\Throwable $e) {
                $errors++;
                $this->newLine();
                $this->error("Map #{$map->id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info("Selesai. Diproses: {$updated}" . ($errors > 0 ? ", gagal: {$errors}" : '') . '.');

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
