<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Map;
use App\Models\PlpFinalGradeFormRule;
use App\Models\PlpFinalGradeWeight;
use App\Support\Grading;

/**
 * Menghitung grade1/letter1, grade2/letter2 pada maps berdasarkan aturan tahun + PLP + peran penilai.
 * PLP 1: rata-rata arithmetic total nilai assessment (Σ grade) dibagi banyak form dalam aturan (dosen).
 * PLP 2: blok dosen dan guru masing-masing dinormalkan dengan Σ times pada form terpilih,
 * lalu digabung dengan bobot (default 40% / 60%).
 * PLP (plp_order 0): aturan khusus nilai gabungan kolom maps.grade / maps.letter (mode ringkas).
 */
final class MapFinalGradeCalculator
{
    /**
     * Hitung ulang grade1/letter1, grade2/letter2, dan nilai gabungan (grade/letter) satu map.
     */
    public function recalculateMapFully(Map $map): void
    {
        if ((bool) $map->plp1 || (bool) $map->plp) {
            $this->recalculateMapForPlp($map->id, 1);
        }

        if ((bool) $map->plp2 || (bool) $map->plp) {
            $this->recalculateMapForPlp($map->id, 2);
        }

        $this->recalculateCombinedDisplay($map->id);
    }

    public function recalculateMapForPlp(int $mapId, int $plpOrder): void
    {
        if (! in_array($plpOrder, [1, 2], true)) {
            return;
        }

        $map = Map::query()->find($mapId);
        if (! $map) {
            return;
        }

        if ($plpOrder === 1 && ! (bool) $map->plp1 && ! (bool) $map->plp) {
            return;
        }

        if ($plpOrder === 2 && ! (bool) $map->plp2 && ! (bool) $map->plp) {
            return;
        }

        if ($plpOrder === 1) {
            $this->applyPlp1Grade($map);
        } else {
            $this->applyPlp2Grade($map);
        }

        $map->save();
    }

    /**
     * Mode “only PLP”: menulis aggregate ke kolom {@see Map::$grade} / {@see Map::$letter}.
     * Jika ada aturan dengan plp_order = 0, dipakai daftar form untuk tahun map tersebut,
     * dengan filter assessment mengikuti PLP efektif di plot (resolved). Jika tidak ada aturan PLP ,
     * dipertahankan perilaku fallback (aturan PLP 1 atau 2 sesuai PLP efektif).
     */
    public function recalculateCombinedDisplay(int $mapId): void
    {
        $map = Map::query()->find($mapId);
        if (! $map) {
            return;
        }

        $resolved = $this->resolvedPlpOrder($map);

        if ($resolved === 1 && ! (bool) $map->plp1 && ! (bool) $map->plp) {
            return;
        }

        if ($resolved === 2 && ! (bool) $map->plp2 && ! (bool) $map->plp) {
            return;
        }

        $year = (int) ($map->year ?? 0);

        if (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', 0)->exists()) {
            $this->applyCombinedFromPlpBucketRules($map, $resolved, 0);

            $map->save();

            return;
        }

        if ($resolved === 1) {
            $this->applyPlp1GradeInternal($map, true);
            $map->save();

            return;
        }

        $this->applyPlp2GradeInternal($map, true);
        $map->save();
    }

    protected function resolvedPlpOrder(Map $map): int
    {
        return $map->resolvedAssessmentPlpOrder();
    }

