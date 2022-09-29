@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        {{ Str::ucFirst(request()->segment(1)) }} PLP {{ $plp_order }}
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>Rekap Hasil Penilaian PLP {{ $plp_order }}</h5>
                    </div>
                    <div class="card-body">
                        @includeWhen(auth()->user()->hasAnyRole('ketua','dekanat'), 'report.yudisium-dekanat', ['plp_order' => $plp_order ])
                        @includeWhen(auth()->user()->hasRole('kajur'), 'report.yudisium-jurusan', ['plp_order' => $plp_order ])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
