@extends('layouts.master')

@push('css')
    <style>
        .summary-hero {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .summary-stat-card {
            border-radius: 16px;
            border: 1px solid #d9e3f1;
            background: linear-gradient(165deg, #ffffff 0%, #f6f9ff 100%);
            box-shadow: 0 10px 24px rgba(20, 44, 79, 0.08);
            padding: 18px 20px;
            min-height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .summary-stat-label {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .35px;
            text-transform: uppercase;
            color: #4c6281;
        }

        .summary-stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: #163653;
            line-height: 1;
        }

        .summary-stat-meta {
            font-size: 12px;
            color: #607894;
        }

        .summary-panel {
            border-radius: 16px;
            border: 1px solid #d9e3f1;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 24px rgba(20, 44, 79, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .summary-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e1eaf5;
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
        }

        .summary-panel-header h5 {
            margin: 0;
            color: #214466;
            font-weight: 700;
        }

        .summary-panel-header p {
            margin: 6px 0 0;
            color: #607894;
            font-size: 13px;
        }

        .summary-panel-body {
            padding: 18px 20px;
        }

        .summary-section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 8px 0 16px;
        }

        .summary-section-title h6 {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            color: #214466;
            letter-spacing: .2px;
        }

        .summary-section-title span {
            font-size: 12px;
            color: #607894;
        }

        .summary-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 16px;
        }

        .summary-detail-card {
            border-radius: 14px;
            border: 1px solid #d9e3f1;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(20, 44, 79, 0.06);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .summary-detail-card-header {
            padding: 14px 16px;
            border-bottom: 1px solid #e8eef7;
            background: linear-gradient(135deg, #f7fbff 0%, #eef6ff 100%);
        }

        .summary-detail-card-header.is-dpl {
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
        }

        .summary-detail-card-header.is-gp {
            background: linear-gradient(135deg, #fff7ed 0%, #fff1f2 100%);
        }

        .summary-detail-card-title {
            font-size: 15px;
            font-weight: 800;
            color: #163653;
            margin: 0;
        }

        .summary-detail-card-subtitle {
            margin: 4px 0 0;
            font-size: 12px;
            color: #607894;
        }

        .summary-detail-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .summary-detail-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid rgba(157, 176, 207, 0.55);
            background: rgba(255, 255, 255, 0.85);
            color: #214466;
        }

        .summary-detail-badge .value {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 24px;
            padding: 1px 7px;
            border-radius: 999px;
            background: #214466;
            color: #ffffff;
        }

        .summary-detail-card-body {
            padding: 0;
            flex: 1;
        }

        .summary-detail-table {
            margin: 0;
            width: 100%;
        }

        .summary-detail-table thead th {
            background: #f5f8fd;
            color: #29405f;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .25px;
            text-transform: uppercase;
            border-bottom: 1px solid #e1eaf5;
            padding: 10px 14px;
        }

        .summary-detail-table tbody td {
            padding: 11px 14px;
            vertical-align: top;
            border-bottom: 1px solid #eef2f8;
            font-size: 13px;
        }

        .summary-detail-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .summary-person-name {
            font-weight: 700;
            color: #163653;
        }

        .summary-count-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            padding: 4px 10px;
            border-radius: 999px;
            background: linear-gradient(135deg, #214466 0%, #2f5f88 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 12px;
        }

        .summary-breakdown-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .summary-breakdown-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            background: #f3f7fd;
            border: 1px solid #dbe6f4;
            color: #29405f;
            font-size: 11px;
        }

        .summary-breakdown-chip .count {
            font-weight: 800;
            color: #163653;
        }

        .summary-recap-table thead th {
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            color: #24364f;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.35px;
            border-bottom: 1px solid #d9e3f1;
        }

        .summary-recap-table tbody th {
            color: #163653;
            font-weight: 700;
        }

        .summary-empty {
            padding: 24px;
            text-align: center;
            color: #607894;
            font-size: 13px;
        }

        body.dark .summary-stat-card,
        body.dark .summary-panel,
        body.dark .summary-detail-card {
            background: linear-gradient(165deg, rgba(29, 43, 66, 0.95) 0%, rgba(21, 33, 52, 0.95) 100%);
            border-color: rgba(166, 187, 222, 0.18);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.32);
        }

        body.dark .summary-stat-label,
        body.dark .summary-stat-meta,
        body.dark .summary-panel-header p,
        body.dark .summary-section-title span,
        body.dark .summary-detail-card-subtitle,
        body.dark .summary-empty {
            color: #a9bdd9;
        }

        body.dark .summary-stat-value,
        body.dark .summary-panel-header h5,
        body.dark .summary-section-title h6,
        body.dark .summary-detail-card-title,
        body.dark .summary-person-name {
            color: #e7efff;
        }

        body.dark .summary-panel-header,
        body.dark .summary-detail-card-header,
        body.dark .summary-detail-card-header.is-dpl,
        body.dark .summary-detail-card-header.is-gp {
            background: linear-gradient(135deg, rgba(36, 54, 82, 0.95) 0%, rgba(28, 44, 68, 0.95) 100%);
            border-color: rgba(166, 187, 222, 0.18);
        }

        body.dark .summary-detail-table thead th,
        body.dark .summary-recap-table thead th {
            background: rgba(36, 54, 82, 0.85);
            color: #dbe7ff;
            border-color: rgba(166, 187, 222, 0.18);
        }

        body.dark .summary-detail-table tbody td,
        body.dark .summary-recap-table tbody td,
        body.dark .summary-recap-table tbody th {
            border-color: rgba(166, 187, 222, 0.12);
            color: #dbe7ff;
        }

        body.dark .summary-detail-badge,
        body.dark .summary-breakdown-chip {
            background: rgba(21, 33, 52, 0.85);
            border-color: rgba(166, 187, 222, 0.18);
            color: #dbe7ff;
        }

        body.dark .summary-breakdown-chip .count {
            color: #ffffff;
        }
    </style>