    /**
     * Nilai gabungan (grade / letter) dari aturan plp_bucket (biasanya 0 = "PLP"),
     * dengan summing assessments pada sesi PLP efektif $assessmentPlpOrder.
     */
    protected function applyCombinedFromPlpBucketRules(Map $map, int $assessmentPlpOrder, int $plpBucket): void
    {
        $year = (int) ($map->year ?? 0);

        $dosenForms = PlpFinalGradeFormRule::query()
            ->forYear($year)
            ->where('plp_order', $plpBucket)
            ->where('assessor', 'dosen')
            ->pluck('form_id')
            ->unique()
            ->values();

        $guruForms = PlpFinalGradeFormRule::query()
            ->forYear($year)
            ->where('plp_order', $plpBucket)
            ->where('assessor', 'guru')
            ->pluck('form_id')
            ->unique()
            ->values();

        $weight = PlpFinalGradeWeight::query()
            ->where('year', $year)
            ->where('plp_order', $plpBucket)
            ->first();

        if ($weight === null && $plpBucket !== 2) {
            $weight = PlpFinalGradeWeight::query()
                ->where('year', $year)
                ->where('plp_order', 2)
                ->first();
        }

        $dRatio = $weight ? (float) $weight->dosen_weight : 0.4;
        $gRatio = $weight ? (float) $weight->guru_weight : 0.6;

        if ($dosenForms->isEmpty() && $guruForms->isEmpty()) {
            $map->grade = null;
            $map->letter = null;

            return;
        }

        // Hanya dosen → rata seperti PLP 1 (pembagi = jumlah form dalam aturan)
        if ($guruForms->isEmpty()) {
            $denom = max(1, $dosenForms->count());
            $sum = Assessment::query()
                ->where('map_id', $map->id)
                ->where('plp_order', $assessmentPlpOrder)
                ->where('assessor', 'dosen')
                ->whereIn('form_id', $dosenForms)
                ->sum('grade');
            $avg = round((float) $sum / $denom, 2);
            $map->grade = $avg;
            $map->letter = Grading::numericToLetter((float) $avg);

            return;
        }

        // Hanya guru → satu blok dibagi Σ times form
        if ($dosenForms->isEmpty()) {
            $gTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', $plpBucket)->where('assessor', 'guru')->whereIn('form_id', $guruForms)->sum('times') ?: 0);
            if ($gTimes <= 0) {
                $map->grade = null;
                $map->letter = null;

                return;
            }
            $sumG = Assessment::query()
                ->where('map_id', $map->id)
                ->where('plp_order', $assessmentPlpOrder)
                ->where('assessor', 'guru')
                ->whereIn('form_id', $guruForms)
                ->sum('grade');
            $final = round((float) $sumG / (float) $gTimes, 2);
            $map->grade = $final;
            $map->letter = Grading::numericToLetter((float) $final);

            return;
        }

        // Dosen + guru → pola PLP 2 dengan bobot
        $lectureTotal = null;
        $dTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', $plpBucket)->where('assessor', 'dosen')->whereIn('form_id', $dosenForms)->sum('times') ?: 0);
        if ($dTimes > 0) {
            $sumD = Assessment::query()
                ->where('map_id', $map->id)
                ->where('plp_order', $assessmentPlpOrder)
                ->where('assessor', 'dosen')
                ->whereIn('form_id', $dosenForms)
                ->sum('grade');
            $lectureTotal = round((float) $sumD / (float) $dTimes, 0);
        }

        $teacherTotal = null;
        $gTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', $plpBucket)->where('assessor', 'guru')->whereIn('form_id', $guruForms)->sum('times') ?: 0);
        if ($gTimes > 0) {
            $sumG = Assessment::query()
                ->where('map_id', $map->id)
                ->where('plp_order', $assessmentPlpOrder)
                ->where('assessor', 'guru')
                ->whereIn('form_id', $guruForms)
                ->sum('grade');
            $teacherTotal = (float) $sumG / (float) $gTimes;
        }

        if ($lectureTotal === null && $teacherTotal === null) {
            $finalGrade = null;
        } elseif ($lectureTotal === null) {
            $finalGrade = round($teacherTotal, 2);
        } elseif ($teacherTotal === null) {
            $finalGrade = round($lectureTotal, 2);
        } else {
            $finalGrade = round($dRatio * $lectureTotal + $gRatio * $teacherTotal, 2);
        }

        if ($finalGrade !== null) {
            $finalGrade = min(100.0, max(0.0, $finalGrade));
        }

        $map->grade = $finalGrade;
        $map->letter = Grading::numericToLetter($finalGrade);
    }

    protected function applyPlp1Grade(Map $map): void
    {
        $this->applyPlp1GradeInternal($map, false);
    }

    /**
     * @param  bool  $writeCombined  kalau true, hasil ditulis ke {@see Map::$grade}/{@see Map::$letter}; jika false ke grade1/letter1
     */
    protected function applyPlp1GradeInternal(Map $map, bool $writeCombined): void
    {
        $year = (int) ($map->year ?? 0);

        $dosenForms = PlpFinalGradeFormRule::query()
            ->forYear($year)
            ->where('plp_order', 1)
            ->where('assessor', 'dosen')
            ->pluck('form_id')
            ->unique()
            ->values();

        $guruForms = PlpFinalGradeFormRule::query()
            ->forYear($year)
            ->where('plp_order', 1)
            ->where('assessor', 'guru')
            ->pluck('form_id')
            ->unique()
            ->values();

        if ($dosenForms->isEmpty() && $guruForms->isEmpty()) {
            if ($writeCombined) {
                $map->grade = null;
                $map->letter = null;
            } else {
                $map->grade1 = null;
                $map->letter1 = null;
            }

            return;
        }

        $weight = PlpFinalGradeWeight::query()
            ->where('year', $year)
            ->where('plp_order', 1)
            ->first();

        if ($weight === null) {
            $weight = PlpFinalGradeWeight::query()
                ->where('year', $year)
                ->where('plp_order', 2)
                ->first();
        }

        $dRatio = $weight ? (float) $weight->dosen_weight : 0.4;
        $gRatio = $weight ? (float) $weight->guru_weight : 0.6;

        $lectureTotal = null;
        if ($dosenForms->isNotEmpty()) {
            $dTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', 1)->where('assessor', 'dosen')->whereIn('form_id', $dosenForms)->sum('times') ?: 0);
            if ($dTimes > 0) {
                $sumD = Assessment::query()
                    ->where('map_id', $map->id)
                    ->where('plp_order', 1)
                    ->where('assessor', 'dosen')
                    ->whereIn('form_id', $dosenForms)
                    ->sum('grade');
                $lectureTotal = round((float) $sumD / (float) $dTimes, 0);
            }
        }

        $teacherTotal = null;
        if ($guruForms->isNotEmpty()) {
            $gTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', 1)->where('assessor', 'guru')->whereIn('form_id', $guruForms)->sum('times') ?: 0);
            if ($gTimes > 0) {
                $sumG = Assessment::query()
                    ->where('map_id', $map->id)
                    ->where('plp_order', 1)
                    ->where('assessor', 'guru')
                    ->whereIn('form_id', $guruForms)
                    ->sum('grade');
                $teacherTotal = (float) $sumG / (float) $gTimes;
            }
        }

        if ($lectureTotal === null && $teacherTotal === null) {
            $finalGrade = null;
        } elseif ($lectureTotal === null) {
            $finalGrade = round($teacherTotal, 2);
        } elseif ($teacherTotal === null) {
            $finalGrade = round($lectureTotal, 2);
        } else {
            $finalGrade = round($dRatio * $lectureTotal + $gRatio * $teacherTotal, 2);
        }

        if ($finalGrade !== null) {
            $finalGrade = min(100.0, max(0.0, $finalGrade));
        }

        $letter = Grading::numericToLetter($finalGrade ?? null);

        if ($writeCombined) {
            $map->grade = $finalGrade;
            $map->letter = $letter;
        } else {
            $map->grade1 = $finalGrade;
            $map->letter1 = $letter;
        }
    }

