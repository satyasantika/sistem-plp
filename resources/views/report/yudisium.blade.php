@extends('layouts.master')

@include('report.partials.yudisium-datatable-assets')

@section('content')
<div class="main-content">
    <div class="title">
        {{ Str::ucFirst(request()->segment(1)) }} PLP {{ $plp_order }}
    </div>
    @includeWhen(auth()->user()->hasAnyRole('ketua','dekanat') && !empty($dekanatSummary), 'report.yudisium-dekanat', [
        'plp_order' => $plp_order,
        'dekanatSummary' => $dekanatSummary,
    ])
    @includeWhen(auth()->user()->hasRole('kajur'), 'report.yudisium-jurusan', [
        'plp_order' => $plp_order,
        'jurusanRows' => $jurusanRows,
        'lectureForms' => $lectureForms,
        'teacherForms' => $teacherForms,
    ])
</div>
@endsection
