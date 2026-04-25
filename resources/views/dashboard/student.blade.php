@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .student-shell {
            display: grid;
            gap: 18px;
        }

        .student-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }

        .student-card {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 18px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.97), rgba(245, 250, 255, 0.97));
            box-shadow: 0 16px 36px rgba(35, 55, 84, 0.08);
            overflow: hidden;
        }

        .student-card-header {
            padding: 16px 18px 10px;
            border-bottom: 1px solid rgba(82, 112, 154, 0.16);
            background: linear-gradient(135deg, rgba(237, 244, 255, 0.88), rgba(247, 250, 255, 0.88));
        }

        .student-card-header h5,
        .student-card-header h6 {
            margin: 0;
            color: #223653;
            font-weight: 800;
        }

        .student-card-subtitle {
            margin-top: 6px;
            color: #6980a2;
            font-size: 0.85rem;
        }

        .student-card-body {
            padding: 18px;
        }

        .student-profile {
            display: grid;
            gap: 18px;
        }

        .student-profile-head {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            align-items: start;
        }

        .student-kicker {
            margin: 0 0 6px;
            color: #5f7394;
            text-transform: uppercase;
            letter-spacing: 0.38px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .student-name {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 800;
            color: #1f3553;
        }

        .student-meta {
            margin: 4px 0 0;
            color: #6a7f9e;
            font-size: 0.86rem;
        }

        .student-badges,
        .student-action-list,
        .student-download-list,
        .subject-groups,
        .subject-member-list {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .student-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.34rem 0.68rem;
            font-size: 0.73rem;
            font-weight: 700;
            letter-spacing: 0.26px;
            border: 1px solid transparent;
        }

        .student-badge-info {
            background: rgba(23, 162, 184, 0.14);
            color: #0d7283;
            border-color: rgba(23, 162, 184, 0.24);
        }

        .student-badge-primary {
            background: rgba(36, 118, 243, 0.12);
            color: #1759c5;
            border-color: rgba(36, 118, 243, 0.22);
        }

        .student-badge-neutral {
            background: rgba(108, 117, 125, 0.12);
            color: #4e5965;
            border-color: rgba(108, 117, 125, 0.22);
        }

        .student-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
        }

        .student-info-item {
            border: 1px solid rgba(82, 112, 154, 0.16);
            border-radius: 14px;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.76);
        }

        .student-info-label {
            margin: 0 0 4px;
            color: #62789a;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.32px;
            font-weight: 700;
        }

        .student-info-value {
            margin: 0;
            color: #223653;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.45;
        }

        .student-contact {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .student-wa {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 999px;
            color: #fff;
            background: linear-gradient(135deg, #16b978, #0c8d5c);
            box-shadow: 0 8px 18px rgba(18, 163, 111, 0.24);
        }

        .student-action-btn,
        .student-download-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.48rem 0.92rem;
            font-size: 0.77rem;
            font-weight: 700;
            letter-spacing: 0.26px;
            text-decoration: none;
            transition: transform 0.18s ease, filter 0.2s ease, box-shadow 0.2s ease;
        }

        .student-action-btn:hover,
        .student-download-btn:hover {
            transform: translateY(-1px);
            filter: saturate(1.06);
            text-decoration: none;
        }

        .student-action-primary {
            color: #fff;
            background: linear-gradient(135deg, #2476f3, #1759c5);
            box-shadow: 0 8px 18px rgba(36, 118, 243, 0.24);
        }

        .student-action-success {
            color: #fff;
            background: linear-gradient(135deg, #15a56f, #0b7f57);
            box-shadow: 0 8px 18px rgba(18, 163, 111, 0.22);
        }

        .student-download-btn {
            color: #244063;
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(74, 105, 148, 0.28);
        }

        .student-download-note {
            margin: 0;
            color: #5f7394;
            font-size: 0.84rem;
            line-height: 1.55;
        }

        .subject-groups {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 14px;
        }

        .subject-card {
            border: 1px solid rgba(82, 112, 154, 0.18);
            border-radius: 16px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.98), rgba(247, 251, 255, 0.98));
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .subject-card-head {
            display: flex;
            justify-content: space-between;
            align-items: start;
            gap: 10px;
        }

        .subject-card-title {
            margin: 0;
            color: #223653;
            font-size: 1rem;
            font-weight: 800;
        }

        .subject-card-count {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.24rem 0.58rem;
            background: rgba(36, 118, 243, 0.12);
            color: #1759c5;
            font-size: 0.71rem;
            font-weight: 700;
        }

        .subject-member {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 12px;
            border: 1px solid rgba(82, 112, 154, 0.14);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.8);
        }

        .subject-member-name {
            margin: 0;
            color: #244063;
            font-size: 0.9rem;
            font-weight: 700;
            line-height: 1.4;
        }

        .subject-member-meta {
            margin: 2px 0 0;
            color: #6a7f9e;
            font-size: 0.78rem;
        }

        .status-stack {
            display: grid;
            gap: 12px;
        }

        .status-map-card {
            border: 1px solid rgba(82, 112, 154, 0.16);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.82);
            padding: 14px;
            display: grid;
            gap: 12px;
        }

        .status-map-title {
            margin: 0;
            color: #223653;
            font-size: 0.96rem;
            font-weight: 800;
        }

        .status-map-meta {
            margin: 4px 0 0;
            color: #6980a2;
            font-size: 0.8rem;
        }

        .status-item {
            display: grid;
            gap: 8px;
            padding: 12px;
            border-radius: 12px;
            border: 1px solid rgba(82, 112, 154, 0.14);
            background: rgba(255, 255, 255, 0.78);
        }

        .status-item-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .status-item-label {
            margin: 0;
            color: #244063;
            font-size: 0.84rem;
            font-weight: 800;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.26rem 0.62rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.24px;
            border: 1px solid transparent;
        }

        .status-pill-done {
            background: rgba(18, 163, 111, 0.16);
            color: #0f7e59;
            border-color: rgba(18, 163, 111, 0.28);
        }

        .status-pill-progress {
            background: rgba(245, 158, 11, 0.16);
            color: #96600a;
            border-color: rgba(245, 158, 11, 0.28);
        }

        .status-pill-pending {
            background: rgba(220, 53, 69, 0.14);
            color: #a32836;
            border-color: rgba(220, 53, 69, 0.24);
        }

        .status-item-detail {
            margin: 0;
            color: #5f7394;
            font-size: 0.82rem;
            line-height: 1.55;
        }

        .status-item-progress {
            margin: 0;
            color: #3a5276;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .status-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(78px, 1fr));
            gap: 7px;
        }

        .status-form-chip {
            display: grid;
            gap: 3px;
            padding: 8px 9px 7px;
            border-radius: 10px;
            border: 1px solid rgba(82, 112, 154, 0.14);
            background: rgba(255, 255, 255, 0.84);
        }

        .status-form-code {
            margin: 0;
            color: #233754;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 0.16px;
        }

        .status-form-label {
            margin: 0;
            font-size: 0.72rem;
            font-weight: 700;
            line-height: 1.35;
        }

        .status-form-chip-done {
            background: rgba(18, 163, 111, 0.14);
            border-color: rgba(18, 163, 111, 0.38);
        }

        .status-form-chip-done .status-form-code {
            color: #0a6b47;
        }

        .status-form-chip-done .status-form-label {
            color: #0f7e59;
        }

        .status-form-chip-done .status-form-icon {
            color: #12a36f;
        }

        .status-form-chip-pending {
            background: rgba(220, 53, 69, 0.1);
            border-color: rgba(220, 53, 69, 0.3);
        }

        .status-form-chip-pending .status-form-code {
            color: #8a1f2c;
        }

        .status-form-chip-pending .status-form-label {
            color: #a32836;
        }

        .status-form-chip-pending .status-form-icon {
            color: #dc3545;
        }

        .status-form-icon {
            font-size: 0.72rem;
            margin-right: 2px;
        }

        .student-empty {
            border-radius: 14px;
            padding: 14px 16px;
            background: rgba(23, 162, 184, 0.12);
            color: #0d7283;
            font-weight: 700;
        }

        @media (max-width: 991.98px) {
            .student-grid {
                grid-template-columns: 1fr;
            }
        }

        body.dark .student-card,
        body.dark .subject-card,
        body.dark .single-surface,
        body.dark .student-info-item,
        body.dark .subject-member {
            border-color: rgba(157, 185, 224, 0.22);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.97), rgba(17, 30, 48, 0.97));
            box-shadow: none;
        }

        body.dark .student-card-header {
            border-bottom-color: rgba(157, 185, 224, 0.18);
            background: linear-gradient(135deg, rgba(34, 49, 73, 0.88), rgba(25, 39, 61, 0.88));
        }

        body.dark .student-card-header h5,
        body.dark .student-card-header h6,
        body.dark .student-name,
        body.dark .student-info-value,
        body.dark .subject-card-title,
        body.dark .subject-member-name {
            color: #e2ecff;
        }

        body.dark .student-card-subtitle,
        body.dark .student-kicker,
        body.dark .student-meta,
        body.dark .student-info-label,
        body.dark .student-download-note,
        body.dark .subject-member-meta {
            color: #abc0e0;
        }

        body.dark .student-badge-info {
            background: rgba(76, 194, 211, 0.18);
            color: #bdebf2;
            border-color: rgba(76, 194, 211, 0.28);
        }

        body.dark .student-badge-primary,
        body.dark .subject-card-count {
            background: rgba(36, 118, 243, 0.2);
            color: #cfe1ff;
            border-color: rgba(36, 118, 243, 0.3);
        }

        body.dark .student-badge-neutral {
            background: rgba(130, 146, 166, 0.22);
            color: #d3deeb;
            border-color: rgba(130, 146, 166, 0.3);
        }

        body.dark .student-download-btn {
            color: #cfe3ff;
            background: rgba(43, 66, 103, 0.36);
            border-color: rgba(146, 182, 230, 0.45);
        }

        body.dark .student-empty {
            background: rgba(76, 194, 211, 0.16);
            color: #bdebf2;
        }

        body.dark .status-map-card,
        body.dark .status-item {
            border-color: rgba(157, 185, 224, 0.2);
            background: rgba(24, 37, 57, 0.82);
        }

        body.dark .status-map-title,
        body.dark .status-item-label,
        body.dark .status-item-progress {
            color: #e2ecff;
        }

        body.dark .status-map-meta,
        body.dark .status-item-detail {
            color: #abc0e0;
        }

        body.dark .status-pill-done {
            background: rgba(18, 163, 111, 0.22);
            color: #bdeedc;
            border-color: rgba(18, 163, 111, 0.34);
        }

        body.dark .status-pill-progress {
            background: rgba(245, 158, 11, 0.22);
            color: #ffe6b5;
            border-color: rgba(245, 158, 11, 0.34);
        }

        body.dark .status-pill-pending {
            background: rgba(220, 53, 69, 0.22);
            color: #ffc7cd;
            border-color: rgba(220, 53, 69, 0.34);
        }

        body.dark .status-form-chip {
            border-color: rgba(157, 185, 224, 0.18);
            background: rgba(24, 37, 57, 0.9);
        }

        body.dark .status-form-code {
            color: #e2ecff;
        }

        body.dark .status-form-chip-done {
            background: rgba(18, 163, 111, 0.2);
            border-color: rgba(18, 163, 111, 0.42);
        }

        body.dark .status-form-chip-done .status-form-code {
            color: #a0e8cc;
        }

        body.dark .status-form-chip-done .status-form-label {
            color: #bdeedc;
        }

        body.dark .status-form-chip-done .status-form-icon {
            color: #3ee8a8;
        }

        body.dark .status-form-chip-pending {
            background: rgba(220, 53, 69, 0.18);
            border-color: rgba(220, 53, 69, 0.36);
        }

        body.dark .status-form-chip-pending .status-form-code {
            color: #ffc0c8;
        }

        body.dark .status-form-chip-pending .status-form-label {
            color: #ffc7cd;
        }

        body.dark .status-form-chip-pending .status-form-icon {
            color: #ff8091;
        }
    </style>