@endpush

@section('content')
@php
    $scopeType = $scope['type'] ?? 'all';
    $scopeLabel = $scope['label'] ?? null;
    $showDplSection = $scope['show_dpl_section'] ?? true;
    $showGpSection = $scope['show_gp_section'] ?? true;
@endphp
<div class="main-content">
    <div class="title d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span>Summary PLP {{ $activeYear }}@if ($scopeLabel) — {{ $scopeLabel }}@endif</span>
        @role('data')
            <a
                href="{{ route('report.summary.plp.export') }}"
                class="btn btn-sm btn-outline-success"
                title="Unduh rekap DPL dan GP per mapel dan sekolah"
            >
                Download Excel
            </a>
        @endrole
    </div>
    <div class="content-wrapper">
        @if ($scopeType === 'jurusan' && ($scope['subject_ids'] ?? collect())->isEmpty())
            <div class="alert alert-warning">
                Akun kajur belum memiliki jurusan (subject) yang terhubung.
            </div>
        @elseif ($scopeType === 'sekolah' && ($scope['school_ids'] ?? collect())->isEmpty())
            <div class="alert alert-warning">
                Akun sekolah belum terhubung ke data sekolah manapun.
            </div>
        @elseif ($scopeType !== 'all')
            <div class="alert alert-info py-2">
                @if ($scopeType === 'jurusan')
                    Data ditampilkan khusus untuk jurusan {{ $scopeLabel ?? 'Anda' }}.
                @else
                    Data ditampilkan khusus untuk sekolah {{ $scopeLabel ?? 'Anda' }}.
                @endif
            </div>
        @endif

        <div class="summary-hero">
            <div class="summary-stat-card">
                <div class="summary-stat-label">Mahasiswa PLP</div>
                <div class="summary-stat-value">{{ number_format($totals['students']) }}</div>
                <div class="summary-stat-meta">Tahun {{ $activeYear }}</div>
            </div>
            <div class="summary-stat-card">
                <div class="summary-stat-label">DPL Terplot</div>
                <div class="summary-stat-value">{{ number_format($totals['dpl']) }}</div>
                <div class="summary-stat-meta">Dosen pembimbing lapangan</div>
            </div>
            <div class="summary-stat-card">
                <div class="summary-stat-label">GP Terplot</div>
                <div class="summary-stat-value">{{ number_format($totals['gp']) }}</div>
                <div class="summary-stat-meta">Guru pendamping</div>
            </div>
            <div class="summary-stat-card">
                @if ($scopeType === 'sekolah')
                    <div class="summary-stat-label">Bidang Studi</div>
                    <div class="summary-stat-value">{{ count($subjectRecap) }}</div>
                    <div class="summary-stat-meta">{{ count($dplCards) }} jurusan terlibat</div>
                @elseif ($scopeType === 'jurusan')
                    <div class="summary-stat-label">Sekolah Penempatan</div>
                    <div class="summary-stat-value">{{ count($gpCards) }}</div>
                    <div class="summary-stat-meta">{{ count($dplCards) }} jurusan aktif</div>
                @else
                    <div class="summary-stat-label">Jurusan Aktif</div>
                    <div class="summary-stat-value">{{ count($subjectRecap) }}</div>
                    <div class="summary-stat-meta">{{ count($gpCards) }} sekolah penempatan</div>
                @endif
            </div>
        </div>

        <div class="summary-panel">
            <div class="summary-panel-header">
                <h5>Rekap Data PLP</h5>
                <p>
                    @if ($scopeType === 'jurusan')
                        Ringkasan mahasiswa, DPL, dan GP pada jurusan {{ $scopeLabel ?? 'Anda' }}.
                    @elseif ($scopeType === 'sekolah')
                        Ringkasan mahasiswa, DPL, dan GP per bidang studi di sekolah {{ $scopeLabel ?? 'Anda' }}.
                    @else
                        Ringkasan mahasiswa, DPL, dan GP per jurusan/bidang studi.
                    @endif
                </p>
            </div>
            <div class="summary-panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm summary-recap-table">
                        <thead>
                            <tr>
                                <th>Jurusan</th>
                                <th class="text-end">Mahasiswa</th>
                                <th class="text-end">DPL</th>
                                <th class="text-end">GP</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjectRecap as $row)
                            <tr>
                                <th scope="row">
                                    <div>{{ $row['name'] }}</div>
                                    <small class="text-muted">{{ $row['department'] }}</small>
                                </th>
                                <td class="text-end">{{ number_format($row['student_count']) }}</td>
                                <td class="text-end">{{ number_format($row['dpl_count']) }}</td>
                                <td class="text-end">{{ number_format($row['gp_count']) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">Belum ada data rekap PLP.</td>
                            </tr>
                            @endforelse
                            @if (count($subjectRecap) > 0)
                            <tr class="text-primary">
                                <th>Total</th>
                                <th class="text-end">{{ number_format($totals['students']) }}</th>
                                <th class="text-end">{{ number_format($totals['dpl']) }}</th>
                                <th class="text-end">{{ number_format($totals['gp']) }}</th>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if ($showDplSection)
        <div class="summary-section-title">
            <h6>Detail DPL per Jurusan</h6>
            <span>
                @if ($scopeType === 'jurusan')
                    Jurusan {{ $scopeLabel ?? 'Anda' }}
                @else
                    {{ count($dplCards) }} jurusan
                @endif
            </span>
        </div>

        @if (count($dplCards) === 0)
            <div class="summary-panel mb-4">
                <div class="summary-empty">Belum ada data DPL yang terplot pada PLP tahun ini.</div>
            </div>
        @else
            <div class="summary-detail-grid mb-4">
                @foreach ($dplCards as $card)
                <div class="summary-detail-card">
                    <div class="summary-detail-card-header is-dpl">
                        <p class="summary-detail-card-title">{{ $card['department'] }}</p>
                        <p class="summary-detail-card-subtitle">{{ $card['name'] }}</p>
                        <div class="summary-detail-badges">
                            <span class="summary-detail-badge">
                                Mahasiswa
                                <span class="value">{{ number_format($card['student_total']) }}</span>
                            </span>
                            <span class="summary-detail-badge">
                                DPL
                                <span class="value">{{ number_format($card['dpl_total']) }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="summary-detail-card-body">
                        <div class="table-responsive">
                            <table class="table summary-detail-table">
                                <thead>
                                    <tr>
                                        <th>DPL</th>
                                        <th class="text-end" style="width: 90px;">Mahasiswa</th>
                                        <th>Penempatan Sekolah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($card['rows'] as $row)
                                    <tr>
                                        <td><span class="summary-person-name">{{ $row['name'] }}</span></td>
                                        <td class="text-end">
                                            <span class="summary-count-pill">{{ number_format($row['student_count']) }}</span>
                                        </td>
                                        <td>
                                            <div class="summary-breakdown-list">
                                                @foreach ($row['schools'] as $school)
                                                <span class="summary-breakdown-chip">
                                                    {{ $school['name'] }}
                                                    <span class="count">{{ $school['student_count'] }}</span>
                                                </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        @endif

        @if ($showGpSection)
        <div class="summary-section-title">
            <h6>Detail GP per Sekolah</h6>
            <span>
                @if ($scopeType === 'sekolah')
                    {{ $scopeLabel ?? 'Sekolah Anda' }}
                @else
                    {{ count($gpCards) }} sekolah
                @endif
            </span>
        </div>

        @if (count($gpCards) === 0)
            <div class="summary-panel">
                <div class="summary-empty">Belum ada data GP yang terplot pada PLP tahun ini.</div>
            </div>
        @else
            <div class="summary-detail-grid">
                @foreach ($gpCards as $card)
                <div class="summary-detail-card">
                    <div class="summary-detail-card-header is-gp">
                        <p class="summary-detail-card-title">{{ $card['name'] }}</p>
                        <p class="summary-detail-card-subtitle">Rekap guru pendamping per sekolah</p>
                        <div class="summary-detail-badges">
                            <span class="summary-detail-badge">
                                Mahasiswa
                                <span class="value">{{ number_format($card['student_total']) }}</span>
                            </span>
                            <span class="summary-detail-badge">
                                GP
                                <span class="value">{{ number_format($card['gp_total']) }}</span>
                            </span>
                        </div>
                    </div>
                    <div class="summary-detail-card-body">
                        <div class="table-responsive">
                            <table class="table summary-detail-table">
                                <thead>
                                    <tr>
                                        <th>GP</th>
                                        <th class="text-end" style="width: 90px;">Mahasiswa</th>
                                        <th>Bidang Studi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($card['rows'] as $row)
                                    <tr>
                                        <td><span class="summary-person-name">{{ $row['name'] }}</span></td>
                                        <td class="text-end">
                                            <span class="summary-count-pill">{{ number_format($row['student_count']) }}</span>
                                        </td>
                                        <td>
                                            <div class="summary-breakdown-list">
                                                @foreach ($row['subjects'] as $subject)
                                                <span class="summary-breakdown-chip">
                                                    {{ $subject['name'] }}
                                                    <span class="count">{{ $subject['student_count'] }}</span>
                                                </span>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
        @endif
    </div>
</div>
@endsection
