@php
    $lecture_forms = ['2024N2','2024N6','2024N7'];
    $teacher_forms = ['2024N1','2024N3','2024N4','2024N5','2024N6','2024N7'];

@endphp
<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian PLP Jurusan {{ auth()->user()->subjects->departement }}</h5>
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
                                                            'maps.year'=>2025,
                                                            'maps.subject_id'=>auth()->user()->subject_id
                                                        ])
                                                        ->whereNotNull('maps.student_id')
                                                        ->orderBy('users.name')
                                                        ->select('maps.id','maps.student_id','maps.lecture_id','maps.teacher_id','maps.grade','maps.letter')
                                                        ->get();
                                    @endphp
                                    @foreach($maps as $map)
                                    <tr>
                                        {{-- Mahasiswa --}}
                                        <td>
                                            {{ $map->students->name ?? '' }}
                                        </td>
                                        {{-- Nilai --}}
                                        @if (App\Models\Assessment::where('assessor','dosen')
                                                                                    ->orWhere('assessor','guru')
                                                                                    ->where('map_id',$map->id)
                                                                                    ->exists())
                                        <td class="text-center">
                                            @php
                                                $status = 'danger';
                                                $grade = $map->grade;
                                                $letter = $map->letter;

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
                                            <br>
                                            @foreach ($teacher_forms as $form)
                                            @php
                                                $grade_sum = 0;
                                                $form_times = 1;
                                                $teacher_assessment_by_form = App\Models\Assessment::where('form_id',$form)
                                                                                    ->where('assessor','guru')
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
