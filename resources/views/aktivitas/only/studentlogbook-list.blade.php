@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    <style>
        .owner-summary-card {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.96), rgba(245, 250, 255, 0.96));
            padding: 14px;
            margin-bottom: 16px;
        }

        .owner-summary-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .owner-summary-title {
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #5f7394;
            margin: 0;
            font-weight: 700;
        }

        .owner-summary-name {
            margin: 0;
            font-size: 1.06rem;
            font-weight: 700;
            color: #233754;
        }

        .owner-summary-meta {
            margin: 2px 0 0;
            color: #6a7f9e;
            font-size: 0.83rem;
        }

        .owner-summary-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-logbook-modern {
            border: none;
            border-radius: 999px;
            padding: 0.44rem 0.88rem;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #fff;
            background: linear-gradient(135deg, #12a36f, #0b7f57);
            box-shadow: 0 6px 16px rgba(18, 163, 111, 0.28);
            transition: transform 0.18s ease, box-shadow 0.22s ease, filter 0.2s ease;
        }

        .btn-logbook-modern:hover,
        .btn-logbook-modern:focus {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 10px 18px rgba(18, 163, 111, 0.34);
            filter: saturate(1.08);
        }

        .btn-logbook-modern:active {
            transform: translateY(0);
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

        .list-shell {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        .logbook-list-table {
            margin-bottom: 0;
        }

        .logbook-list-table thead th {
            background: linear-gradient(135deg, #edf4ff, #f6f9ff);
            border-bottom: 1px solid rgba(82, 112, 154, 0.22);
            color: #526789;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            font-size: 0.75rem;
        }

        .logbook-list-table tbody td {
            vertical-align: top;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .student-name {
            margin: 0;
            font-size: 0.92rem;
            font-weight: 700;
            color: #223553;
        }

        .student-meta {
            margin: 3px 0 0;
            color: #627897;
            font-size: 0.8rem;
        }

        .verify-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        body.dark .owner-summary-card {
            border-color: rgba(157, 185, 224, 0.24);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.95), rgba(17, 30, 48, 0.95));
        }

        body.dark .owner-summary-title,
        body.dark .owner-summary-meta,
        body.dark .student-meta {
            color: #a8bddd;
        }

        body.dark .owner-summary-name,
        body.dark .student-name {
            color: #e2ecff;
        }

        body.dark .list-shell {
            border-color: rgba(157, 185, 224, 0.24);
            background: rgba(15, 26, 42, 0.8);
        }

        body.dark .btn-logbook-modern {
            background: linear-gradient(135deg, #1cbf82, #149665);
            box-shadow: 0 6px 16px rgba(28, 191, 130, 0.26);
        }

        body.dark .btn-logbook-modern:hover,
        body.dark .btn-logbook-modern:focus {
            box-shadow: 0 10px 18px rgba(28, 191, 130, 0.32);
        }

        body.dark .btn-modern-outline {
            border-color: rgba(146, 182, 230, 0.45);
            color: #cfe3ff;
            background: rgba(43, 66, 103, 0.36);
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

        body.dark .logbook-list-table thead th {
            background: linear-gradient(135deg, rgba(34, 49, 73, 0.92), rgba(25, 39, 61, 0.92));
            color: #b8cbea;
            border-bottom-color: rgba(157, 185, 224, 0.24);
        }

        @media (max-width: 768px) {
            .logbook-list-table thead {
                display: none;
            }

            .logbook-list-table,
            .logbook-list-table tbody,
            .logbook-list-table tr,
            .logbook-list-table td {
                display: block;
                width: 100%;
            }

            .logbook-list-table tr {
                border-bottom: 1px solid rgba(82, 112, 154, 0.18);
                padding: 8px 0;
            }

            .logbook-list-table td {
                border: none;
                padding: 8px 12px;
            }

            .logbook-list-table td[data-label]::before {
                content: attr(data-label);
                display: block;
                font-size: 0.72rem;
                text-transform: uppercase;
                letter-spacing: 0.45px;
                color: #6b7f9f;
                margin-bottom: 3px;
                font-weight: 700;
            }

            body.dark .logbook-list-table td[data-label]::before {
                color: #9db6dc;
            }
        }
    </style>
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Catatan Harian Kegiatan PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Mahasiswa Pamongan

                            <a href="{{ route('dashboard') }}" class="btn btn-modern btn-modern-outline float-end">Dashboard</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalVerified = $maps->sum('verified_count');
                            $totalUnverified = $maps->sum('unverified_count');
                            $totalDiaries = $maps->sum('total_count');
                        @endphp

                        <div class="owner-summary-card">
                            <div class="owner-summary-top">
                                <div>
                                    <p class="owner-summary-title">Identitas Dosen Pembimbing Lapangan</p>
                                    <p class="owner-summary-name">{{ $user->name ?? '-' }}</p>
                                    <p class="owner-summary-meta">{{ $user->username ?? '-' }}</p>
                                </div>
                                <div class="owner-summary-badges">
                                    <span class="badge-modern badge-modern-year">Tahun Aktif {{ $activeYear }}</span>
                                    <span class="badge-modern badge-modern-success">Sudah {{ $totalVerified }}</span>
                                    <span class="badge-modern badge-modern-warning">Belum {{ $totalUnverified }}</span>
                                    <span class="badge-modern badge-modern-neutral">Total {{ $totalDiaries }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div class="list-shell">
                            <table class="table small-font table-striped table-hover table-sm logbook-list-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Identitas Mahasiswa</th>
                                        <th>Status Verifikasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($maps as $map)
                                    <tr>
                                        <td class="text-end" data-label="Aksi">
                                            <a href="{{ route('diaryverifications.only.show',['map_id'=>$map->id]) }}" class="btn btn-logbook-modern">Logbook</a>
                                        </td>
                                        <td data-label="Identitas Mahasiswa">
                                            <p class="student-name">{{ $map->students->name ?? '-' }}</p>
                                            <p class="student-meta">{{ $map->students->username ?? '-' }}</p>
                                        </td>
                                        <td data-label="Status Verifikasi">
                                            <div class="verify-badges">
                                                <span class="badge-modern badge-modern-success">sudah {{ $map->verified_count }}</span>
                                                <span class="badge-modern badge-modern-danger">belum {{ $map->unverified_count }}</span>
                                                <span class="badge-modern badge-modern-neutral">total {{ $map->total_count }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="alert alert-info mb-0">
                                                Belum ada data mahasiswa pamongan untuk tahun aktif {{ $activeYear }}.
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
