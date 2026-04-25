@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .teach-shell {
            display: grid;
            gap: 18px;
        }

        .teach-top {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 2.2fr);
            gap: 18px;
            align-items: start;
        }

        .teach-card {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 18px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.97), rgba(245, 250, 255, 0.97));
            box-shadow: 0 16px 36px rgba(35, 55, 84, 0.08);
            overflow: hidden;
        }

        .teach-card-header {
            padding: 14px 18px 10px;
            border-bottom: 1px solid rgba(82, 112, 154, 0.16);
            background: linear-gradient(135deg, rgba(237, 244, 255, 0.88), rgba(247, 250, 255, 0.88));
        }

        .teach-card-header h5,
        .teach-card-header h6 {
            margin: 0;
            color: #223653;
            font-weight: 800;
        }

        .teach-card-subtitle {
            margin-top: 5px;
            color: #6980a2;
            font-size: 0.84rem;
        }

        .teach-card-body {
            padding: 16px 18px;
        }

        .teach-identity-kicker {
            margin: 0 0 5px;
            color: #5f7394;
            text-transform: uppercase;
            letter-spacing: 0.38px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .teach-identity-name {
            margin: 0 0 2px;
            font-size: 1.15rem;
            font-weight: 800;
            color: #1f3553;
        }

        .teach-identity-meta {
            margin: 0 0 10px;
            color: #6a7f9e;
            font-size: 0.84rem;
        }

        .teach-identity-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.3rem 0.64rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.2px;
            background: rgba(36, 118, 243, 0.12);
            color: #1759c5;
            border: 1px solid rgba(36, 118, 243, 0.22);
            margin-bottom: 14px;
        }

        .teach-action-list {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .teach-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 0.46rem 0.96rem;
            font-size: 0.77rem;
            font-weight: 700;
            letter-spacing: 0.24px;
            text-decoration: none;
            transition: transform 0.18s ease, filter 0.2s ease;
        }

        .teach-action-btn:hover {
            transform: translateY(-1px);
            filter: saturate(1.08);
            text-decoration: none;
        }

        .teach-action-primary {
            color: #fff;
            background: linear-gradient(135deg, #2476f3, #1759c5);
            box-shadow: 0 8px 18px rgba(36, 118, 243, 0.24);
        }

        .teach-school-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 14px;
        }

        .teach-school-card {
            border: 1px solid rgba(82, 112, 154, 0.18);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.84);
            overflow: hidden;
        }

        .teach-school-card-complete {
            border-color: rgba(18, 163, 111, 0.28);
        }

        .teach-school-card-incomplete {
            border-color: rgba(220, 53, 69, 0.2);
        }

        .teach-school-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: rgba(237, 244, 255, 0.7);
            border-bottom: 1px solid rgba(82, 112, 154, 0.14);
        }

        .teach-school-name {
            margin: 0;
            color: #1f3553;
            font-size: 0.88rem;
            font-weight: 800;
            line-height: 1.4;
        }

        .teach-school-count {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.2rem 0.52rem;
            background: rgba(36, 118, 243, 0.12);
            color: #1759c5;
            font-size: 0.69rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .teach-school-progress {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 9px 14px;
            border-bottom: 1px solid rgba(82, 112, 154, 0.08);
            background: rgba(255, 255, 255, 0.45);
        }

        .teach-progress-copy {
            margin: 0;
            color: #5f7394;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .teach-progress-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            border-radius: 999px;
            padding: 0.24rem 0.56rem;
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.16px;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .teach-progress-chip-complete {
            background: rgba(18, 163, 111, 0.15);
            color: #0a6b47;
            border-color: rgba(18, 163, 111, 0.34);
        }

        .teach-progress-chip-incomplete {
            background: rgba(220, 53, 69, 0.1);
            color: #8a1f2c;
            border-color: rgba(220, 53, 69, 0.28);
        }

        .teach-student-list {
            display: grid;
            gap: 0;
        }

        .teach-student-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            padding: 9px 14px;
            border-bottom: 1px solid rgba(82, 112, 154, 0.08);
        }

        .teach-student-row:last-child {
            border-bottom: none;
        }

        .teach-student-info {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            flex: 1 1 220px;
        }

        .teach-wa-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 999px;
            color: #fff;
            background: linear-gradient(135deg, #16b978, #0c8d5c);
            box-shadow: 0 6px 14px rgba(18, 163, 111, 0.22);
            flex-shrink: 0;
            font-size: 0.78rem;
        }

        .teach-student-meta {
            display: grid;
            gap: 5px;
            min-width: 0;
        }

        .teach-student-name {
            margin: 0;
            color: #244063;
            font-size: 0.86rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .teach-student-progress {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            width: fit-content;
            border-radius: 999px;
            padding: 0.16rem 0.48rem;
            font-size: 0.66rem;
            font-weight: 800;
            letter-spacing: 0.14px;
            border: 1px solid transparent;
        }

        .teach-student-progress-complete {
            background: rgba(18, 163, 111, 0.14);
            color: #0a6b47;
            border-color: rgba(18, 163, 111, 0.3);
        }

        .teach-student-progress-incomplete {
            background: rgba(245, 158, 11, 0.12);
            color: #96600a;
            border-color: rgba(245, 158, 11, 0.28);
        }

        .teach-form-badges {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            flex: 1 1 100%;
            width: 100%;
            max-width: 100%;
            justify-content: flex-start;
            margin-left: 36px;
        }

        @media (max-width: 767.98px) {
            .teach-form-badges {
                width: 100%;
                max-width: 100%;
                margin-left: 0;
                justify-content: flex-start;
            }
        }

        .teach-form-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            border-radius: 8px;
            padding: 0.2rem 0.46rem;
            font-size: 0.69rem;
            font-weight: 800;
            letter-spacing: 0.16px;
            border: 1px solid transparent;
        }

        .teach-form-badge-done {
            background: rgba(18, 163, 111, 0.15);
            color: #0a6b47;
            border-color: rgba(18, 163, 111, 0.35);
        }

        .teach-form-badge-done .teach-form-icon {
            color: #12a36f;
        }

        .teach-form-badge-pending {
            background: rgba(220, 53, 69, 0.1);
            color: #8a1f2c;
            border-color: rgba(220, 53, 69, 0.28);
        }

        .teach-form-badge-pending .teach-form-icon {
            color: #dc3545;
        }

        .teach-form-icon {
            font-size: 0.64rem;
        }

        .teach-empty {
            border-radius: 12px;
            padding: 12px 16px;
            background: rgba(23, 162, 184, 0.12);
            color: #0d7283;
            font-weight: 700;
            font-size: 0.88rem;
        }

        @media (max-width: 991.98px) {
            .teach-top {
                grid-template-columns: 1fr;
            }
        }

        body.dark .teach-card {
            border-color: rgba(157, 185, 224, 0.22);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.97), rgba(17, 30, 48, 0.97));
            box-shadow: none;
        }

        body.dark .teach-card-header {
            border-bottom-color: rgba(157, 185, 224, 0.18);
            background: linear-gradient(135deg, rgba(34, 49, 73, 0.88), rgba(25, 39, 61, 0.88));
        }

        body.dark .teach-card-header h5,
        body.dark .teach-card-header h6,
        body.dark .teach-identity-name,
        body.dark .teach-school-name,
        body.dark .teach-student-name {
            color: #e2ecff;
        }

        body.dark .teach-card-subtitle,
        body.dark .teach-identity-kicker,
        body.dark .teach-identity-meta,
        body.dark .teach-progress-copy {
            color: #abc0e0;
        }

        body.dark .teach-identity-badge,
        body.dark .teach-school-count {
            background: rgba(36, 118, 243, 0.2);
            color: #cfe1ff;
            border-color: rgba(36, 118, 243, 0.3);
        }

        body.dark .teach-school-card {
            border-color: rgba(157, 185, 224, 0.2);
            background: rgba(24, 37, 57, 0.82);
        }

        body.dark .teach-school-card-complete {
            border-color: rgba(18, 163, 111, 0.36);
        }

        body.dark .teach-school-card-incomplete {
            border-color: rgba(220, 53, 69, 0.28);
        }

        body.dark .teach-school-head {
            background: rgba(34, 49, 73, 0.6);
            border-bottom-color: rgba(157, 185, 224, 0.14);
        }

        body.dark .teach-school-progress {
            background: rgba(20, 31, 48, 0.5);
            border-bottom-color: rgba(157, 185, 224, 0.09);
        }

        body.dark .teach-student-row {
            border-bottom-color: rgba(157, 185, 224, 0.09);
        }

        body.dark .teach-progress-chip-complete {
            background: rgba(18, 163, 111, 0.2);
            color: #a0e8cc;
            border-color: rgba(18, 163, 111, 0.38);
        }

        body.dark .teach-progress-chip-incomplete {
            background: rgba(220, 53, 69, 0.18);
            color: #ffc0c8;
            border-color: rgba(220, 53, 69, 0.32);
        }

        body.dark .teach-student-progress-complete {
            background: rgba(18, 163, 111, 0.18);
            color: #a0e8cc;
            border-color: rgba(18, 163, 111, 0.34);
        }

        body.dark .teach-student-progress-incomplete {
            background: rgba(245, 158, 11, 0.2);
            color: #ffe6b5;
            border-color: rgba(245, 158, 11, 0.3);
        }

        body.dark .teach-form-badge-done {
            background: rgba(18, 163, 111, 0.2);
            color: #a0e8cc;
            border-color: rgba(18, 163, 111, 0.4);
        }

        body.dark .teach-form-badge-done .teach-form-icon {
            color: #3ee8a8;
        }

        body.dark .teach-form-badge-pending {
            background: rgba(220, 53, 69, 0.18);
            color: #ffc0c8;
            border-color: rgba(220, 53, 69, 0.34);
        }

        body.dark .teach-form-badge-pending .teach-form-icon {
            color: #ff8091;
        }

        body.dark .teach-empty {
            background: rgba(76, 194, 211, 0.16);
            color: #bdebf2;
        }
    </style>
