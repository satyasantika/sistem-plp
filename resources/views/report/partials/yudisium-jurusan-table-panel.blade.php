<div class="yudisium-form-legend" aria-hidden="true">
    <span class="yudisium-form-legend-item">
        <span class="yudisium-form-legend-swatch is-empty"></span> Belum dinilai
    </span>
    <span class="yudisium-form-legend-item">
        <span class="yudisium-form-legend-swatch is-partial"></span> Belum lengkap
    </span>
    <span class="yudisium-form-legend-item">
        <span class="yudisium-form-legend-swatch is-filled-lecture"></span> Dosen — sudah dinilai
    </span>
    @if (!empty($teacherForms))
        <span class="yudisium-form-legend-item">
            <span class="yudisium-form-legend-swatch is-filled-teacher"></span> Guru — sudah dinilai
        </span>
    @endif
</div>
<div class="yudisium-datatable-shell">
    <table id="{{ $tableId ?? 'yudisium-jurusan-table' }}" class="table small-font table-striped table-hover table-sm yudisium-table js-yudisium-table js-yudisium-jurusan-table w-100" role="grid">
        <thead>
            <tr role="row">
                <th>Mahasiswa</th>
                <th class="text-center">Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jurusanRows as $row)
            <tr
                data-yudisium-pass="{{ $row['filter_pass'] ?? 'belum-dinilai' }}"
                data-yudisium-letter="{{ $row['filter_letter'] ?? '' }}"
                data-yudisium-display-state="{{ $row['display_state'] ?? '' }}"
            >
                <td>{{ $row['student_name'] }}</td>
                @include('report.partials.yudisium-grade-cell', ['row' => $row])
                <td>
                    <div class="yudisium-notes">
                        @include('report.partials.yudisium-form-chips', [
                            'forms' => $lectureForms,
                            'formStatuses' => $row['lecture_form_status'] ?? [],
                            'formGrades' => $row['lecture_forms'] ?? [],
                            'chipRole' => 'lecture',
                        ])
                        <span class="yudisium-chip is-lecture is-actor is-form-filled" title="Dosen pembimbing">{{ $row['lecture_name'] }}</span>
                    </div>
                    @if (!empty($teacherForms))
                        <div class="yudisium-notes break-line">
                            @include('report.partials.yudisium-form-chips', [
                                'forms' => $teacherForms,
                                'formStatuses' => $row['teacher_form_status'] ?? [],
                                'formGrades' => $row['teacher_forms'] ?? [],
                                'chipRole' => 'teacher',
                            ])
                            <span class="yudisium-chip is-teacher is-actor is-form-filled" title="Guru pamong">{{ $row['teacher_name'] }}</span>
                        </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
