<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian {{ $plp_label ?? ('PLP '.$plp_order) }} Jurusan {{ auth()->user()->subjects->departement }}</h5>
                </div>
                <div class="card-body">
                    @if (!empty($deferRecap) && !empty($recapUrl))
                        <div
                            class="yudisium-recap-host mb-3"
                            data-yudisium-recap-url="{{ $recapUrl }}"
                            data-yudisium-table-id="{{ $tableId ?? 'yudisium-jurusan-table' }}"
                        >
                            <div class="yudisium-recap-skeleton text-muted small py-2">
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Memuat rekap nilai…
                            </div>
                        </div>
                    @else
                        @include('report.partials.yudisium-jurusan-recap', [
                            'gradeRecap' => $gradeRecap ?? null,
                            'jurusanRows' => $jurusanRows,
                            'tableId' => $tableId ?? 'yudisium-jurusan-table',
                        ])
                    @endif

                    <div
                        class="yudisium-table-panel is-hidden"
                        data-yudisium-table-panel
                        @if (!empty($deferTable) && !empty($tableUrl))
                            data-yudisium-table-url="{{ $tableUrl }}"
                            data-yudisium-table-id="{{ $tableId ?? 'yudisium-jurusan-table' }}"
                        @endif
                    >
                        @if (!empty($deferTable) && !empty($tableUrl))
                            <p class="yudisium-table-deferred-hint text-muted small mb-0 py-2">
                                Daftar mahasiswa dimuat setelah Anda memilih filter pada rekap di atas.
                            </p>
                        @else
                            @include('report.partials.yudisium-jurusan-table-panel', [
                                'jurusanRows' => $jurusanRows,
                                'lectureForms' => $lectureForms,
                                'teacherForms' => $teacherForms,
                                'tableId' => $tableId ?? 'yudisium-jurusan-table',
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