@endpush

@php
    $teacherSchoolGroups = $teacherMaps->groupBy(fn($map) => $map->schools->name ?? 'Sekolah Belum Diatur');
@endphp

<div class="content-wrapper">
    <div class="teach-shell">
        <div class="teach-top">
            <div class="teach-card">
                <div class="teach-card-header">
                    <h6>Identitas Guru Pamong</h6>
                    <p class="teach-card-subtitle">Informasi akun dan akses cepat aktivitas penilaian.</p>
                </div>
                <div class="teach-card-body">
                    <p class="teach-identity-kicker">Guru Pamong Aktif</p>
                    <p class="teach-identity-name">{{ auth()->user()->name ?? '-' }}</p>
                    <p class="teach-identity-meta">{{ auth()->user()->username ?? '-' }}</p>
                    <span class="teach-identity-badge">{{ auth()->user()->subjects->name ?? 'Prodi belum diatur' }}</span>
                    <div class="teach-action-list">
                        <a href="{{ route('schoolassessments.only.index') }}" class="teach-action-btn teach-action-primary">Mulai Menilai</a>
                    </div>
                </div>
            </div>

            <div class="teach-card">
                <div class="teach-card-header">
                    <h6>Ringkasan Mahasiswa Pamongan</h6>
                    <p class="teach-card-subtitle">{{ $teacherMaps->count() }} mahasiswa di {{ $teacherSchoolGroups->count() }} sekolah.</p>
                </div>
                <div class="teach-card-body">
                    @if ($teacherMaps->isEmpty())
                        <div class="teach-empty">Belum ada mahasiswa yang dipetakan ke Anda pada tahun aktif ini.</div>
                    @else
                        <div class="teach-school-grid">
                            @foreach ($teacherSchoolGroups as $schoolName => $schoolMaps)
                                @php
                                    $schoolCompletedForms = 0;
                                    $schoolTotalForms = 0;

                                    foreach ($schoolMaps as $schoolMap) {
                                        $mapBadges = collect($teacherMapBadges[$schoolMap->id] ?? []);
                                        $schoolCompletedForms += $mapBadges->where('done', true)->count();
                                        $schoolTotalForms += $mapBadges->count();
                                    }

                                    $schoolIsComplete = $schoolTotalForms > 0 && $schoolCompletedForms === $schoolTotalForms;
                                @endphp
                                <div class="teach-school-card teach-school-card-{{ $schoolIsComplete ? 'complete' : 'incomplete' }}">
                                    <div class="teach-school-head">
                                        <p class="teach-school-name">{{ $schoolName }}</p>
                                        <span class="teach-school-count">{{ $schoolMaps->count() }} mhs</span>
                                    </div>
                                    <div class="teach-school-progress">
                                        <p class="teach-progress-copy">Progres sekolah {{ $schoolCompletedForms }}/{{ $schoolTotalForms }} form</p>
                                        <span class="teach-progress-chip teach-progress-chip-{{ $schoolIsComplete ? 'complete' : 'incomplete' }}">
                                            <i class="fa {{ $schoolIsComplete ? 'fa-check-circle' : 'fa-exclamation-circle' }}"></i>
                                            {{ $schoolIsComplete ? 'Selesai' : 'Belum selesai' }}
                                        </span>
                                    </div>
                                    <div class="teach-student-list">
                                        @foreach ($schoolMaps as $map)
                                            @php
                                                $studentBadges = collect($teacherMapBadges[$map->id] ?? []);
                                                $studentCompletedForms = $studentBadges->where('done', true)->count();
                                                $studentTotalForms = $studentBadges->count();
                                                $studentIsComplete = $studentTotalForms > 0 && $studentCompletedForms === $studentTotalForms;
                                            @endphp
                                            <div class="teach-student-row">
                                                <div class="teach-student-info">
                                                    @if (isset($map->students->phone))
                                                        <a href="{{ 'http://wa.me/62'.$map->students->phone }}" target="_blank" class="teach-wa-btn"><i class="fa fa-whatsapp"></i></a>
                                                    @endif
                                                    <div class="teach-student-meta">
                                                        <p class="teach-student-name">{{ $map->students->name ?? '-' }}</p>
                                                        <span class="teach-student-progress teach-student-progress-{{ $studentIsComplete ? 'complete' : 'incomplete' }}">
                                                            <i class="fa {{ $studentIsComplete ? 'fa-check-circle' : 'fa-clock-o' }}"></i>
                                                            {{ $studentCompletedForms }}/{{ $studentTotalForms }} form
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="teach-form-badges">
                                                    @foreach ($studentBadges as $badge)
                                                        <span class="teach-form-badge teach-form-badge-{{ $badge['done'] ? 'done' : 'pending' }}">
                                                            <i class="fa teach-form-icon {{ $badge['done'] ? 'fa-check' : 'fa-times' }}"></i>{{ $badge['code'] }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
