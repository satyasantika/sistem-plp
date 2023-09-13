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
                                            <span class="badge bg-{{ isset($school->headmasters->golongan) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->headmasters->golongan) ? 'Gol: '.$school->headmasters->golongan : 'golongan?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($school->headmasters->npwp) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->headmasters->npwp) ? 'npwp: '.$school->headmasters->npwp : 'npwp?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($school->headmasters->nomor_rekening) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->headmasters->nomor_rekening) ? 'norek: '.$school->headmasters->nomor_rekening : 'nomor_rekening?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($school->headmasters->bank) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->headmasters->bank) ? $school->headmasters->bank : 'bank?' }}
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
                                            <span class="badge bg-{{ isset($school->coordinators->golongan) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->coordinators->golongan) ? 'Gol: '.$school->coordinators->golongan : 'golongan?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($school->coordinators->npwp) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->coordinators->npwp) ? 'npwp: '.$school->coordinators->npwp : 'npwp?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($school->coordinators->nomor_rekening) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->coordinators->nomor_rekening) ? 'norek: '.$school->coordinators->nomor_rekening : 'nomor_rekening?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($school->coordinators->bank) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($school->coordinators->bank) ? $school->coordinators->bank : 'bank?' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @php
                                        // List guru
                                        $school_maps = App\Models\Map::select('teacher_id','school_id')
                                                                        ->where('year',2023)
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
                                            <span class="badge bg-{{ isset($user->teachers->golongan) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($user->teachers->golongan) ? 'Gol: '.$user->teachers->golongan : 'golongan?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($user->teachers->npwp) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($user->teachers->npwp) ? 'npwp: '.$user->teachers->npwp : 'npwp?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($user->teachers->nomor_rekening) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($user->teachers->nomor_rekening) ? 'norek: '.$user->teachers->nomor_rekening : 'nomor_rekening?' }}
                                            </span>
                                            <span class="badge bg-{{ isset($user->teachers->bank) ? 'light text-dark' : 'danger' }} rounded-pill">
                                                {{ isset($user->teachers->bank) ? $user->teachers->bank : 'bank?' }}
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
