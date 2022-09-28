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
            @php
                $subjects = App\Models\Subject::whereNot('id','03')->get();
            @endphp
            <div class="col-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>Hasil Penilaian DPL</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($subjects as $subject)
                        @php
                            $quota = App\Models\Map::where([
                                                        'year'=>2022,
                                                        request()->segment(3)=>1,
                                                        'subject_id'=>$subject->id,
                                                    ])->whereNotNull('student_id')
                                                    ->get();
                            $assessed = 0;
                            foreach ($quota as $map) {
                                if (App\Models\Assessment::where([
                                                                    'map_id'=>$map->id,
                                                                    'plp_order'=>$plp_order,
                                                                    'assessor' => 'dosen'
                                                                ])->exists()) {
                                    $assessed += 1;
                                } else {
                                    continue;
                                }
                            }
                            $percent = round($assessed/$quota->count() * 100,2)
                        @endphp
                        <div class="accordion mb-3" id="departement-accordion">
                            <div class="accordion-item">
                                <div class="progress-wrapper">
                                    <div class="progress progress-bar-small">
                                        <div class="progress-bar progress-bar-small" style="width: {{ $percent.'%' }}" role="progressbar"
                                            aria-valuenow="{{ $assessed }}" aria-valuemin="0" aria-valuemax="$quota">
                                        </div>
                                    </div>
                                </div>
                                <h2 class="accordion-header" id="heading{{ $subject->id }}">
                                    <button class="accordion-button collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#collapse{{ $subject->id }}"
                                        aria-expanded="false" aria-controls="collapse{{ $subject->id }}">
                                        {{ $subject->name }} (Progress {{ $percent.'%' }})
                                    </button>
                                </h2>
                                <div id="collapse{{ $subject->id }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $subject->id }}" data-bs-parent="#departement-accordion">
                                    <div class="accordion-body">
                                        <div class="table-responsive">
                                            <table class="table small-font table-striped table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>DPL</th>
                                                        <th>Progress</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $lectures = App\Models\User::role('dosen')
                                                                                    ->where('subject_id',$subject->id)
                                                                                    ->orderBy('name')
                                                                                    ->get()
                                                    @endphp
                                                    @foreach ($lectures as $lecture)
                                                    <tr>
                                                        <td>
                                                            @if ($lecture->phone)
                                                                <a href="{{ 'http://wa.me/62'.$lecture->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                                            @endif

                                                            {{ $lecture->name ?? '' }}
                                                        </td>
                                                        @php
                                                            $quota = App\Models\Map::where([
                                                                                        'lecture_id'=>$lecture->id,
                                                                                        'year'=>2022,
                                                                                        request()->segment(3)=>1,
                                                                                        'subject_id'=>$subject->id,
                                                                                    ])->whereNotNull('student_id')
                                                                                    ->get();
                                                            $assessed = 0;
                                                            foreach ($quota as $map) {
                                                                if (App\Models\Assessment::where([
                                                                                            'map_id'=>$map->id,
                                                                                            'plp_order'=>$plp_order,
                                                                                            'assessor' => 'dosen'
                                                                                        ])->exists())
                                                                {
                                                                    $assessed += 1;
                                                                } else {
                                                                    continue;
                                                                }
                                                            }
                                                            $percent = round($assessed/$quota->count() * 100,2)
                                                        @endphp
                                                        <td class="text-end">{{ $percent.'%' }}</td>
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
