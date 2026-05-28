<?php

namespace Database\Seeders;

use App\Models\PlpFinalGradeFormRule;
use App\Models\PlpFinalGradeWeight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Mengisi aturan form & bobot PLP 2 untuk setiap tahun yang muncul di maps.
 * Hanya memasukkan form yang benar-benar ada di tabel forms.
 */
class PlpFinalGradeDefaultsSeeder extends Seeder
{
    public function run(): void
    {
        $years = DB::table('maps')->distinct()->pluck('year')->filter(fn ($y) => $y !== null)->map(fn ($y) => (int) $y)->values();
        if ($years->isEmpty()) {
            $years = collect([(int) config('plp.default_year', 2026)]);
        }

        foreach ($years as $year) {
            $this->seedWeights($year);
            $this->seedRules($year);
        }
    }

    private function formExists(string $id): bool
    {
        return DB::table('forms')->where('id', $id)->exists();
    }

    private function seedWeights(int $year): void
    {
        PlpFinalGradeWeight::query()->updateOrCreate(
            ['year' => $year, 'plp_order' => 2],
            [
                'dosen_weight' => 0.4,
                'guru_weight' => 0.6,
            ]
        );
    }

    private function seedRules(int $year): void
    {
        $use2024 = $this->formExists('2024N2');
        $p = $use2024 ? '2024' : '2022';

        // PLP 1 — hanya blok dosen (rata-rata arithmetic sesuai kode legacy).
        $plp1Dosen = array_filter(["{$p}N2", "{$p}N8"], fn ($fid) => $this->formExists($fid));

        foreach ($plp1Dosen as $fid) {
            PlpFinalGradeFormRule::query()->firstOrCreate(
                [
                    'year' => $year,
                    'plp_order' => 1,
                    'assessor' => 'dosen',
                    'form_id' => $fid,
                ]
            );
        }

        // PLP 2 — blok dosen dan guru (sesuai kode legacy).
        $plp2Dosen = array_values(array_filter(
            [$use2024 ? '2024N2' : '2022N2', $use2024 ? '2024N6' : '2022N6', $use2024 ? '2024N7' : '2022N7'],
            fn ($fid) => $this->formExists($fid)
        ));

        foreach ($plp2Dosen as $fid) {
            PlpFinalGradeFormRule::query()->firstOrCreate(
                [
                    'year' => $year,
                    'plp_order' => 2,
                    'assessor' => 'dosen',
                    'form_id' => $fid,
                ]
            );

            PlpFinalGradeFormRule::query()->firstOrCreate(
                [
                    'year' => $year,
                    'plp_order' => 0,
                    'assessor' => 'dosen',
                    'form_id' => $fid,
                ]
            );
        }

        $plp2GuruCandidates = [$use2024 ? '2024N1' : '2022N1', "{$p}N3", "{$p}N4", "{$p}N5", "{$p}N6", "{$p}N7"];

        foreach ($plp2GuruCandidates as $fid) {
            if (! $this->formExists($fid)) {
                continue;
            }
            PlpFinalGradeFormRule::query()->firstOrCreate(
                [
                    'year' => $year,
                    'plp_order' => 2,
                    'assessor' => 'guru',
                    'form_id' => $fid,
                ]
            );

            PlpFinalGradeFormRule::query()->firstOrCreate(
                [
                    'year' => $year,
                    'plp_order' => 0,
                    'assessor' => 'guru',
                    'form_id' => $fid,
                ]
            );
        }
    }
}