@endpush


@php
    $schoolmateGroups = $studentSchoolmates->groupBy(function ($map) {
        return $map->students->subjects->name ?? 'Bidang Studi Belum Diatur';
    });
@endphp

<div class="content-wrapper">
    <div class="student-shell">
        <div class="student-grid">
            <div>
                @forelse ($studentMaps as $map)
                    <div class="student-card">
                        <div class="student-card-header">
                            <h5>Identitas Mahasiswa PLP</h5>
                            <p class="student-card-subtitle">Ringkasan identitas dan penempatan mahasiswa pada sistem PLP.</p>
                        </div>
                        <div class="student-card-body">
                            <div class="student-profile">
                                <div class="student-profile-head">
                                    <div>
                                        <p class="student-kicker">Peserta Aktif</p>
                                        <p class="student-name">{{ $map->students->name ?? '-' }}</p>
                                        <p class="student-meta">{{ $map->students->username ?? '-' }}</p>
                                    </div>
                                    <div class="student-badges">
                                        <span class="student-badge student-badge-info">Tahun Aktif {{ $activeYear }}</span>
                                        {{-- <span class="student-badge student-badge-primary">{{ $map->subjects->name ?? '-' }}</span>
                                        <span class="student-badge student-badge-neutral">{{ $map->schools->name ?? '-' }}</span> --}}
                                    </div>
                                </div>

                                <div class="student-info-grid">
                                    <div class="student-info-item">
                                        <p class="student-info-label">Tempat Praktik</p>
                                        <p class="student-info-value">{{ $map->schools->name ?? '-' }}</p>
                                    </div>
                                    <div class="student-info-item">
                                        <p class="student-info-label">Bidang Studi</p>
                                        <p class="student-info-value">{{ $map->subjects->name ?? '-' }}</p>
                                    </div>
                                    <div class="student-info-item">
                                        <p class="student-info-label">Guru Pamong</p>
                                        <p class="student-info-value student-contact">
                                            @if (isset($map->teachers->phone))
                                                <a href="{{ 'http://wa.me/62'.$map->teachers->phone }}" target="_blank" class="student-wa"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                            <span>{{ $map->teachers->name ?? '-' }}</span>
                                        </p>
                                    </div>
                                    <div class="student-info-item">
                                        <p class="student-info-label">Dosen Pembimbing Lapangan</p>
                                        <p class="student-info-value student-contact">
                                            @if (isset($map->lectures->phone))
                                                <a href="{{ 'http://wa.me/62'.$map->lectures->phone }}" target="_blank" class="student-wa"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                            <span>{{ $map->lectures->name ?? '-' }}</span>
                                        </p>
                                    </div>
                                    <div class="student-info-item">
                                        <p class="student-info-label">Kepala Sekolah</p>
                                        <p class="student-info-value student-contact">
                                            @if (isset($map->schools->headmasters->phone))
                                                <a href="{{ 'http://wa.me/62'.$map->schools->headmasters->phone }}" target="_blank" class="student-wa"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                            <span>{{ $map->schools->headmasters->name ?? '-' }}</span>
                                        </p>
                                    </div>
                                    <div class="student-info-item">
                                        <p class="student-info-label">Koordinator Guru Pamong</p>
                                        <p class="student-info-value student-contact">
                                            @if (isset($map->schools->coordinators->phone))
                                                <a href="{{ 'http://wa.me/62'.$map->schools->coordinators->phone }}" target="_blank" class="student-wa"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                            <span>{{ $map->schools->coordinators->name ?? '-' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="student-empty">Anda belum diplot oleh Jurusan.</div>
                @endforelse
                <div class="student-card mt-2">
                    <div class="student-card-header">
                        <h6>Akses Cepat Mahasiswa</h6>
                        <p class="student-card-subtitle">Gunakan menu berikut untuk aktivitas utama selama mengikuti PLP.</p>
                    </div>
                    <div class="student-card-body">
                        <div class="student-action-list">
                            <a href="{{ route('studentobservations.only.index') }}" class="student-action-btn student-action-primary">Observasi</a>
                            <a href="{{ route('studentdiaries.only.index') }}" class="student-action-btn student-action-success">Logbook</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="student-shell">

                <div class="student-card">
                    <div class="student-card-header">
                        <h6>Status Penilaian</h6>
                        <p class="student-card-subtitle">Ringkasan progres penilaian dari guru pamong dan dosen pembimbing lapangan.</p>
                    </div>
                    <div class="student-card-body">
                        @if (!empty($studentAssessmentStatuses) && $studentAssessmentStatuses->isNotEmpty())
                            <div class="status-stack">
                                @foreach ($studentAssessmentStatuses as $assessmentStatus)
                                    <div class="status-map-card">
                                        <div class="status-item">
                                            <div class="status-item-head">
                                                <p class="status-item-label">Guru Pamong</p>
                                                <span class="status-pill status-pill-{{ $assessmentStatus->teacher['tone'] }}">{{ $assessmentStatus->teacher['label'] }}</span>
                                            </div>
                                            <p class="status-item-detail">{{ $assessmentStatus->teacher['detail'] }}</p>
                                            <p class="status-item-progress">{{ $assessmentStatus->teacher['progress'] }}</p>
                                            <div class="status-form-grid">
                                                @foreach ($assessmentStatus->teacher['items'] as $item)
                                                    <div class="status-form-chip status-form-chip-{{ $item['tone'] }}">
                                                        <p class="status-form-code">{{ $item['code'] }}</p>
                                                        <p class="status-form-label">
                                                            <i class="fa status-form-icon {{ $item['tone'] === 'done' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>{{ $item['label'] }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="status-item">
                                            <div class="status-item-head">
                                                <p class="status-item-label">Dosen Pembimbing Lapangan</p>
                                                <span class="status-pill status-pill-{{ $assessmentStatus->lecture['tone'] }}">{{ $assessmentStatus->lecture['label'] }}</span>
                                            </div>
                                            <p class="status-item-detail">{{ $assessmentStatus->lecture['detail'] }}</p>
                                            <p class="status-item-progress">{{ $assessmentStatus->lecture['progress'] }}</p>
                                            <div class="status-form-grid">
                                                @foreach ($assessmentStatus->lecture['items'] as $item)
                                                    <div class="status-form-chip status-form-chip-{{ $item['tone'] }}">
                                                        <p class="status-form-code">{{ $item['code'] }}</p>
                                                        <p class="status-form-label">
                                                            <i class="fa status-form-icon {{ $item['tone'] === 'done' ? 'fa-check-circle' : 'fa-times-circle' }}"></i>{{ $item['label'] }}
                                                        </p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="student-empty">Status penilaian belum tersedia karena Anda belum memiliki penempatan aktif.</div>
                        @endif
                    </div>
                </div>

                <div class="student-card">
                    <div class="student-card-header">
                        <h6>Dokumen PLP</h6>
                        <p class="student-card-subtitle">Akses berkas utama yang dibutuhkan selama pelaksanaan dan pelaporan.</p>
                    </div>
                    <div class="student-card-body">
                        <div class="student-download-list">
                            <a href="{{ route('only.generateCover') }}" class="student-download-btn">Download Cover Laporan</a>
                            <a href="https://docs.google.com/document/d/1bbfXIjsT6U2qUY_RU0K1BM9wdREGr6q2/edit" class="student-download-btn">Template Formulir Bimbingan</a>
                        </div>
                        <p class="student-download-note mt-3">Template formulir bimbingan aktif untuk periode PLP {{ $activeYear }} tersedia pada tautan di atas.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="student-card">
            <div class="student-card-header">
                <h5>Mahasiswa Peserta PLP Satu Sekolah</h5>
                <p class="student-card-subtitle">Daftar peserta pada sekolah yang sama, dikelompokkan per bidang studi agar lebih cepat dipindai.</p>
            </div>
            <div class="student-card-body">
                @if ($schoolmateGroups->isNotEmpty())
                    <div class="subject-groups">
                        @foreach ($schoolmateGroups as $subjectName => $groupMaps)
                            <div class="subject-card">
                                <div class="subject-card-head">
                                    <div>
                                        <p class="subject-card-title">{{ $subjectName }}</p>
                                    </div>
                                    <span class="subject-card-count">{{ $groupMaps->count() }} mahasiswa</span>
                                </div>

                                <div class="subject-member-list">
                                    @foreach ($groupMaps as $map)
                                        <div class="subject-member">
                                            <div>
                                                <p class="subject-member-name">{{ $map->students->name ?? '-' }}</p>
                                                <p class="subject-member-meta">{{ $map->schools->name ?? '-' }}</p>
                                            </div>
                                            @if (isset($map->students->phone))
                                                <a href="{{ 'http://wa.me/62'.$map->students->phone }}" target="_blank" class="student-wa"><i class="fa fa-whatsapp"></i></a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="student-empty">Anda belum diplot oleh Jurusan.</div>
                @endif
            </div>
        </div>
    </div>
</div>
