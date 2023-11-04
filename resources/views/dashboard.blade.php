@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Dashboard
    </div>
    @if(auth()->user()->can('active-read'))
        @includeWhen(auth()->user()->can('dashboard/ketua-read'),'dashboard.chairman')
        @includeWhen(auth()->user()->can('dashboard/kajur-read'),'dashboard.departement')
        @includeWhen(auth()->user()->can('dashboard/dosen-read'),'dashboard.lecture')
        @includeWhen(auth()->user()->can('dashboard/mahasiswa-read'),'dashboard.student')
        @includeWhen(auth()->user()->can('dashboard/guru-read'),'dashboard.teacher')
        @includeWhen(auth()->user()->canany('dashboard/kepsek-read',),'dashboard.headmaster')
        @includeWhen(auth()->user()->can('dashboard/korguru-read'),'dashboard.teachercoordinator')
    @else
        <h1>Saat ini akun anda tidak aktif, silakan hubungi panitia PLP</h1>
    @endif
</div>
@endsection
