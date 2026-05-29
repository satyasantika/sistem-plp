<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian {{ $plp_label ?? 'PLP' }} Jurusan {{ auth()->user()->subjects->departement }}</h5>
                </div>
                <div class="card-body">
                    @include('report.partials.yudisium-jurusan-recap', [
                        'gradeRecap' => $gradeRecap ?? null,
                        'jurusanRows' => $jurusanRows,
                        'tableId' => $tableId ?? 'yudisium-only-jurusan-table',
                    ])

                    <div class="yudisium-table-panel is-hidden" data-yudisium-table-panel>
                        <div class="yudisium-datatable-shell">
                            <table id="{{ $tableId ?? 'yudisium-only-jurusan-table' }}" class="table small-font table-striped table-hover table-sm yudisium-table js-yudisium-table js-yudisium-jurusan-table w-100" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th>Mahasiswa</th>
                                        <th class="text-center">Nilai</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jurusanRows as $row)
                                    @php
                                        $filterPass = $row['filter_pass'] ?? match ($row['display_state'] ?? '') {
                                            'complete' => (float) ($row['grade'] ?? 0) >= 61 ? 'lulus' : 'tidak-lulus',
                                            'partial' => 'belum-lengkap',
                                            default => 'belum-dinilai',
                                        };
                                        $filterLetter = $row['filter_letter'] ?? (string) ($row['letter_display'] ?? $row['letter'] ?? '');
                                    @endphp
                                    <tr
                                        data-yudisium-pass="{{ $filterPass }}"
                                        data-yudisium-letter="{{ $filterLetter }}"
                                        data-yudisium-display-state="{{ $row['display_state'] ?? '' }}"
                                    >
                                        <td>
                                            {{ $row['student_name'] }}
                                        </td>
                                        @include('report.partials.yudisium-grade-cell', ['row' => $row])
                                        <td>
                                            <div class="yudisium-notes">
                                                @foreach ($lectureForms as $form)
                                                <span class="yudisium-chip is-lecture">
                                                    {{ substr($form,-2) }} <span class="chip-value">{{ $row['lecture_forms'][$form] ?? 0 }}</span>
                                                </span>
                                                @endforeach
                                                <span class="yudisium-chip is-lecture is-actor">{{ $row['lecture_name'] }}</span>
                                            </div>
                                            @if (!empty($teacherForms))
                                            <div class="yudisium-notes break-line">
                                                @foreach ($teacherForms as $form)
                                                <span class="yudisium-chip is-teacher">
                                                    {{ substr($form,-2) }} <span class="chip-value">{{ $row['teacher_forms'][$form] ?? 0 }}</span>
                                                </span>
                                                @endforeach
                                                <span class="yudisium-chip is-teacher is-actor">{{ $row['teacher_name'] }}</span>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
