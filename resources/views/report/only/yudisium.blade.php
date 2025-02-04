@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Yudisium PLP
    </div>
    @includeWhen(auth()->user()->hasAnyRole('ketua','dekanat'), 'report.only.yudisium-dekanat')
    @includeWhen(auth()->user()->hasRole('kajur'), 'report.only.yudisium-jurusan')
</div>
@endsection
