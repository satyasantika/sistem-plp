@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Dashboard
    </div>
    @includeWhen(auth()->user()->can('dashboard/ketua-read'),'dashboard.chairman')
    @includeWhen(auth()->user()->can('dashboard/kajur-read'),'dashboard.departement')
    @includeWhen(auth()->user()->can('dashboard/dosen-read'),'dashboard.lecture')
    @includeWhen(auth()->user()->can('dashboard/mahasiswa-read'),'dashboard.student')
    @includeWhen(auth()->user()->can('dashboard/guru-read'),'dashboard.teacher')
    @includeWhen(auth()->user()->can('dashboard/kepsek-read'),'dashboard.headmaster')
    @includeWhen(auth()->user()->can('dashboard/korguru-read'),'dashboard.coordinator')
</div>
@endsection
