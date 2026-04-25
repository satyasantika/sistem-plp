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

        .summary-badges,
        .legend-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .legend-badges {
            margin-bottom: 12px;
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

        .badge-modern-danger {
            background: rgba(220, 53, 69, 0.16);
            color: #a32836;
            border-color: rgba(220, 53, 69, 0.28);
        }

        .badge-modern-neutral {
            background: rgba(108, 117, 125, 0.14);
            color: #4e5965;
            border-color: rgba(108, 117, 125, 0.24);
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

        .btn-modern-outline {
            border-color: rgba(74, 105, 148, 0.34);
            color: #264063;
            background: rgba(255, 255, 255, 0.62);
        }

        .btn-modern-success {
            color: #fff;
            background: linear-gradient(135deg, #12a36f, #0b7f57);
            box-shadow: 0 6px 14px rgba(18, 163, 111, 0.27);
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

        .note-list {
            margin-bottom: 12px;
            color: #2d4468;
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

        body.dark .identity-card {
            border-color: rgba(157, 185, 224, 0.24);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.95), rgba(17, 30, 48, 0.95));
        }

        body.dark .identity-title,
        body.dark .identity-meta,
        body.dark .note-list {
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

        body.dark .badge-modern-danger {
            background: rgba(220, 53, 69, 0.22);
            color: #ffc7cd;
            border-color: rgba(220, 53, 69, 0.36);
        }

        body.dark .badge-modern-neutral {
            background: rgba(130, 146, 166, 0.22);
            color: #d3deeb;
            border-color: rgba(130, 146, 166, 0.36);
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
    </style>
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Penilaian Kegiatan PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Rekap Penilaian Mahasiswa
                        <a href="{{ route('dashboard') }}" class="btn btn-modern btn-modern-outline float-end">Dashboard</a>
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
                                    <span class="badge-modern badge-modern-neutral">Form {{ count($forms) }}</span>
                                </div>
                            </div>
                        </div>

                        <div id="submit-status-resume" class="submit-status d-none" role="status" aria-live="polite">
                            <p class="submit-status-text" id="submit-status-resume-text"></p>
                            <span class="submit-status-badge" id="submit-status-resume-badge">BERHASIL</span>
                        </div>

                        <div class="note-list">
                            Rekap penilaian ini menyajikan informasi penilaian dari setiap mahasiswa dengan catatan sebagai berikut:
                            <ol>
                                <li>nilai 0 (tulisan merah) menandakan input nilai belum dilakukan</li>
                                <li>nilai sudah masuk ke sistem jika keterangan nilai (walaupun nilai 0) berada pada tombol hijau</li>
                                @role('dosen')
                                    <li>klik pada tombol setiap skor untuk mulai menilai (N2/N6/N7)</li>
                                    <li>nilai DPL = (N2 + N6 + N7) / 3</li>
                                @endrole
                                @role('guru')
                                    <li>klik pada tombol setiap skor untuk mulai menilai (N1/N3/N4/N5/N6/N7)</li>
                                    <li>nilai GP = (N1 + N3 + N4 + N5 + N6 + N7) / 6</li>
                                @endrole
                                <li>nilai gabungan DPL & GP = 60% nilai GP + 40% nilai DPL</li>
                                <li>
                                    ketarangan huruf sebagai berikut:<br>
                                    A (minimal 85) A- (minimal 77) B+ (minimal 69) B (minimal 61) B- (minimal 53) <br>C+ (minimal 45) C (minimal 37) C- (minimal 29) D (minimal 21) E (di bawah 21)
                                </li>
                            </ol>

                            <div class="legend-badges">
                                <span class="badge-modern badge-modern-success">Nilai terisi</span>
                                <span class="badge-modern badge-modern-danger">Nilai belum diisi</span>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer assessment-wrap">
                                <table class="display dataTable no-footer" id="assessment-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>Mahasiswa</th>
                                            {{-- PERULANGAN JENIS FORM --}}
                                            @foreach ($forms as $form) <th>{{ substr($form,-2) }}</th> @endforeach
                                            @role('dosen')
                                                <th class="text-center">Nilai DPL</th>
                                            @endrole
                                            @role('guru')
                                                <th class="text-center">Nilai GP</th>
                                            @endrole
                                            <th class="text-center">Nilai Gab <br>(DPL & GP)</th>
                                            <th class="text-center">Huruf</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($maps as $map)
                                        <tr>
                                            <td>
                                                {{ $map->students->name ?? '' }}
                                            </td>
                                            @php
                                                $count_form = 0;
                                                $total_grade = 0;
                                            @endphp
                                            @foreach ($forms as $form)
                                            {{-- PERULANGAN FORM YANG DINILAI --}}
                                            @php
                                                $assessor = 'guru';
                                                if(auth()->user()->hasrole('dosen')){
                                                    $assessor = 'dosen';
                                                }
                                                $form_times = App\Models\Form::find($form)->times;
                                                $assessments = App\Models\Assessment::where('form_id',$form)
                                                                                    ->where('map_id',$map->id)
                                                                                    ->where('assessor',$assessor)
                                                                                    ;
                                            @endphp
                                            <td>
                                                @if ($assessments->exists())
                                                @php
                                                    $assessmentRecord = $assessments->first();
                                                    $grade = $assessments->sum('grade');
                                                    $grade = round($grade/$form_times,0);
                                                @endphp
                                                @if ($form_times > 1)
                                                    <a
                                                        href="{{ route('schoolassessments.only.show',['form_id' => $form]) }}"
                                                        class="btn btn-modern btn-modern-success btn-sm mb-2">
                                                        {{ $grade }}
                                                    </a>
                                                @else
                                                    <button
                                                        type="button"
                                                        data-id="{{ $assessmentRecord->id }}"
                                                        data-formid="{{ $form }}"
                                                        data-form_order="1"
                                                        data-map_id="{{ $map->id }}"
                                                        data-jenis="edit"
                                                        class="btn btn-modern btn-modern-success btn-sm mb-2 action">
                                                        {{ $grade }}
                                                    </button>
                                                @endif
                                                @php
                                                    // hitung banyaknya form
                                                    $count_form += $assessments->count();
                                                    //hitung nilai total form
                                                    $total_grade += $grade;
                                                @endphp
                                                @else
                                                @if ($form_times > 1)
                                                    <a
                                                        href="{{ route('schoolassessments.only.show',['form_id' => $form]) }}"
                                                        class="btn btn-modern btn-modern-outline-danger btn-sm mb-2">
                                                        {{ 0 }}
                                                    </a>
                                                @else
                                                    <button
                                                        type="button"
                                                        data-formid="{{ $form }}"
                                                        data-form_order="1"
                                                        data-map_id="{{ $map->id }}"
                                                        data-jenis="add"
                                                        class="btn btn-modern btn-modern-outline-danger btn-sm mb-2 action">
                                                        {{ 0 }}
                                                    </button>
                                                @endif
                                                @endif
                                            </td>
                                            @endforeach
                                            @php
                                                if (auth()->user()->hasrole('dosen'))
                                                {
                                                    $forms = ['2024N2','2024N6','2024N7'];
                                                } else {
                                                    $forms = ['2024N1','2024N3','2024N4','2024N5','2024N6','2024N7'];
                                                }
                                                // penilaian dari guru
                                                $assessment_by_teacher = App\Models\Assessment::where([
                                                    'assessor'=>'guru',
                                                    'map_id'=>$map->id,
                                                    ])
                                                    ->whereIn('form_id',$forms)
                                                    ->sum('grade');
                                                    $teacher_form_times = App\Models\Form::whereIn('id',$forms)->sum('times');
                                                    $assessment = $assessment_by_teacher/$teacher_form_times;

                                                    $assessor = 'guru';
                                                    if(auth()->user()->hasrole('dosen')){
                                                        $assessor = 'dosen';
                                                        // penilaian dari dosen
                                                        $assessment_by_lecture = App\Models\Assessment::where([
                                                                                            'assessor'=>'dosen',
                                                                                            'map_id'=>$map->id,
                                                                                        ])
                                                                                        ->whereIn('form_id',$forms)
                                                                                        ->sum('grade');
                                                        $lecture_form_times = App\Models\Form::whereIn('id',$forms)->sum('times');
                                                        $assessment = round($assessment_by_lecture/$lecture_form_times,0);
                                                }
                                                $assessments = App\Models\Assessment::where('assessor',$assessor)
                                                                                    ->where('map_id',$map->id)
                                                                                    ;
                                            @endphp
                                                @if ($assessments->exists())
                                                    <td class="text-center">{{ round($assessment,2) }}</td>
                                                    <td class="text-center">{{ round($map->grade,2) }}</td>
                                                    <td class="text-center">{{ $map->letter }}</td>
                                                @else
                                                <td></td>
                                                <td></td>
                                                @endif
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
        var initOnlyAssessmentResumeCrud = function(table) {
            const baseUrl = "{{ route('schoolassessments.only.index') }}";
            const createUrlTemplate = "{{ route('schoolassessments.only.create', ['form_id' => '__FORM__', 'form_order' => '__ORDER__', 'map_id' => '__MAP__']) }}";
            const editUrlTemplate = "{{ route('schoolassessments.only.edit', ['form_id' => '__FORM__', 'form_order' => '__ORDER__', 'map_id' => '__MAP__', 'schoolassessment' => '__ID__']) }}";

            const $modal = $('#modalAction');
            const modalElement = document.getElementById('modalAction');

            const buildUrl = (template, replacements) => {
                let url = template;
                Object.entries(replacements).forEach(([key, value]) => {
                    url = url.replace(key, encodeURIComponent(String(value)));
                });
                return url;
            };

            const showModal = () => {
                try {
                    if (window.bootstrap && window.bootstrap.Modal && modalElement) {
                        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                try {
                    if (typeof $modal.modal === 'function') {
                        $modal.modal('show');
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                $modal.addClass('show').css('display', 'block').attr('aria-modal', 'true').removeAttr('aria-hidden');
                if (!$('.modal-backdrop').length) {
                    $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                }
                $('body').addClass('modal-open');
            };

            const hideModal = () => {
                try {
                    if (window.bootstrap && window.bootstrap.Modal && modalElement) {
                        window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                try {
                    if (typeof $modal.modal === 'function') {
                        $modal.modal('hide');
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                $modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true').removeAttr('aria-modal');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            };

            const bindStore = () => {
                $(document)
                    .off('submit.onlyAssessmentResumeStore', '#formAction')
                    .on('submit.onlyAssessmentResumeStore', '#formAction', function(e) {
                        e.preventDefault();
                        const _form = this;
                        const formData = new FormData(_form);
                        const url = this.getAttribute('action');
                        const isUpdate = !!_form.querySelector("input[name='_method'][value='PUT']");
                        const actionType = isUpdate ? 'update' : 'create';

                        $('.text-danger.text-small').remove();

                        $.ajax({
                            method: 'POST',
                            url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                hideModal();
                                iziToast.success({
                                    title: 'Saved!',
                                    message: response.message,
                                    position: 'topRight',
                                });
                                const redirectUrl = `${baseUrl}?submit_status=success&submit_action=${actionType}`;
                                window.location.href = redirectUrl;
                            },
                            error: function(response) {
                                const errors = response.responseJSON?.errors;

                                if (errors) {
                                    for (const [key, value] of Object.entries(errors)) {
                                        $(`[name='${key}']`).parent().append(`<span class='text-danger text-small'>${value}</span>`);
                                    }
                                    return;
                                }

                                iziToast.error({
                                    title: 'Error',
                                    message: 'Form penilaian gagal disimpan.',
                                    position: 'topRight',
                                });
                            },
                        });
                    });
            };

            $(`#${table}`)
                .off('click.onlyAssessmentResumeAction', '.action')
                .on('click.onlyAssessmentResumeAction', '.action', function() {
                    const data = $(this).data();
                    const id = data.id;
                    const jenis = data.jenis;
                    const formid = data.formid;
                    const formOrder = data.form_order;
                    const mapId = data.map_id;

                    const actionUrl = jenis === 'add'
                        ? buildUrl(createUrlTemplate, {
                            '__FORM__': formid,
                            '__ORDER__': formOrder,
                            '__MAP__': mapId,
                        })
                        : buildUrl(editUrlTemplate, {
                            '__FORM__': formid,
                            '__ORDER__': formOrder,
                            '__MAP__': mapId,
                            '__ID__': id,
                        });

                    $.ajax({
                        method: 'GET',
                        url: actionUrl,
                        success: function(response) {
                            $modal.find('.modal-dialog').html(response);
                            showModal();
                            bindStore();
                        },
                        error: function() {
                            iziToast.error({
                                title: 'Error',
                                message: 'Form penilaian tidak bisa dimuat.',
                                position: 'topRight',
                            });
                        },
                    });
                });
        };

        var showResumeSubmitStatusFromQuery = function() {
            const params = new URLSearchParams(window.location.search);
            const submitStatus = params.get('submit_status');
            const submitAction = params.get('submit_action');

            if (submitStatus !== 'success') {
                return;
            }

            const statusText = submitAction === 'update'
                ? 'Update data penilaian berhasil disimpan.'
                : 'Input data penilaian berhasil disimpan.';

            const $status = $('#submit-status-resume');
            $('#submit-status-resume-text').text(statusText);
            $status.removeClass('d-none');

            iziToast.success({
                title: 'Berhasil',
                message: statusText,
                position: 'topRight',
            });

            params.delete('submit_status');
            params.delete('submit_action');
            const cleanedQuery = params.toString();
            const cleanedUrl = `${window.location.pathname}${cleanedQuery ? `?${cleanedQuery}` : ''}`;
            window.history.replaceState({}, document.title, cleanedUrl);
        };

        initOnlyAssessmentResumeCrud('assessment-table');
        showResumeSubmitStatusFromQuery();
    </script>
@endpush
