@extends('layouts.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@php
    $schools = App\Models\School::all();
    if (auth()->user()->hasAnyRole('kepsek','korguru')) {
        $id = auth()->user()->id;
        $schools = App\Models\School::where('headmaster_id',$id)->orWhere('coordinator_id',$id)->get();
    }
@endphp

@section('content')
<div class="main-content">
    <div class="title">
        Progress Pengisian Profil GP, Kepsek, & Koordinator GP
    </div>
    <div class="content-wrapper">
        @foreach ($schools as $school)
        <div class="row">
            <div class="col-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>Pengisian Profil dari {{ $school->name }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Identitas</th>
                                        <th class="text-end">Profil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Kepala Sekolah --}}
                                    <tr>
                                        <td>
                                            @if (isset($school->headmasters->phone))
                                                <a href="{{ 'http://wa.me/62'.$school->headmasters->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                            @endif

                                            {{ $school->headmasters->name ?? '' }}
                                            <span class="badge bg-primary">KepSek</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ isset($school->headmasters->golongan) ? 'success' : 'danger' }} rounded-pill">
                                                golongan
                                            </span>
                                            <span class="badge bg-{{ isset($school->headmasters->npwp) ? 'success' : 'danger' }} rounded-pill">
                                                npwp
                                            </span>
                                            <span class="badge bg-{{ isset($school->headmasters->nomor_rekening) ? 'success' : 'danger' }} rounded-pill">
                                                nomor_rekening
                                            </span>
                                            <span class="badge bg-{{ isset($school->headmasters->bank) ? 'success' : 'danger' }} rounded-pill">
                                                bank
                                            </span>
                                        </td>
                                    </tr>
                                    {{-- Koordinator Guru Pamong --}}
                                    <tr>
                                        <td>
                                            @if (isset($school->coordinators->phone))
                                                <a href="{{ 'http://wa.me/62'.$school->coordinators->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                            @endif

                                            {{ $school->coordinators->name ?? '' }}
                                            <span class="badge bg-primary">KGP</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ isset($school->coordinators->golongan) ? 'success' : 'danger' }} rounded-pill">
                                                golongan
                                            </span>
                                            <span class="badge bg-{{ isset($school->coordinators->npwp) ? 'success' : 'danger' }} rounded-pill">
                                                npwp
                                            </span>
                                            <span class="badge bg-{{ isset($school->coordinators->nomor_rekening) ? 'success' : 'danger' }} rounded-pill">
                                                nomor_rekening
                                            </span>
                                            <span class="badge bg-{{ isset($school->coordinators->bank) ? 'success' : 'danger' }} rounded-pill">
                                                bank
                                            </span>
                                        </td>
                                    </tr>
                                    @php
                                        // List guru
                                        $school_maps = App\Models\Map::select('teacher_id','school_id')
                                                                        ->where('year',2022)
                                                                        ->where('plp2',1)
                                                                        ->where('school_id',$school->id)
                                                                        ->groupBy('teacher_id','school_id')
                                                                        ->get();
                                    @endphp
                                    @foreach ($school_maps as $user)
                                    <tr>
                                        <td>
                                            @if (isset($user->teachers->phone))
                                                <a href="{{ 'http://wa.me/62'.$user->teachers->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                            @endif

                                            {{ $user->teachers->name ?? '' }}
                                            <span class="badge bg-dark rounded-pill">GP</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge bg-{{ isset($user->teachers->golongan) ? 'success' : 'danger' }} rounded-pill">
                                                golongan
                                            </span>
                                            <span class="badge bg-{{ isset($user->teachers->npwp) ? 'success' : 'danger' }} rounded-pill">
                                                npwp
                                            </span>
                                            <span class="badge bg-{{ isset($user->teachers->nomor_rekening) ? 'success' : 'danger' }} rounded-pill">
                                                nomor_rekening
                                            </span>
                                            <span class="badge bg-{{ isset($user->teachers->bank) ? 'success' : 'danger' }} rounded-pill">
                                                bank
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
