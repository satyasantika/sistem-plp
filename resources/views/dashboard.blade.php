@extends('layouts.master')

@push('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
<style>
    .main-content.dashboard-modern {
        --dash-bg: linear-gradient(160deg, #eff5ff 0%, #f7fbff 40%, #edf7f2 100%);
        --dash-card-bg: linear-gradient(168deg, #ffffff 0%, #f7faff 100%);
        --dash-border: #d8e4f3;
        --dash-soft-text: #5a6a83;
        --dash-strong-text: #1f2f4d;
        --dash-accent: #0f6ad6;
        --dash-accent-soft: rgba(15, 106, 214, 0.12);
        --dash-shadow: 0 14px 36px rgba(30, 56, 98, 0.1);
        --dash-table-wrap-bg: linear-gradient(180deg, rgba(247, 251, 255, 0.95) 0%, rgba(238, 246, 255, 0.95) 100%);
        --dash-table-head-bg: linear-gradient(180deg, #f3f8ff 0%, #eaf3ff 100%);
        --dash-table-row-alt: rgba(15, 106, 214, 0.045);
        --dash-table-hover: rgba(15, 106, 214, 0.12);

        font-family: "Manrope", "Segoe UI", sans-serif;
        background: var(--dash-bg);
        border-radius: 18px 18px 0 0;
        padding-bottom: 20px;
    }

    .dashboard-modern .dashboard-hero {
        border: 1px solid var(--dash-border);
        background: linear-gradient(115deg, rgba(255, 255, 255, 0.96) 0%, rgba(239, 246, 255, 0.95) 55%, rgba(234, 248, 241, 0.95) 100%);
        box-shadow: var(--dash-shadow);
        border-radius: 16px;
        padding: 16px 18px;
        margin-bottom: 16px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-modern .dashboard-hero::after {
        content: "";
        position: absolute;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        right: -70px;
        top: -90px;
        background: radial-gradient(circle at center, rgba(15, 106, 214, 0.2) 0%, rgba(15, 106, 214, 0) 68%);
    }

    .dashboard-modern .dashboard-hero h1 {
        font-size: 1.18rem;
        font-weight: 800;
        margin: 0;
        color: var(--dash-strong-text);
        letter-spacing: 0.2px;
    }

    .dashboard-modern .dashboard-hero p {
        margin: 6px 0 0;
        color: var(--dash-soft-text);
        font-size: 0.9rem;
        font-weight: 600;
    }

    .dashboard-modern .content-wrapper {
        margin-bottom: 14px;
    }

    .dashboard-modern .card {
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        border-radius: 16px;
        box-shadow: var(--dash-shadow);
        overflow: hidden;
    }

    .dashboard-modern .card-header {
        border-bottom: 1px solid color-mix(in srgb, var(--dash-border) 75%, transparent);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.9) 0%, rgba(246, 250, 255, 0.82) 100%);
        padding: 14px 16px;
    }

    .dashboard-modern .card-header h5 {
        margin: 0;
        color: var(--dash-strong-text);
        font-weight: 800;
        font-size: 0.98rem;
        letter-spacing: 0.15px;
    }

    .dashboard-modern .card .card-body {
        padding: 16px;
    }

    .dashboard-modern .table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        border-color: color-mix(in srgb, var(--dash-border) 70%, transparent);
    }

    .dashboard-modern .table-responsive {
        border: 1px solid color-mix(in srgb, var(--dash-border) 82%, transparent);
        border-radius: 13px;
        background: var(--dash-table-wrap-bg);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.72);
    }

    .dashboard-modern .table thead th {
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--dash-soft-text);
        background: var(--dash-table-head-bg);
        border-bottom-width: 1px;
        border-bottom-color: color-mix(in srgb, var(--dash-border) 80%, transparent);
        font-weight: 800;
        padding: 0.72rem 0.78rem;
    }

    .dashboard-modern .table td {
        color: var(--dash-strong-text);
        font-weight: 600;
        border-color: color-mix(in srgb, var(--dash-border) 68%, transparent);
        vertical-align: middle;
        padding: 0.74rem 0.78rem;
    }

    .dashboard-modern .table > tbody > tr:nth-child(even) > * {
        background: var(--dash-table-row-alt);
    }

    .dashboard-modern .table-hover > tbody > tr:hover > * {
        background: var(--dash-table-hover);
    }

    .dashboard-modern .table > :not(caption) > * > *:first-child {
        padding-left: 0.92rem;
    }

    .dashboard-modern .table > :not(caption) > * > *:last-child {
        padding-right: 0.92rem;
    }

    .dashboard-modern .badge {
        border: 1px solid color-mix(in srgb, var(--dash-border) 80%, transparent);
        border-radius: 999px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 0.4rem 0.62rem;
        background: color-mix(in srgb, var(--dash-accent) 10%, #ffffff);
        color: var(--dash-strong-text);
    }

    .dashboard-modern .badge.bg-light {
        background: linear-gradient(180deg, #ffffff 0%, #eef5ff 100%) !important;
        color: color-mix(in srgb, var(--dash-strong-text) 86%, #ffffff) !important;
        border-color: color-mix(in srgb, var(--dash-border) 92%, transparent);
        box-shadow: 0 6px 14px rgba(30, 56, 98, 0.08);
    }

    .dashboard-modern .badge.bg-primary {
        background: linear-gradient(135deg, #0f6ad6 0%, #2e9ae6 100%) !important;
        color: #ffffff !important;
        border-color: color-mix(in srgb, #0f6ad6 78%, #ffffff);
        box-shadow: 0 8px 16px rgba(15, 106, 214, 0.28);
    }

    .dashboard-modern .badge.text-dark {
        color: color-mix(in srgb, var(--dash-strong-text) 85%, #000000) !important;
    }

    .dashboard-modern td .badge + .badge,
    .dashboard-modern td .badge + br + .badge {
        margin-left: 0.35rem;
    }

    .dashboard-modern .btn.btn-sm.btn-success {
        border-radius: 10px;
        padding: 0.25rem 0.48rem;
        min-width: 2rem;
        box-shadow: 0 6px 14px rgba(15, 143, 99, 0.26);
    }

    .dashboard-modern .btn {
        border-radius: 11px;
        font-weight: 700;
        border-width: 1px;
    }

    .dashboard-modern .btn-primary {
        background: linear-gradient(135deg, #0f6ad6 0%, #2890dc 100%);
        border-color: #0f6ad6;
        box-shadow: 0 8px 18px rgba(15, 106, 214, 0.24);
    }

    .dashboard-modern .btn-outline-primary {
        color: #0f6ad6;
        border-color: color-mix(in srgb, #0f6ad6 65%, white);
        background: color-mix(in srgb, #0f6ad6 10%, white);
    }

    .dashboard-modern .btn-success {
        background: linear-gradient(135deg, #0f8f63 0%, #14a36f 100%);
        border-color: #0f8f63;
    }

    .dashboard-modern .alert {
        border-radius: 12px;
        border: 1px dashed var(--dash-border);
        background: color-mix(in srgb, #0f6ad6 8%, #ffffff);
        color: var(--dash-soft-text);
        font-weight: 700;
    }

    .dashboard-modern .dashboard-inactive {
        border: 1px solid var(--dash-border);
        background: var(--dash-card-bg);
        border-radius: 16px;
        box-shadow: var(--dash-shadow);
        padding: 22px;
        color: var(--dash-strong-text);
        font-size: 1rem;
        font-weight: 700;
    }

    body.dark .main-content.dashboard-modern {
        --dash-bg: radial-gradient(circle at 100% 0%, rgba(56, 98, 167, 0.32) 0%, rgba(16, 28, 44, 0.96) 42%, rgba(12, 22, 38, 1) 100%);
        --dash-card-bg: linear-gradient(170deg, rgba(22, 36, 58, 0.96) 0%, rgba(16, 28, 46, 0.95) 100%);
        --dash-border: rgba(146, 176, 223, 0.26);
        --dash-soft-text: #b4c6e4;
        --dash-strong-text: #e9f2ff;
        --dash-accent: #5aa5ff;
        --dash-accent-soft: rgba(90, 165, 255, 0.24);
        --dash-shadow: 0 18px 38px rgba(0, 0, 0, 0.36);
        --dash-table-wrap-bg: linear-gradient(180deg, rgba(28, 44, 69, 0.86) 0%, rgba(22, 36, 58, 0.88) 100%);
        --dash-table-head-bg: linear-gradient(180deg, rgba(42, 66, 103, 0.9) 0%, rgba(32, 54, 87, 0.92) 100%);
        --dash-table-row-alt: rgba(90, 165, 255, 0.1);
        --dash-table-hover: rgba(90, 165, 255, 0.22);
    }

    body.dark .dashboard-modern .dashboard-hero {
        background: linear-gradient(115deg, rgba(30, 50, 79, 0.96) 0%, rgba(20, 37, 61, 0.96) 55%, rgba(18, 32, 56, 0.96) 100%);
    }

    body.dark .dashboard-modern .card-header {
        background: linear-gradient(180deg, rgba(29, 46, 74, 0.85) 0%, rgba(22, 36, 58, 0.7) 100%);
    }

    body.dark .dashboard-modern .table td,
    body.dark .dashboard-modern .table th {
        border-color: color-mix(in srgb, var(--dash-border) 85%, transparent);
    }

    body.dark .dashboard-modern .table-responsive {
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
    }

    body.dark .dashboard-modern .badge {
        background: rgba(67, 124, 196, 0.2);
        color: var(--dash-strong-text);
        border-color: rgba(140, 178, 235, 0.35);
    }

    body.dark .dashboard-modern .badge.bg-light {
        background: linear-gradient(180deg, rgba(53, 82, 126, 0.85) 0%, rgba(42, 66, 104, 0.86) 100%) !important;
        color: #e9f2ff !important;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.22);
        border-color: rgba(150, 188, 245, 0.45);
    }

    body.dark .dashboard-modern .badge.bg-primary {
        background: linear-gradient(135deg, #2e86ff 0%, #58adff 100%) !important;
        border-color: rgba(160, 206, 255, 0.52);
        box-shadow: 0 8px 18px rgba(46, 134, 255, 0.34);
    }

    body.dark .dashboard-modern .btn-outline-primary {
        color: #8ec1ff;
        border-color: rgba(142, 193, 255, 0.45);
        background: rgba(53, 112, 183, 0.2);
    }

    body.dark .dashboard-modern .alert {
        background: rgba(47, 82, 130, 0.2);
    }

    @media (max-width: 768px) {
        .main-content.dashboard-modern {
            border-radius: 14px 14px 0 0;
            padding-bottom: 12px;
        }

        .dashboard-modern .dashboard-hero {
            padding: 14px 14px;
            margin-bottom: 12px;
        }

        .dashboard-modern .dashboard-hero h1 {
            font-size: 1.05rem;
        }

        .dashboard-modern .card-header,
        .dashboard-modern .card .card-body {
            padding: 12px;
        }

        .dashboard-modern .btn {
            margin-bottom: 6px;
        }
    }
</style>
@endpush

@section('content')
<div class="main-content dashboard-modern">
    <div class="dashboard-hero">
        <h1>Dashboard</h1>
        <p>Informasi Umum kegiatan PLP disajikan sebagai berikut</p>
    </div>
    @if(auth()->user()->can('active-read') || auth()->user()->hasRole('admin'))
        @includeWhen(auth()->user()->can('dashboard/ketua-read') || auth()->user()->hasRole('admin'),'dashboard.chairman')
        @includeWhen(auth()->user()->can('dashboard/kajur-read'),'dashboard.departement')
        @includeWhen(auth()->user()->can('dashboard/dosen-read'),'dashboard.lecture')
        @includeWhen(auth()->user()->can('dashboard/mahasiswa-read'),'dashboard.student')
        @includeWhen(auth()->user()->can('dashboard/guru-read'),'dashboard.teacher')
        @includeWhen(auth()->user()->canAny('dashboard/kepsek-read'),'dashboard.headmaster')
        @includeWhen(auth()->user()->can('dashboard/korguru-read'),'dashboard.teachercoordinator')
    @else
        <div class="dashboard-inactive">
            Saat ini akun anda tidak aktif, silakan hubungi panitia PLP.
        </div>
    @endif
</div>
@endsection