    protected function applyPlp2Grade(Map $map): void
    {
        $this->applyPlp2GradeInternal($map, false);
    }

    protected function applyPlp2GradeInternal(Map $map, bool $writeCombined): void
    {
        $year = (int) ($map->year ?? 0);

        $dosenForms = PlpFinalGradeFormRule::query()
            ->forYear($year)
            ->where('plp_order', 2)
            ->where('assessor', 'dosen')
            ->pluck('form_id')
            ->unique()
            ->values();

        $guruForms = PlpFinalGradeFormRule::query()
            ->forYear($year)
            ->where('plp_order', 2)
            ->where('assessor', 'guru')
            ->pluck('form_id')
            ->unique()
            ->values();

        $weight = PlpFinalGradeWeight::query()
            ->where('year', $year)
            ->where('plp_order', 2)
            ->first();

        $dRatio = $weight ? (float) $weight->dosen_weight : 0.4;
        $gRatio = $weight ? (float) $weight->guru_weight : 0.6;

        if ($dosenForms->isEmpty() && $guruForms->isEmpty()) {
            if ($writeCombined) {
                $map->grade = null;
                $map->letter = null;
            } else {
                $map->grade2 = null;
                $map->letter2 = null;
            }

            return;
        }

        $lectureTotal = null;
        if ($dosenForms->isNotEmpty()) {
            $dTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', 2)->where('assessor', 'dosen')->whereIn('form_id', $dosenForms)->sum('times') ?: 0);
            if ($dTimes > 0) {
                $sumD = Assessment::query()
                    ->where('map_id', $map->id)
                    ->where('plp_order', 2)
                    ->where('assessor', 'dosen')
                    ->whereIn('form_id', $dosenForms)
                    ->sum('grade');
                $lectureTotal = round((float) $sumD / (float) $dTimes, 0);
            }
        }

        $teacherTotal = null;
        if ($guruForms->isNotEmpty()) {
            $gTimes = (int) (PlpFinalGradeFormRule::query()->forYear($year)->where('plp_order', 2)->where('assessor', 'guru')->whereIn('form_id', $guruForms)->sum('times') ?: 0);
            if ($gTimes > 0) {
                $sumG = Assessment::query()
                    ->where('map_id', $map->id)
                    ->where('plp_order', 2)
                    ->where('assessor', 'guru')
                    ->whereIn('form_id', $guruForms)
                    ->sum('grade');
                $teacherTotal = (float) $sumG / (float) $gTimes;
            }
        }

        // Jika salah satu blok tidak punya konfigurasi atau pembagi 0 — gunakan yang tersedia.
        if ($lectureTotal === null && $teacherTotal === null) {
            $finalGrade = null;
        } elseif ($lectureTotal === null) {
            $finalGrade = round($teacherTotal, 2);
        } elseif ($teacherTotal === null) {
            $finalGrade = round($lectureTotal, 2);
        } else {
            $finalGrade = round($dRatio * $lectureTotal + $gRatio * $teacherTotal, 2);
        }

        if ($finalGrade !== null) {
            $finalGrade = min(100.0, max(0.0, $finalGrade));
        }

        $letter = Grading::numericToLetter($finalGrade ?? null);

        if ($writeCombined) {
            $map->grade = $finalGrade;
            $map->letter = $letter;
        } else {
            $map->grade2 = $finalGrade;
            $map->letter2 = $letter;
        }
    }
}
