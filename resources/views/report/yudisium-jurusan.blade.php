@php
    if ($plp_order == 1) {
        $lecture_forms = ['2023N2','2023N8'];
    } else {
        $lecture_forms = ['2023N2','2023N6','2023N7'];
    }
    $teacher_forms = ['2023N1','2023N3','2023N4','2002N5','2023N6','2023N7'];

@endphp
<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian PLP {{ $plp_order }} Jurusan {{ auth()->user()->subjects->departement }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                            <table class="table small-font table-striped table-hover table-sm" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th>Mahasiswa</th>
                                        <th class="text-center">Nilai</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maps = App\Models\Map::join('users','users.id','maps.student_id')
                                                        ->where([
                                                            'year'=>2023,
                                                            request()->segment(2)=>1,
                                                            'maps.subject_id'=>auth()->user()->subject_id
                                                        ])->orderBy('users.name')
                                                        ->select('maps.id','maps.student_id','maps.lecture_id','maps.teacher_id')
                                                        ->get();
                                    @endphp
                                    @foreach($maps as $map)
                                    <tr>
                                        <td>
                                            {{ $map->students->name ?? '' }}
                                        </td>
                                        @php
                                            $count_form = 0;
                                            if ($plp_order == 1) {
                                                // TODO: untuk PLP 1,
                                                $total_grade = App\Models\Assessment::where('assessor','dosen')
                                                                                    ->where('plp_order',$plp_order)
                                                                                    ->where('map_id',$map->id)
                                                                                    ->sum('grade');
                                                $grade = $total_grade/count($lecture_forms);

                                            } else {
                                                // TODO: untuk PLP 2,
                                                // penilaian dari dosen
                                                $assessment_by_lecture = App\Models\Assessment::where('assessor','dosen')
                                                                                                ->where('plp_order',$plp_order)
                                                                                                ->where('map_id',$map->id)
                                                                                                ->whereIn('form_id',$lecture_forms)
                                                                                                ->sum('grade');
                                                $lecture_form_times = App\Models\Form::whereIn('id',$lecture_forms)->sum('times');
                                                $lecture_total = round($assessment_by_lecture/$lecture_form_times,0);
                                                // penilaian dari guru
                                                $assessment_by_teacher = App\Models\Assessment::where('assessor','guru')
                                                                                    ->where('plp_order',$plp_order)
                                                                                    ->where('map_id',$map->id)
                                                                                    ->whereIn('form_id',$teacher_forms)
                                                                                    ->sum('grade');
                                                $teacher_form_times = App\Models\Form::whereIn('id',$teacher_forms)->sum('times');
                                                $teacher_total = round($assessment_by_teacher/$teacher_form_times,0);

                                                $grade = 0.4 * $lecture_total + 0.6 * $teacher_total;
                                            }

                                        @endphp
                                        @if (App\Models\Assessment::where('assessor','dosen')
                                                                                    ->where('plp_order',$plp_order)
                                                                                    ->where('map_id',$map->id)
                                                                                    ->exists())
                                        @php
                                            if ($grade < 56) {
                                                $letter = 'E';
                                            } elseif ($grade < 66) {
                                                $letter = 'D';
                                            } elseif ($grade < 76) {
                                                $letter = 'C';
                                            } elseif ($grade < 86) {
                                                $letter = 'B';
                                            } else {
                                                $letter = 'A';
                                            }
                                        @endphp
                                        <td class="text-center">
                                            @php
                                                $status = 'danger';
                                                if($grade >= 86){
                                                    $status = 'primary';
                                                }elseif ($grade >= 76) {
                                                    $status = 'dark';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $status }}">
                                                {{ $letter }} <span class="badge bg-light text-dark rounded-pill">{{ round($grade,1) }}</span>
                                            </span>
                                        </td>
                                        @else
                                        <td></td>
                                        @endif
                                        <td>
                                            @foreach ($lecture_forms as $form)
                                            @php
                                                $grade_sum = 0;
                                                $lecture_assessment_by_form = App\Models\Assessment::where('form_id',$form)
                                                                                    ->where('assessor','dosen')
                                                                                    ->where('plp_order',$plp_order)
                                                                                    ->where('map_id',$map->id)
                                                                                    ;
                                                if ($lecture_assessment_by_form->exists()) {
                                                    $grade_sum = $lecture_assessment_by_form->sum('grade');
                                                    $form_times = App\Models\Form::find($form)->times;
                                                    $grade_sum = round($grade_sum/$form_times,0);
                                                }
                                            @endphp
                                            <span class="badge bg-light text-primary">
                                                {{ substr($form,-2) }} <span class="badge bg-light text-dark rounded-pill">{{ $grade_sum }}</span>
                                            </span>
                                            @endforeach
                                            <span class="badge bg-light text-primary rounded-pill">{{ $map->lectures->name }}</span>
                                            @if ($plp_order == 2)
                                                <br>
                                                @foreach ($teacher_forms as $form)
                                                @php
                                                    $grade_sum = 0;
                                                    $form_times = 0;
                                                    $teacher_assessment_by_form = App\Models\Assessment::where('form_id',$form)
                                                                                        ->where('assessor','guru')
                                                                                        ->where('plp_order',$plp_order)
                                                                                        ->where('map_id',$map->id)
                                                                                        ;
                                                    if ($teacher_assessment_by_form->exists()) {
                                                        $grade_sum = $teacher_assessment_by_form->sum('grade');
                                                        $form_times = App\Models\Form::find($form)->times;
                                                        // $grade_sum = round($grade_sum/$form_times,0);
                                                    }
                                                    if ($form = '2023N4') {
                                                        dd($grade_sum);
                                                    }
                                                @endphp
                                                <span class="badge bg-light text-success">
                                                    {{ substr($form,-2) }} <span class="badge bg-light text-dark rounded-pill">{{ $grade_sum }}</span>
                                                </span>
                                                @endforeach
                                                <span class="badge bg-light text-success rounded-pill">{{ $map->teachers->name }}</span>
                                            @endif
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
    </div>
</div>
