<div class="table-responsive">
    <table class="table small-font table-striped table-hover table-sm">
        @php
            $subjects = App\Models\Subject::all();
        @endphp
        <thead>
            <tr>
                <th>Jurusan</th>
                <th class="text-end">Peserta</th>
                <th class="text-end">A</th>
                <th class="text-end">B</th>
                <th class="text-end">C</th>
                <th class="text-end">D</th>
                <th class="text-end">E</th>
                <th class="text-end">Belum Dinilai</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total_student = 0;
                $total_A = 0;
                $total_B = 0;
                $total_C = 0;
                $total_D = 0;
                $total_E = 0;
                $total_remain = 0;
            @endphp
            @foreach ($subjects as $subject)
            @continue($subject->id == '03')
            <tr>
                <th>{{ $subject->name }}</th>
                @php
                    $students = App\Models\Map::where([
                                            'year'=>2022,
                                            request()->segment(2)=>1,
                                            'subject_id'=>$subject->id,
                                        ])->whereNotNull('student_id')
                                        ->get();
                @endphp
                <td class="text-end">{{ $students->count() }}</td>
                @php
                    $lecture_assessment = 0;
                    $teacher_assessment = 0;
                    $lecture_form_plp1_count = 2;
                    $lecture_form_plp2_count = 3;
                    $teacher_form_plp2_count = 13;

                    $lecture_percent = 0.4;
                    $teacher_percent = 0.6;
                    $grade_A = 0;
                    $grade_B = 0;
                    $grade_C = 0;
                    $grade_D = 0;
                    $grade_E = 0;
                    $remain = $students->count();
                    foreach ($students as $map) {

                        $lecture = App\Models\Assessment::where([
                                'map_id'=>$map->id,
                                'plp_order'=>$plp_order,
                                'assessor' => 'dosen'
                            ]);
                        $teacher = App\Models\Assessment::where([
                                'map_id'=>$map->id,
                                'plp_order'=>$plp_order,
                                'assessor' => 'guru'
                                ]);
                        if ($lecture->exists() or $teacher->exists()) {
                            $remain -= 1;
                        } else {
                            continue;
                        }
                        $lecture_assessment += $lecture->sum('grade');
                        $teacher_assessment += $teacher->sum('grade');
                        if ($plp_order == 1) {
                            $lecture_assessment /= $lecture_form_plp1_count;
                        }
                        if ($plp_order == 2) {
                            $lecture_assessment /= $lecture_form_plp2_count;
                            $teacher_assessment /= $teacher_form_plp2_count;
                            $teacher_assessment *= $teacher_percent;
                            $lecture_assessment += $teacher_assessment;
                        }
                        $grade = $lecture_assessment;
                        if ($grade >= 86) {
                            $grade_A += 1;
                        } elseif ($grade >= 76) {
                            $grade_B += 1;
                        } elseif ($grade >= 66) {
                            $grade_C += 1;
                        } elseif ($grade >= 56) {
                            $grade_D += 1;
                        } else {
                            $grade_E += 1;
                        }
                    }
                @endphp
                <td class="text-end">{{ $grade_A }}</td>
                <td class="text-end">{{ $grade_B }}</td>
                <td class="text-end">{{ $grade_C }}</td>
                <td class="text-end">{{ $grade_D }}</td>
                <td class="text-end">{{ $grade_E }}</td>
                <td class="text-end">{{ $remain }}</td>
            </tr>
            @php
                $total_student += $students->count();
                $total_A += $grade_A;
                $total_B += $grade_B;
                $total_C += $grade_C;
                $total_D += $grade_D;
                $total_E += $grade_E;
                $total_remain += $remain;
            @endphp
            @endforeach
            <tr class="text-primary">
                <th>Total:</th>
                <th class="text-end">{{ $total_student }}</th>
                <th class="text-end">{{ $total_A }}</th>
                <th class="text-end">{{ $total_B }}</th>
                <th class="text-end">{{ $total_C }}</th>
                <th class="text-end">{{ $total_D }}</th>
                <th class="text-end">{{ $total_E }}</th>
                <th class="text-end">{{ $total_remain }}</th>
            </tr>
        </tbody>
    </table>
</div>
