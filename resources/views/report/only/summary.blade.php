@extends('layouts.master')

@section('content')
<div class="main-content">
    <div class="title">
        {{ Str::ucFirst(request()->segment(2)) }}
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-auto">
                <div class="card">
                    <div class="card-header">
                        <h5>Rekap data PLP</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                @php
                                    $subjects = App\Models\Subject::all();
                                @endphp
                                <thead>
                                    <tr>
                                        <th>Jurusan</th>
                                        <th class="text-end">Mahasiswa</th>
                                        <th class="text-end">DPL</th>
                                        <th class="text-end">GP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                    @continue($subject->id == '03')
                                    <tr>
                                        <th>{{ $subject->name }}</th>
                                        @php
                                            $student_plp = App\Models\Map::where('year',2025)
                                                                ->where('plp',1)
                                                                ->where('subject_id',$subject->id)
                                                                ->whereNotNull('student_id')
                                                                ->count();
                                        @endphp
                                        <td class="text-end">{{ $student_plp }}</td>
                                        @php
                                            $lecture = App\Models\Map::where('year',2025)
                                                                ->where('subject_id',$subject->id)
                                                                ->whereNotNull('lecture_id')
                                                                ->select('lecture_id')
                                                                ->groupBy('lecture_id')
                                                                ->get()
                                                                ->count();
                                        @endphp
                                        <td class="text-end">{{ $lecture }}</td>
                                        @php
                                            $teacher = App\Models\Map::where('year',2025)
                                                                ->where('subject_id',$subject->id)
                                                                ->whereNotNull('teacher_id')
                                                                ->select('teacher_id')
                                                                ->groupBy('teacher_id')
                                                                ->get()
                                                                ->count();
                                        @endphp
                                        <td class="text-end">{{ $teacher }}</td>
                                    </tr>
                                    @endforeach
                                    <tr class="text-primary">
                                        <th>Total:</th>
                                        <th class="text-end">{{ App\Models\Map::where('year',2025)
                                                                                ->whereNotNull('student_id')
                                                                                ->where('plp',1)
                                                                                ->count() }}</th>
                                        <th class="text-end">{{ App\Models\Map::where('year',2025)
                                                                                ->whereNotNull('lecture_id')
                                                                                ->select('lecture_id')
                                                                                ->groupBy('lecture_id')
                                                                                ->get()
                                                                                ->count() }}</th>
                                        <th class="text-end">{{ App\Models\Map::where('year',2025)
                                                                                ->whereNotNull('teacher_id')
                                                                                ->select('teacher_id')
                                                                                ->groupBy('teacher_id')
                                                                                ->get()
                                                                                ->count() }}</th>
                                    </tr>
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
