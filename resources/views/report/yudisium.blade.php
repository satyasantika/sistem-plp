@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        {{ Str::ucFirst(request()->segment(1)) }} PLP {{ $plp_order }}
    </div>
    @includeWhen(auth()->user()->hasAnyRole('ketua','dekanat'), 'report.yudisium-dekanat', ['plp_order' => $plp_order ])
    @includeWhen(auth()->user()->hasRole('kajur'), 'report.yudisium-jurusan', ['plp_order' => $plp_order ])
</div>
@endsection
