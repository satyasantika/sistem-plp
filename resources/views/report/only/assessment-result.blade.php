@extends('layouts.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Progress Penilaian PLP
    </div>
    <div class="content-wrapper">
        <div class="row">
            @includeWhen(auth()->user()->hasAnyRole('data','kajur'), 'report.only.assessment-result-departement')
            @can('plp2-read')
                @includeWhen(auth()->user()->hasAnyRole('kepsek','korguru','data'), 'report.only.assessment-result-school')
            @endcan
        </div>
    </div>
</div>
@endsection
