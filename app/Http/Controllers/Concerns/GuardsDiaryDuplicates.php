<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Diary;
use App\Models\Map;
use Carbon\Carbon;
use Illuminate\Http\Request;

trait GuardsDiaryDuplicates
{
    /**
     * Selaras dengan pemilihan PLP pada penilaian (map bisa aktif PLP1 dan/atau PLP2).
     */
    protected function resolvedDiaryPlpOrder(?Map $map, ?int $incoming): int
    {
        if ($incoming !== null && $incoming > 0) {
            return (int) $incoming;
        }

        if (! $map) {
            return 1;
        }

        if ((int) $map->plp2 === 1) {
            return 2;
        }

        if ((int) $map->plp1 === 1) {
            return 1;
        }

        return 2;
    }

    protected function diaryDuplicateResponse(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Sudah ada catatan harian untuk tanggal ini pada PLP yang sama. Pilih tanggal lain atau ubah entri yang sudah ada.',
        ], 422);
    }

    protected function assertDiaryDateUnique(Request $request, Map $map, int $plpOrder, ?Diary $existing = null): ?\Illuminate\Http\JsonResponse
    {
        $raw = $request->input('log_date');
        if ($raw === null || $raw === '') {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal catatan (log_date) wajib diisi.',
            ], 422);
        }

        try {
            $day = Carbon::parse($raw)->startOfDay();
        } catch (\Throwable) {
            return response()->json([
                'success' => false,
                'message' => 'Format tanggal tidak valid.',
            ], 422);
        }

        $query = Diary::query()
            ->where('map_id', (int) $map->id)
            ->where('plp_order', $plpOrder)
            ->whereDate('log_date', $day);

        if ($existing !== null) {
            $query->whereKeyNot($existing->getKey());
        }

        if ($query->exists()) {
            return $this->diaryDuplicateResponse();
        }

        return null;
    }
}
