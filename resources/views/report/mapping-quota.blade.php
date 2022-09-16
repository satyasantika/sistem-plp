@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        Distribusi Peserta
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Sebaran Peserta PLP 1 Tahun 2022</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                @php
                                    $subjects = App\Models\Map::where('year',2022)->where('plp1',1)->select('subject_id')
                                                        ->groupBy('subject_id')
                                                        ->orderBy('subject_id')
                                                        ->get();
                                @endphp
                                <thead>
                                    <tr>
                                        <th>Sekolah</th>
                                        <th>Total</th>
                                        @foreach ($subjects as $subject)
                                            <th class="text-end">{{ $subject->subjects->id }}</th>
                                        @endforeach
                                    </tr>
                                    <tr class="text-primary">
                                        <th></th>
                                        <th>{{ App\Models\Map::where('year',2022)->where('plp1',1)->count() }}</th>
                                        @foreach ($subjects as $subject)
                                            @php
                                                $subject_quota = App\Models\Map::where('year',2022)->where('plp1',1)
                                                                                ->where('subject_id',$subject->subject_id)
                                                                                ->count();
                                            @endphp
                                            <th class="text-end">{{ $subject_quota }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maps = App\Models\Map::where('year',2022)->where('plp1',1)
                                                                ->select('school_id')
                                                                ->groupBy('school_id')
                                                                ->get();
                                    @endphp
                                    @foreach ($maps as $map)
                                    <tr>
                                        <td>{{ $map->schools->name ?? '' }}</td>
                                        @php
                                            $school_quota = App\Models\Map::where('year',2022)->where('plp1',1)
                                                                            ->where('school_id',$map->school_id)
                                                                            ->count();
                                        @endphp
                                        <th class="text-primary">{{ $school_quota }}</th>
                                        @foreach ($subjects as $subject)
                                            @php
                                                $subject_quota = App\Models\Map::where('year',2022)->where('plp1',1)
                                                                                ->where('subject_id',$subject->subjects->id)
                                                                                ->where('school_id',$map->school_id)
                                                                                ->count();
                                            @endphp
                                            <td class="text-end">{{ $subject_quota ?? '' }}</td>
                                        @endforeach

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Sebaran Peserta PLP 2 Tahun 2022</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                @php
                                    $subjects = App\Models\Map::where('year',2022)->where('plp2',1)->select('subject_id')
                                                        ->groupBy('subject_id')
                                                        ->orderBy('subject_id')
                                                        ->get();
                                @endphp
                                <thead>
                                    <tr>
                                        <th>Sekolah</th>
                                        <th>Total</th>
                                        @foreach ($subjects as $subject)
                                            <th class="text-end">{{ $subject->subjects->id }}</th>
                                        @endforeach
                                    </tr>
                                    <tr class="text-primary">
                                        <th></th>
                                        <th>{{ App\Models\Map::where('year',2022)->where('plp2',1)->count() }}</th>
                                        @foreach ($subjects as $subject)
                                            @php
                                                $subject_quota = App\Models\Map::where('year',2022)->where('plp2',1)
                                                                                ->where('subject_id',$subject->subject_id)
                                                                                ->count();
                                            @endphp
                                            <th class="text-end">{{ $subject_quota }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maps = App\Models\Map::where('year',2022)->where('plp2',1)
                                                                ->select('school_id')
                                                                ->groupBy('school_id')
                                                                ->get();
                                    @endphp
                                    @foreach ($maps as $map)
                                    <tr>
                                        <td>{{ $map->schools->name ?? '' }}</td>
                                        @php
                                            $school_quota = App\Models\Map::where('year',2022)->where('plp2',1)
                                                                            ->where('school_id',$map->school_id)
                                                                            ->count();
                                        @endphp
                                        <th class="text-primary">{{ $school_quota }}</th>
                                        @foreach ($subjects as $subject)
                                            @php
                                                $subject_quota = App\Models\Map::where('year',2022)->where('plp2',1)
                                                                                ->where('subject_id',$subject->subjects->id)
                                                                                ->where('school_id',$map->school_id)
                                                                                ->count();
                                            @endphp
                                            <td class="text-end">{{ $subject_quota ?? '' }}</td>
                                        @endforeach

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
