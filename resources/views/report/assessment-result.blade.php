@extends('layouts.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Progress Penilaian PLP {{ $plp_order }}
    </div>
    <div class="content-wrapper">
        <div class="row">
            @includeWhen(auth()->user()->hasAnyRole('data'), 'report.assessment-result-departement', ['plp_order' => $plp_order ])
            @includeWhen(auth()->user()->hasAnyRole('kepsek','korguru','data'), 'report.assessment-result-school', ['plp_order' => $plp_order ])
            {{-- @include('report.assessment-result-school', ['plp_order' => $plp_order ]) --}}
        </div>
    </div>
</div>
@endsection
