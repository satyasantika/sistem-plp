@php
    if ($plp_order == 1) {
        $lecture_forms = ['2022N2','2022N8'];
    } else {
        $lecture_forms = ['2022N2','2022N6','2022N7'];
    }
    $teacher_forms = ['2022N1','2022N3','2022N4','2022N5','2022N6','2022N7'];

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
                                                            'maps.year'=>2023,
                                                            request()->segment(2)=>1,
                                                            'maps.subject_id'=>auth()->user()->subject_id
                                                        ])
                                                        ->whereNotNull('maps.student_id')
                                                        ->orderBy('users.name')
                                                        ->select('maps.id','maps.student_id','maps.lecture_id','maps.teacher_id','maps.grade1','maps.letter1','maps.grade2','maps.letter2')
                                                        ->get();
                                    @endphp
                                    @foreach($maps as $map)
                                    <tr>
                                        <td>
                                            {{ $map->students->name ?? '' }}
                                        </td>
                                        @if (App\Models\Assessment::where('assessor','dosen')
                                                                                    ->where('plp_order',$plp_order)
                                                                                    ->where('map_id',$map->id)
                                                                                    ->exists())
                                        <td class="text-center">
                                            @php
                                                $status = 'danger';
                                                if ($plp_order == 1) {
                                                    $grade = $map->grade1;
                                                    $letter = $map->letter1;
                                                } else {
                                                    $grade = $map->grade2;
                                                    $letter = $map->letter2;
                                                }

                                                if($grade >= 85){
                                                    $status = 'primary';
                                                }elseif ($grade >= 61) {
                                                    $status = 'dark';
                                                }
                                            @endphp
                                            <span class="badge bg-{{ $status }}">
                                                {{ $letter }} <span class="badge bg-light text-dark rounded-pill">{{ round($grade,2) }}</span>
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
                                                    $grade_sum = round($grade_sum/$form_times,2);
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
                                                    $form_times = 1;
                                                    $teacher_assessment_by_form = App\Models\Assessment::where('form_id',$form)
                                                                                        ->where('assessor','guru')
                                                                                        ->where('plp_order',$plp_order)
                                                                                        ->where('map_id',$map->id)
                                                                                        ;
                                                    if ($teacher_assessment_by_form->exists()) {
                                                        $grade_sum = $teacher_assessment_by_form->sum('grade');
                                                        $form_times = App\Models\Form::find($form)->times;
                                                        $grade_sum = round($grade_sum/$form_times,2);
                                                    }
                                                @endphp
                                                <span class="badge bg-light text-success">
                                                    {{ substr($form,-2) }} <span class="badge bg-light text-dark rounded-pill">{{ $grade_sum }}</span>
                                                </span>
                                                @endforeach
                                                <span class="badge bg-light text-success rounded-pill">{{ $map->teachers->name ?? "belum diset" }}</span>
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
