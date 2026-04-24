<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian PLP {{ $plp_order }} Jurusan {{ auth()->user()->subjects->departement }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive yudisium-table-wrap">
                            <table id="{{ $tableId ?? 'yudisium-jurusan-table' }}" class="table small-font table-striped table-hover table-sm yudisium-table js-yudisium-table" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th>Mahasiswa</th>
                                        <th class="text-center">Nilai</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jurusanRows as $row)
                                    <tr>
                                        <td>
                                            {{ $row['student_name'] }}
                                        </td>
                                        @if ($row['has_assessment'])
                                        <td class="text-center">
                                            <span class="badge bg-{{ $row['status'] }}">
                                                {{ $row['letter'] }} <span class="badge bg-light text-dark rounded-pill">{{ round($row['grade'],2) }}</span>
                                            </span>
                                        </td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>
                                            <div class="yudisium-notes">
                                                @foreach ($lectureForms as $form)
                                                <span class="yudisium-chip is-lecture">
                                                    {{ substr($form,-2) }} <span class="chip-value">{{ $row['lecture_forms'][$form] ?? 0 }}</span>
                                                </span>
                                                @endforeach
                                                <span class="yudisium-chip is-lecture is-actor">{{ $row['lecture_name'] }}</span>
                                            </div>
                                            @if ($plp_order == 2)
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
