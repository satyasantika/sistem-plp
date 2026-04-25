@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    <style>
        .identity-card {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.96), rgba(245, 250, 255, 0.96));
            padding: 14px;
            margin-bottom: 16px;
        }

        .identity-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .identity-title {
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #5f7394;
            margin: 0;
            font-weight: 700;
        }

        .identity-name {
            margin: 0;
            font-size: 1.06rem;
            font-weight: 700;
            color: #233754;
        }

        .identity-meta {
            margin: 2px 0 0;
            color: #6a7f9e;
            font-size: 0.83rem;
        }

        .summary-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .badge-modern {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.33rem 0.62rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.28px;
            border: 1px solid transparent;
        }

        .badge-modern-year {
            background: rgba(23, 162, 184, 0.16);
            color: #0d7283;
            border-color: rgba(23, 162, 184, 0.26);
        }

        .badge-modern-success {
            background: rgba(24, 151, 105, 0.16);
            color: #0f7e59;
            border-color: rgba(24, 151, 105, 0.28);
        }

        .badge-modern-warning {
            background: rgba(245, 158, 11, 0.18);
            color: #96600a;
            border-color: rgba(245, 158, 11, 0.28);
        }

        .btn-modern {
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 0.42rem 0.86rem;
            border: 1px solid transparent;
            transition: transform 0.18s ease, box-shadow 0.22s ease, filter 0.2s ease;
        }

        .btn-modern:hover,
        .btn-modern:focus {
            transform: translateY(-1px);
            filter: saturate(1.08);
        }

        .btn-modern-primary {
            color: #fff;
            background: linear-gradient(135deg, #2476f3, #1759c5);
            box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
        }

        .btn-modern-success {
            color: #fff;
            background: linear-gradient(135deg, #12a36f, #0b7f57);
            box-shadow: 0 6px 14px rgba(18, 163, 111, 0.27);
        }

        .btn-modern-outline {
            border-color: rgba(74, 105, 148, 0.34);
            color: #264063;
            background: rgba(255, 255, 255, 0.62);
        }

        .btn-modern-outline-danger {
            color: #b53143;
            border-color: rgba(220, 53, 69, 0.42);
            background: rgba(255, 245, 246, 0.84);
        }

        .assessment-wrap {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        #assessment-table thead th {
            background: linear-gradient(135deg, #edf4ff, #f6f9ff);
            border-bottom: 1px solid rgba(82, 112, 154, 0.22);
            color: #526789;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            font-size: 0.75rem;
        }

        #assessment-table tbody td {
            vertical-align: top;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .submit-status {
            border: 1px solid rgba(24, 151, 105, 0.28);
            border-radius: 12px;
            background: linear-gradient(145deg, rgba(232, 251, 244, 0.9), rgba(244, 255, 251, 0.9));
            color: #125f45;
            padding: 10px 12px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .submit-status-text {
            margin: 0;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.2px;
        }

        .submit-status-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.24rem 0.58rem;
            font-size: 0.69rem;
            font-weight: 700;
            letter-spacing: 0.25px;
            background: rgba(24, 151, 105, 0.18);
            color: #0f7250;
            border: 1px solid rgba(24, 151, 105, 0.25);
        }

        .single-student-panel {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(245, 250, 255, 0.96));
            padding: 14px;
            margin-bottom: 16px;
        }

        .single-student-title {
            margin: 0 0 4px;
            font-size: 0.86rem;
            text-transform: uppercase;
            letter-spacing: 0.36px;
            color: #58719a;
            font-weight: 700;
        }

        .single-student-name {
            margin: 0;
            font-size: 1.08rem;
            font-weight: 800;
            color: #233754;
        }

        .single-student-meta {
            margin: 3px 0 0;
            color: #6a7f9e;
            font-size: 0.83rem;
        }

        .single-student-note {
            margin: 10px 0 0;
            color: #345175;
            font-size: 0.83rem;
        }

        .focus-assessment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 14px;
        }

        .focus-assessment-card {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 16px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.98), rgba(245, 250, 255, 0.98));
            padding: 14px;
            box-shadow: 0 10px 24px rgba(37, 56, 84, 0.08);
        }

        .focus-assessment-order {
            margin: 0 0 6px;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.36px;
            color: #58719a;
            font-weight: 700;
        }

        .focus-assessment-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: #223653;
        }

        .focus-assessment-caption {
            margin: 6px 0 12px;
            color: #6a7f9e;
            font-size: 0.82rem;
            min-height: 38px;
        }

        .focus-assessment-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .focus-assessment-state {
            font-size: 0.79rem;
            font-weight: 700;
            color: #526789;
        }

        body.dark .identity-card {
            border-color: rgba(157, 185, 224, 0.24);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.95), rgba(17, 30, 48, 0.95));
        }

        body.dark .identity-title,
        body.dark .identity-meta {
            color: #a8bddd;
        }

        body.dark .identity-name {
            color: #e2ecff;
        }

        body.dark .assessment-wrap {
            border-color: rgba(157, 185, 224, 0.24);
            background: rgba(15, 26, 42, 0.8);
        }

        body.dark #assessment-table thead th {
            background: linear-gradient(135deg, rgba(34, 49, 73, 0.92), rgba(25, 39, 61, 0.92));
            color: #b8cbea;
            border-bottom-color: rgba(157, 185, 224, 0.24);
        }

        body.dark .badge-modern-year {
            background: rgba(76, 194, 211, 0.2);
            color: #bdebf2;
            border-color: rgba(76, 194, 211, 0.34);
        }

        body.dark .badge-modern-success {
            background: rgba(24, 151, 105, 0.2);
            color: #bdeedc;
            border-color: rgba(24, 151, 105, 0.34);
        }

        body.dark .badge-modern-warning {
            background: rgba(245, 158, 11, 0.22);
            color: #ffe6b5;
            border-color: rgba(245, 158, 11, 0.36);
        }

        body.dark .btn-modern-outline {
            border-color: rgba(146, 182, 230, 0.45);
            color: #cfe3ff;
            background: rgba(43, 66, 103, 0.36);
        }

        body.dark .btn-modern-outline-danger {
            color: #ffc7cd;
            border-color: rgba(220, 53, 69, 0.48);
            background: rgba(97, 28, 38, 0.35);
        }

        body.dark .submit-status {
            border-color: rgba(78, 203, 153, 0.34);
            background: linear-gradient(145deg, rgba(21, 60, 48, 0.72), rgba(18, 44, 36, 0.72));
            color: #c7f2df;
        }

        body.dark .submit-status-badge {
            background: rgba(66, 191, 144, 0.26);
            color: #c6f6e3;
            border-color: rgba(76, 211, 159, 0.34);
        }

        body.dark .single-student-panel {
            border-color: rgba(157, 185, 224, 0.24);
            background: linear-gradient(145deg, rgba(24, 37, 57, 0.95), rgba(17, 30, 48, 0.95));
        }

        body.dark .single-student-title,
        body.dark .single-student-meta,
        body.dark .single-student-note {
            color: #abc0e0;
        }

        body.dark .single-student-name {
            color: #e2ecff;
        }

        body.dark .focus-assessment-card {
            border-color: rgba(157, 185, 224, 0.24);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.98), rgba(17, 30, 48, 0.98));
            box-shadow: none;
        }

        body.dark .focus-assessment-order,
        body.dark .focus-assessment-caption,
        body.dark .focus-assessment-state {
            color: #abc0e0;
        }

        body.dark .focus-assessment-title {
            color: #e2ecff;
        }
    </style>
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Penilaian PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Penilaian {{ substr($form_id,-2) }}
                        <a href="{{ route('schoolassessments.only.index') }}" class="btn btn-modern btn-modern-outline float-end">Lihat Rekap Penilaian</a>
                    </div>
                    <div class="card-body">
                        <div class="identity-card">
                            <div class="identity-head">
                                <div>
                                    <p class="identity-title">Identitas Penilai</p>
                                    <p class="identity-name">{{ $user->name ?? '-' }}</p>
                                    <p class="identity-meta">{{ $user->username ?? '-' }}</p>
                                </div>
                                <div class="summary-badges">
                                    <span class="badge-modern badge-modern-year">Tahun Aktif {{ $activeYear }}</span>
                                    <span class="badge-modern badge-modern-success">Mahasiswa {{ $maps->count() }}</span>
                                    <span class="badge-modern badge-modern-warning">Form {{ substr($form_id,-2) }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="submit-status-assessment" class="submit-status d-none" role="status" aria-live="polite">
                            <p class="submit-status-text" id="submit-status-assessment-text"></p>
                            <span class="submit-status-badge" id="submit-status-assessment-badge">BERHASIL</span>
                        </div>

                        @if (!empty($isFocusedAssessment) && $maps->count() === 1)
                            @php
                                $singleMap = $maps->first();
                            @endphp
                            <div class="single-student-panel">
                                <p class="single-student-title">Mode Fokus Penilaian Form</p>
                                <p class="single-student-name">{{ $singleMap->students->name ?? '-' }}</p>
                                <p class="single-student-meta">{{ $singleMap->schools->name ?? '-' }} • {{ $singleMap->subjects->name ?? '-' }}</p>
                                <p class="single-student-note">Silakan klik pada tombol angka untuk menilai, belum diisi (merah), sudah diisi (hijau)</p>
                            </div>
                        @endif

                        <div id="assessment-table">
                            @php
                                $form_times = App\Models\Form::find($form_id)->times;
                                $assessorType = auth()->user()->hasrole('guru') ? 'guru' : 'dosen';
                            @endphp

                            @if (!empty($isFocusedAssessment) && $maps->count() === 1)
                                @php
                                    $singleMap = $maps->first();
                                @endphp
                                <div class="focus-assessment-grid">
                                    @for ($i = 0; $i < $form_times; $i++)
                                        @php
                                            $attemptOrder = $i + 1;
                                            $attemptLabel = substr($form_id, -2) == 'N3'
                                                ? 'Perangkat ke-' . $attemptOrder
                                                : (substr($form_id, -2) == 'N5' ? 'Tampil ke-' . $attemptOrder : 'Nilai ' . substr($form_id, -2));
                                            $assessmentRecord = App\Models\Assessment::where([
                                                'assessor' => $assessorType,
                                                'map_id' => $singleMap->id,
                                                'form_id' => $form_id,
                                                'form_order' => $attemptOrder,
                                            ])->first();
                                        @endphp
                                        <div class="focus-assessment-card">
                                            <p class="focus-assessment-order">{{ $attemptLabel }}</p>
                                            <p class="focus-assessment-title">{{ $assessmentRecord?->grade ?? 0 }}</p>
                                            <p class="focus-assessment-caption">
                                                {{ $assessmentRecord ? 'Nilai sudah tersimpan dan siap diperbarui bila diperlukan.' : 'Belum ada nilai tersimpan untuk sesi ini.' }}
                                            </p>
                                            <div class="focus-assessment-action">
                                                <span class="focus-assessment-state">{{ $assessmentRecord ? 'Sudah dinilai' : 'Belum dinilai' }}</span>
                                                @if ($assessmentRecord)
                                                    @can('aktivitas/schoolassessments/plp-update')
                                                        <button
                                                            type="button"
                                                            data-id="{{ $assessmentRecord->id }}"
                                                            data-formid="{{ $form_id }}"
                                                            data-form_order="{{ $attemptOrder }}"
                                                            data-map_id="{{ $singleMap->id }}"
                                                            data-jenis="edit"
                                                            class="btn btn-modern btn-modern-success btn-sm action"
                                                        >{{ $assessmentRecord->grade }}</button>
                                                    @endcan
                                                @else
                                                    <button
                                                        type="button"
                                                        data-formid="{{ $form_id }}"
                                                        data-form_order="{{ $attemptOrder }}"
                                                        data-map_id="{{ $singleMap->id }}"
                                                        data-jenis="add"
                                                        class="btn btn-modern btn-modern-outline-danger btn-sm action"
                                                    >0</button>
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            @else
                                <div class="table-responsive">
                                    <div id="role-table_wrapper" class="dataTables_wrapper no-footer assessment-wrap">
                                        <table class="display dataTable no-footer" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>Mahasiswa</th>
                                                    @for ($i = 0; $i < $form_times; $i++)
                                                        @if (substr($form_id,-2) == 'N3')
                                                        <th>Perangkat ke-{{ $i+1 }}</th>
                                                        @elseif (substr($form_id,-2) == 'N5')
                                                        <th>Tampil ke-{{ $i+1 }}</th>
                                                        @else
                                                        <th>Nilai {{ substr($form_id,-2) }}</th>
                                                        @endif
                                                    @endfor
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($maps as $map)
                                                <tr>
                                                    <td>
                                                        {{ $map->students->name ?? '' }}
                                                    </td>
                                                    @for ($i = 0; $i < $form_times; $i++)
                                                    <td>
                                                        @php
                                                        $assessment = App\Models\Assessment::where([
                                                            'assessor' => $assessorType,
                                                            'map_id' => $map->id,
                                                            'form_id' => $form_id,
                                                            'form_order' => $i+1
                                                        ]);
                                                        @endphp
                                                        @if ($assessment->exists())
                                                        @php
                                                            $assessmentRecord = $assessment->first();
                                                        @endphp
                                                        @can('aktivitas/schoolassessments/plp-update')
                                                        <button type="button"
                                                            data-id="{{ $assessmentRecord->id }}"
                                                            data-formid="{{ $form_id }}"
                                                            data-form_order="{{ $i+1 }}"
                                                            data-map_id="{{ $map->id }}"
                                                            data-jenis="edit"
                                                            class="btn btn-modern btn-modern-success btn-sm mb-2 action"
                                                        >{{ $assessmentRecord->grade }}</button>
                                                        @endcan
                                                        @else
                                                        <button
                                                            type="button"
                                                            data-formid="{{ $form_id }}"
                                                            data-form_order="{{ $i+1 }}"
                                                            data-map_id="{{ $map->id }}"
                                                            data-jenis="add"
                                                            class="btn btn-modern btn-modern-outline-danger btn-sm mb-2 action"
                                                        >0</button>
                                                        @endif
                                                    </td>
                                                    @endfor
                                                </tr>
                                                @empty
                                                <div class="alert alert-info">Belum ada catatan</div>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        window.showOnlyAssessmentSubmitStatus = function(actionType, responseMessage) {
            const text = actionType === 'update'
                ? 'Update data penilaian berhasil disimpan.'
                : 'Input data penilaian berhasil disimpan.';

            const $status = $('#submit-status-assessment');
            $('#submit-status-assessment-text').text(text);
            $status.removeClass('d-none');

            iziToast.success({
                title: 'Berhasil',
                message: responseMessage || text,
                position: 'topRight',
            });
        };
    </script>
    @include('partials.datatables.assessment')
    <script>
        crudDataTables('assessment-table')
    </script>
@endpush
