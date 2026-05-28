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

        .assessment-section-heading {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #526789;
            margin: 1rem 0 0.5rem;
            padding-bottom: 0.35rem;
            border-bottom: 2px solid rgba(82, 112, 154, 0.18);
        }

        #assessment-table thead th,
        [id^="assessment-table-"] thead th {
            background: linear-gradient(135deg, #edf4ff, #f6f9ff);
            border-bottom: 1px solid rgba(82, 112, 154, 0.22);
            color: #526789;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            font-size: 0.75rem;
        }

        #assessment-table tbody td,
        [id^="assessment-table-"] tbody td {
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

        body.dark [id^="assessment-table-"] thead th {
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
        @if ($focusPlpLabel ?? null)
            Penilaian {{ $focusPlpLabel }}
        @else
            Penilaian Kegiatan PLP
        @endif
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                        <span>
                            Rekap Penilaian Mahasiswa
                            @if ($focusPlpLabel ?? null)
                                <span class="badge bg-primary ms-1">{{ $focusPlpLabel }}</span>
                            @endif
                        </span>
                        <div class="d-flex flex-wrap gap-2">
                            @can('aktivitas/schoolassessments/plp-update')
                                <button type="button"
                                        id="btn-recalculate-grades"
                                        class="btn btn-modern btn-modern-outline btn-sm"
                                        data-url="{{ route('schoolassessments.only.recalculate-grades') }}"
                                        title="Hitung ulang nilai akhir semua mahasiswa bimbingan tahun {{ $activeYear }}">
                                    Hitung ulang nilai
                                </button>
                            @endcan
                            <a href="{{ route('dashboard') }}" class="btn btn-modern btn-modern-outline btn-sm">Dashboard</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="assessment-resume-main">
                        @if (count($plpTabs ?? []) > 1)
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @foreach ($plpTabs as $tab)
                                    <a href="{{ $tab['url'] }}"
                                       class="btn btn-sm {{ ($focusPlp ?? null) === $tab['order'] ? 'btn-primary' : 'btn-outline-secondary' }}">
                                        {{ $tab['label'] }} ({{ $tab['formCount'] }} form)
                                    </a>
                                @endforeach
                                @if ($focusPlp !== null)
                                    <a href="{{ route('schoolassessments.only.index') }}" class="btn btn-sm btn-outline-secondary">Semua PLP</a>
                                @endif
                            </div>
                        @endif
                        <div class="identity-card">
                            <div class="identity-head">
                                <div>
                                    <p class="identity-title">Identitas Penilai</p>
                                    <p class="identity-name">{{ $user->name ?? '-' }}</p>
                                    <p class="identity-meta">{{ $user->username ?? '-' }}</p>
                                </div>
                                <div class="summary-badges">
                                    <span class="badge-modern badge-modern-year">Tahun Aktif {{ $activeYear }}</span>
                                    <span class="badge-modern badge-modern-success">Mahasiswa {{ $totalMaps }}</span>
                                    @foreach ($sections as $plpOrder => $section)
                                        <span class="badge-modern badge-modern-neutral">{{ $plpOrder === 0 ? 'PLP' : 'PLP '.$plpOrder }}: {{ count($section['forms']) }} form</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div id="submit-status-resume" class="submit-status d-none" role="status" aria-live="polite">
                            <p class="submit-status-text" id="submit-status-resume-text"></p>
                            <span class="submit-status-badge" id="submit-status-resume-badge">BERHASIL</span>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-link btn-sm text-secondary ps-0 text-decoration-none"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapsePetunjuk"
                                    aria-expanded="false"
                                    aria-controls="collapsePetunjuk"
                                    style="font-size: 0.8rem; font-weight: 600; letter-spacing: 0.3px;">
                                <i class="ti-info-alt me-1"></i> Petunjuk Penilaian
                                <i class="ti-angle-down ms-1" style="font-size: 0.65rem;"></i>
                            </button>
                            <div class="collapse" id="collapsePetunjuk">
                                <div class="note-list mt-2">
                                    Rekap penilaian ini menyajikan informasi penilaian dari setiap mahasiswa dengan catatan sebagai berikut:
                                    <ol>
                                        <li>nilai 0 (tulisan merah) menandakan input nilai belum dilakukan</li>
                                        <li>nilai sudah masuk ke sistem jika keterangan nilai (walaupun nilai 0) berada pada tombol hijau</li>
                                        @foreach ($sections as $plpOrder => $section)
                                            <li>
                                                {{ count($sections) > 1 ? ($plpOrder === 0 ? 'PLP: ' : 'PLP '.$plpOrder.': ') : '' }}klik pada tombol skor untuk mulai menilai
                                                ({{ implode(' / ', array_map(fn ($f) => substr($f, -2), $section['forms'])) }})
                                            </li>
                                        @endforeach
                                        <li>nilai gabungan DPL &amp; GP = bobot GP × nilai GP + bobot DPL × nilai DPL (sesuai konfigurasi)</li>
                                        <li>
                                            keterangan huruf:<br>
                                            A (min. 85) · A- (min. 77) · B+ (min. 69) · B (min. 61) · B- (min. 53)<br>
                                            C+ (min. 45) · C (min. 37) · C- (min. 29) · D (min. 21) · E (di bawah 21)
                                        </li>
                                    </ol>
                                    <div class="legend-badges">
                                        <span class="badge-modern badge-modern-success">Nilai terisi</span>
                                        <span class="badge-modern badge-modern-danger">Nilai belum diisi</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="sections-wrapper">
                            @forelse ($sections as $plpOrder => $section)
                                @php
                                    $sectionLabel = \App\Models\Map::plpBucketLabel((int) $plpOrder);
                                    $showSectionHeading = ($focusPlp ?? null) === null && count($sections) > 1;
                                @endphp
                                @if ($showSectionHeading)
                                    <p class="assessment-section-heading">{{ $sectionLabel }}</p>
                                @endif
                                <div class="table-responsive mb-4" id="plp-section-{{ $plpOrder }}">
                                    <div class="dataTables_wrapper no-footer assessment-wrap">
                                        <table class="display dataTable no-footer" id="assessment-table-{{ $plpOrder }}" role="grid">
                                            <thead>
                                                <tr role="row">
                                                    <th>Mahasiswa</th>
                                                    @foreach ($section['forms'] as $formId)
                                                        <th>{{ substr($formId, -2) }}</th>
                                                    @endforeach
                                                    @role('dosen')
                                                        <th class="text-center">Nilai DPL</th>
                                                    @endrole
                                                    @role('guru')
                                                        <th class="text-center">Nilai GP</th>
                                                    @endrole
                                                    <th class="text-center">
                                                        @if ((int) $plpOrder === 0)
                                                            Nilai PLP
                                                        @elseif (count($sections) > 1 || ($focusPlp ?? null) !== null)
                                                            Nilai {{ $sectionLabel }}
                                                        @else
                                                            Nilai Gab (DPL & GP)
                                                        @endif
                                                    </th>
                                                    <th class="text-center">Huruf</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($section['maps'] as $map)
                                                <tr>
                                                    <td>{{ $map->students->name ?? '' }}</td>
                                                    @php
                                                        $totalSelfGradeNum = 0.0;
                                                        $totalSelfGradeDen = 0;
                                                    @endphp
                                                    @foreach ($section['forms'] as $formId)
                                                        @php
                                                            $ruleTimes = $section['formRuleTimes'][$formId] ?? 1;
                                                            $assessmentPlpOrder = $map->assessmentPlpOrderForBucket((int) $plpOrder);
                                                            $totalSelfGradeDen += $ruleTimes;
                                                            $occAssessments = App\Models\Assessment::where('form_id', $formId)
                                                                ->where('map_id', $map->id)
                                                                ->where('assessor', $section['assessor'])
                                                                ->where('plp_order', $assessmentPlpOrder)
                                                                ->get()
                                                                ->keyBy('form_order');
                                                            $occSum = 0.0;
                                                            $occCount = 0;
                                                            for ($fi = 1; $fi <= $ruleTimes; $fi++) {
                                                                $a = $occAssessments->get($fi);
                                                                if ($a) {
                                                                    $occSum += (float) $a->grade;
                                                                    $occCount++;
                                                                    $totalSelfGradeNum += (float) $a->grade;
                                                                }
                                                            }
                                                        @endphp
                                                        <td>
                                                            @if ($ruleTimes > 1)
                                                                @php
                                                                    $occAvg = $occCount > 0 ? round($occSum / $occCount, 0) : 0;
                                                                    $showUrl = route('schoolassessments.only.show', ['form_id' => $formId]) . '?plp=' . $plpOrder . '&map_id=' . $map->id;
                                                                @endphp
                                                                <a href="{{ $showUrl }}"
                                                                   class="btn btn-modern {{ $occCount > 0 ? 'btn-modern-success' : 'btn-modern-outline-danger' }} btn-sm mb-2">
                                                                    <small class="d-block lh-1" style="font-size:0.6rem;opacity:0.8">{{ substr($formId, -2) }}</small>
                                                                    {{ $occAvg }}
                                                                </a>
                                                            @else
                                                                @php $occAss = $occAssessments->get(1); @endphp
                                                                @if ($occAss)
                                                                    <button type="button"
                                                                        data-id="{{ $occAss->id }}"
                                                                        data-formid="{{ $formId }}"
                                                                        data-form_order="1"
                                                                        data-map_id="{{ $map->id }}"
                                                                        data-plp-bucket="{{ $plpOrder }}"
                                                                        data-jenis="edit"
                                                                        class="btn btn-modern btn-modern-success btn-sm mb-2 action">
                                                                        {{ round((float) $occAss->grade, 0) }}
                                                                    </button>
                                                                @else
                                                                    <button type="button"
                                                                        data-formid="{{ $formId }}"
                                                                        data-form_order="1"
                                                                        data-map_id="{{ $map->id }}"
                                                                        data-plp-bucket="{{ $plpOrder }}"
                                                                        data-jenis="add"
                                                                        class="btn btn-modern btn-modern-outline-danger btn-sm mb-2 action">
                                                                        0
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    @php
                                                        $selfGrade = $totalSelfGradeDen > 0
                                                            ? min(100, max(0, round($totalSelfGradeNum / $totalSelfGradeDen, 2)))
                                                            : 0;
                                                        $finalGrade = match ((int) $plpOrder) {
                                                            0 => $map->grade,
                                                            1 => $map->grade1,
                                                            2 => $map->grade2,
                                                            default => null,
                                                        };
                                                        $finalLetter = match ((int) $plpOrder) {
                                                            0 => $map->letter,
                                                            1 => $map->letter1,
                                                            2 => $map->letter2,
                                                            default => null,
                                                        };
                                                    @endphp
                                                    <td class="text-center">{{ $selfGrade ?: '' }}</td>
                                                    <td class="text-center">{{ $finalGrade !== null ? min(100, max(0, round((float) $finalGrade, 2))) : '—' }}</td>
                                                    <td class="text-center">{{ $finalLetter ?? '—' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted small py-3">
                                    @if (($focusPlp ?? null) !== null)
                                        Tidak ada data penilaian untuk <strong>{{ $focusPlpLabel ?? 'PLP' }}</strong> pada tahun {{ $activeYear }}.
                                        Pastikan mahasiswa bimbingan ikut PLP yang sesuai dan form sudah diatur di
                                        <a href="{{ route('plpfinalgraderules.index') }}">Sebaran Form</a>.
                                    @else
                                        Belum ada konfigurasi form penilaian untuk peran ini.
                                        Atur di <a href="{{ route('plpfinalgraderules.index') }}">Sebaran Form</a>.
                                    @endif
                                </p>
                            @endforelse
                        </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"></div>
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
        var initOnlyAssessmentResumeCrud = function(wrapperId) {
            const baseUrl = "{{ route('schoolassessments.only.index') }}";
            const focusPlp = @json($focusPlp);
            const reloadBaseUrl = focusPlp !== null ? `${baseUrl}?plp=${focusPlp}` : baseUrl;
            const createUrlTemplate = "{{ route('schoolassessments.only.create', ['form_id' => '__FORM__', 'form_order' => '__ORDER__', 'map_id' => '__MAP__']) }}";
            const editUrlTemplate = "{{ route('schoolassessments.only.edit', ['form_id' => '__FORM__', 'form_order' => '__ORDER__', 'map_id' => '__MAP__', 'schoolassessment' => '__ID__']) }}";

            const $modal = $('#modalAction');
            const modalElement = document.getElementById('modalAction');
            let lastViewportY = window.scrollY || window.pageYOffset || 0;

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
                } catch (e) {}

                try {
                    if (typeof $modal.modal === 'function') {
                        $modal.modal('show');
                        return;
                    }
                } catch (e) {}

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
                } catch (e) {}

                try {
                    if (typeof $modal.modal === 'function') {
                        $modal.modal('hide');
                        return;
                    }
                } catch (e) {}

                $modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true').removeAttr('aria-modal');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            };

            const restoreViewport = () => {
                window.requestAnimationFrame(() => {
                    window.scrollTo({ top: lastViewportY, left: 0, behavior: 'auto' });
                });
            };

            const appendPlpQuery = (url, bucket) => {
                if (bucket === undefined || bucket === null || bucket === '') {
                    return url;
                }
                const sep = url.includes('?') ? '&' : '?';
                return `${url}${sep}plp=${encodeURIComponent(bucket)}`;
            };

            const reloadSections = (callback) => {
                $(`#${wrapperId}`).load(`${reloadBaseUrl} #${wrapperId}`, function() {
                    restoreViewport();
                    if (typeof callback === 'function') {
                        callback();
                    }
                });
            };

            const showResumeSubmitStatus = (actionType, responseMessage) => {
                const statusText = actionType === 'update'
                    ? 'Update data penilaian berhasil disimpan.'
                    : 'Input data penilaian berhasil disimpan.';

                const $status = $('#submit-status-resume');
                $('#submit-status-resume-text').text(statusText);
                $status.removeClass('d-none');

                iziToast.success({
                    title: 'Berhasil',
                    message: responseMessage || statusText,
                    position: 'topRight',
                });
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
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                hideModal();
                                reloadSections(function() {
                                    showResumeSubmitStatus(actionType, response.message);
                                });
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

            $(`#${wrapperId}`)
                .off('click.onlyAssessmentResumeAction', '.action')
                .on('click.onlyAssessmentResumeAction', '.action', function() {
                    lastViewportY = window.scrollY || window.pageYOffset || 0;
                    const data = $(this).data();
                    const id = data.id;
                    const jenis = data.jenis;
                    const formid = data.formid;
                    const formOrder = data.form_order;
                    const mapId = data.map_id;
                    const plpBucket = data.plpBucket;

                    let actionUrl = jenis === 'add'
                        ? buildUrl(createUrlTemplate, { '__FORM__': formid, '__ORDER__': formOrder, '__MAP__': mapId })
                        : buildUrl(editUrlTemplate, { '__FORM__': formid, '__ORDER__': formOrder, '__MAP__': mapId, '__ID__': id });
                    actionUrl = appendPlpQuery(actionUrl, plpBucket);

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

        initOnlyAssessmentResumeCrud('assessment-resume-main');

        (function () {
            const btn = document.getElementById('btn-recalculate-grades');
            if (!btn) {
                return;
            }

            const focusPlp = @json($focusPlp);
            const reloadBaseUrl = focusPlp !== null
                ? "{{ route('schoolassessments.only.index') }}?plp=" + focusPlp
                : "{{ route('schoolassessments.only.index') }}";

            btn.addEventListener('click', function () {
                if (!window.confirm('Hitung ulang nilai akhir (PLP / PLP 1 / PLP 2) untuk semua mahasiswa bimbingan Anda pada tahun {{ $activeYear }}?')) {
                    return;
                }

                const originalText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Menghitung…';

                fetch(btn.getAttribute('data-url'), {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                })
                    .then((r) => r.json().catch(() => ({})).then((data) => ({ r, data })))
                    .then(({ r, data }) => {
                        if (!r.ok) {
                            throw new Error(data.message || 'Gagal menghitung ulang nilai.');
                        }
                        iziToast.success({
                            title: 'Berhasil',
                            message: data.message || 'Nilai akhir dihitung ulang.',
                            position: 'topRight',
                        });
                        window.location.href = reloadBaseUrl;
                    })
                    .catch((err) => {
                        iziToast.error({
                            title: 'Gagal',
                            message: err.message || 'Gagal menghitung ulang nilai.',
                            position: 'topRight',
                        });
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            });
        })();

        @if (isset($focusPlp) && $focusPlp !== null)
            document.addEventListener('DOMContentLoaded', function () {
                const target = document.getElementById('plp-section-{{ $focusPlp }}');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        @endif
    </script>
@endpush
