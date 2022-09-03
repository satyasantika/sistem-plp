@extends('layouts.master')

@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('') }}vendor/chart.js/dist/Chart.min.css"> --}}

@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Dashboard
    </div>
    @can('dashboard/kajur-read')
    @include('dashboard.departement')
    @endcan
    @can('dashboard/dosen-read')
    @include('dashboard.lecture')
    @endcan
    @can('dashboard/mahasiswa-read')
    @include('dashboard.student')
    @endcan
</div>
@endsection

@push('js')
    {{-- <script src="{{ asset('') }}vendor/chart.js/dist/Chart.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    {{-- <script src="{{ asset('') }}assets/js/page/index.js"></script> --}}
@endpush
