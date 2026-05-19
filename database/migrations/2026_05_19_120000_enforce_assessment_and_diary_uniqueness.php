<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Normalisasi assessor, hapus duplikat slot penilaian, dan satu diary per tanggal per PLP per plot.
     */
    public function up(): void
    {
        DB::table('assessments')->orderBy('id')->chunkById(200, function ($rows): void {
            foreach ($rows as $row) {
                $raw = strtolower(trim((string) ($row->assessor ?? '')));
                $assessor = in_array($raw, ['guru', 'dosen'], true) ? $raw : 'dosen';
                DB::table('assessments')->where('id', $row->id)->update(['assessor' => $assessor]);
            }
        });

        $assessmentDupGroups = DB::table('assessments')
            ->select([
                'map_id',
                'plp_order',
                'form_id',
                'form_order',
                'assessor',
                DB::raw('MIN(id) as keep_id'),
                DB::raw('COUNT(*) as c'),
            ])
            ->groupBy('map_id', 'plp_order', 'form_id', 'form_order', 'assessor')
            ->having('c', '>', 1)
            ->get();

        foreach ($assessmentDupGroups as $g) {
            $q = DB::table('assessments')
                ->where('map_id', $g->map_id)
                ->where('plp_order', $g->plp_order)
                ->where('form_order', $g->form_order)
                ->where('assessor', $g->assessor);

            if ($g->form_id === null) {
                $q->whereNull('form_id');
            } else {
                $q->where('form_id', $g->form_id);
            }

            $q->where('id', '!=', $g->keep_id)->delete();
        }

        Schema::table('assessments', function (Blueprint $table): void {
            $table->unique(
                ['map_id', 'plp_order', 'form_id', 'form_order', 'assessor'],
                'assessments_map_plp_form_slot_assessor_unique'
            );
        });

        DB::table('diaries')->whereNull('plp_order')->orderBy('id')->chunkById(200, function ($rows): void {
            foreach ($rows as $row) {
                $map = DB::table('maps')->where('id', $row->map_id)->first();
                $order = 2;
                if ($map) {
                    if ((int) $map->plp2 === 1) {
                        $order = 2;
                    } elseif ((int) $map->plp1 === 1) {
                        $order = 1;
                    } else {
                        $order = 2;
                    }
                }
                DB::table('diaries')->where('id', $row->id)->update(['plp_order' => $order]);
            }
        });

        DB::table('diaries')->whereNull('log_date')->update([
            'log_date' => DB::raw('DATE(created_at)'),
        ]);

        $diaryDupGroups = DB::table('diaries')
            ->select([
                'map_id',
                'plp_order',
                'log_date',
                DB::raw('MIN(id) as keep_id'),
                DB::raw('COUNT(*) as c'),
            ])
            ->groupBy('map_id', 'plp_order', 'log_date')
            ->having('c', '>', 1)
            ->get();

        foreach ($diaryDupGroups as $g) {
            DB::table('diaries')
                ->where('map_id', $g->map_id)
                ->where('plp_order', $g->plp_order)
                ->whereDate('log_date', $g->log_date)
                ->where('id', '!=', $g->keep_id)
                ->delete();
        }

        Schema::table('diaries', function (Blueprint $table): void {
            $table->unique(
                ['map_id', 'plp_order', 'log_date'],
                'diaries_map_plp_log_date_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diaries', function (Blueprint $table): void {
            $table->dropUnique('diaries_map_plp_log_date_unique');
        });

        Schema::table('assessments', function (Blueprint $table): void {
            $table->dropUnique('assessments_map_plp_form_slot_assessor_unique');
        });
    }
};
