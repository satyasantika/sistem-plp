@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Hasil Mapping Jurusan
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Kuota dan Hasil Pemetaan Mahasiswa pada PLP Tahun 2022</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                @php
                                    $subjects = App\Models\Map::select('subject_id')->groupBy('subject_id')->orderBy('subject_id')->get();
                                @endphp
                                <thead>
                                    <tr>
                                        <th>Sekolah</th>
                                        @foreach ($subjects as $subject)
                                            <th>{{ $subject->subjects->id }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maps = App\Models\Map::select('school_id')->groupBy('school_id')->get();
                                    @endphp
                                    @foreach ($maps as $map)
                                    <tr>
                                        <td>{{ $map->schools->name ?? '' }}</td>
                                        @foreach ($subjects as $subject)
                                            @php
                                                $quota = App\Models\Map::where('subject_id',$subject->subjects->id)
                                                            ->where('school_id',$map->school_id)
                                                            ->count();
                                                $filled = App\Models\Map::where('subject_id',$subject->subjects->id)
                                                            ->where('school_id',$map->school_id)
                                                            ->whereNotNull('student_id')
                                                            ->count();
                                            @endphp
                                            <td class="{{ $filled < $quota ? 'text-danger' : '' }}">
                                                {{ $filled ?? '' }} /
                                                {{ $quota ?? '' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>Hasil Mapping Setiap DPL</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($subjects as $subject)
                        @php
                            $quota = App\Models\Map::where('subject_id',$subject->subjects->id)->count();
                            $filled = App\Models\Map::where('subject_id',$subject->subjects->id)->whereNotNull('student_id')->count();
                            $percent = round($filled/$quota * 100,2)
                        @endphp
                        <div class="accordion accordion-space" id="accordion{{ $subject->subjects->id }}">
                            <div class="accordion-item">
                                <div class="progress-wrapper">
                                    <div class="progress progress-bar-small">
                                        <div class="progress-bar progress-bar-small" style="width: {{ $percent.'%' }}" role="progressbar"
                                            aria-valuenow="{{ $filled }}" aria-valuemin="0" aria-valuemax="$quota">
                                        </div>
                                    </div>
                                </div>
                                <h2 class="accordion-header" id="heading{{ $subject->subjects->id }}">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $subject->subjects->id }}"
                                        aria-expanded="true" aria-controls="collapse{{ $subject->subjects->id }}">
                                        DPL  {{ $subject->subjects->departement }} (Progress {{ $percent.'%' }})
                                    </button>
                                </h2>
                                <div id="collapse{{ $subject->subjects->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $subject->subjects->id }}" data-bs-parent="#accordion{{ $subject->subjects->id }}">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table small-font table-striped table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Nama</th>
                                                        <th>Bimbingan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $lectures = App\Models\User::role('dosen')->where('subject_id',$subject->subjects->id)->get()
                                                    @endphp
                                                    @foreach ($lectures as $lecture)
                                                    <tr>
                                                        <td>{{ $lecture->name ?? '' }}</td>
                                                        @php
                                                            $maps = App\Models\Map::select('school_id', DB::raw('count(student_id) as total'))
                                                                            ->where('lecture_id',$lecture->id)
                                                                            ->groupBy('school_id')->get();
                                                        @endphp
                                                        <td>
                                                            @foreach ($maps as $map)
                                                                <span class="badge bg-light rounded-pill text-dark">
                                                                    {{ $map->schools->name ?? '' }}
                                                                    <span class="badge bg-primary rounded-pill">
                                                                        {{ $map->total ?? '' }}
                                                                    </span>
                                                                </span>
                                                            @endforeach
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
            </div>
        </div>
    </div>
</div>
@endsection
