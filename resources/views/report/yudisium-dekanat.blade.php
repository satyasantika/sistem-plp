<div class="content-wrapper">
    <div class="row">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    <h5>Rekap Hasil Penilaian PLP {{ $plp_order }}</h5>
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
                                    <th class="text-end">Peserta</th>
                                    <th class="text-end px-3">A</th>
                                    <th class="text-end px-3">B</th>
                                    <th class="text-end px-3">C</th>
                                    <th class="text-end px-3">D</th>
                                    <th class="text-end px-3">E</th>
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
                                    $penmas = 99;
                                @endphp
                                <tr>
                                    <td>penmas</td>
                                    <td class="text-end">{{ $penmas }}</td>
                                    <td class="text-end"><span class="badge bg-primary">{{ $penmas }}</span></td>
                                    <td class="text-end"><span class="badge bg-dark">{{ 0 }}</span></td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end">{{ 0 }}</td>
                                </tr>
                                @foreach ($subjects as $subject)
                                @continue($subject->id == '03')
                                <tr>
                                    <th>{{ $subject->name }}</th>
                                    @php
                                        $students = App\Models\Map::where([
                                                                'year'=>2023,
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
                                        // $arrayku = []
                                        $remain = $students->count();
                                        foreach ($students as $map) {
                                        // $lecture_assessment = 0;
                                        // $teacher_assessment = 0;

                                            $lecture = App\Models\Assessment::where([
                                                'map_id'=>$map->id,
                                                'plp_order'=>$plp_order,
                                                'assessor' => 'dosen'
                                            ]);
                                            // dd($lecture->get()->maps->id);
                                            $teacher = App\Models\Assessment::where([
                                                    'map_id'=>$map->id,
                                                    'plp_order'=>$plp_order,
                                                    'assessor' => 'guru'
                                                ]);
                                            if ($lecture->exists() or $teacher->exists()) {
                                            // if ($lecture->exists()) {
                                                $remain -= 1;

                                                $lecture_assessment += $lecture->sum('grade');
                                                $teacher_assessment += $teacher->sum('grade');

                                                if ($plp_order == 1) {
                                                    $lecture_assessment /= $lecture_form_plp1_count;
                                                    }

                                                if ($plp_order == 2) {
                                                    $lecture_assessment /= $lecture_form_plp2_count;
                                                    $teacher_assessment /= $teacher_form_plp2_count;
                                                    $lecture_assessment *= $lecture_percent;
                                                    $teacher_assessment *= $teacher_percent;
                                                    $lecture_assessment += $teacher_assessment;
                                                }

                                                $grade = round($lecture_assessment,2);

                                                // if ($lecture_assessment < 56) {
                                                //     $grade_E += 1;
                                                // } elseif ($lecture_assessment < 66) {
                                                //     $grade_D += 1;
                                                // } elseif ($lecture_assessment < 76) {
                                                //     $grade_C += 1;
                                                // } elseif ($lecture_assessment < 86) {
                                                //     $grade_B += 1;
                                                // } else {
                                                //     $grade_A += 1;
                                                // }

                                                if ($grade >= 86) {
                                                    $grade_A += 1;
                                                } else if ($grade >= 76) {
                                                    $grade_B += 1;
                                                } else if ($grade >= 66) {
                                                    $grade_C += 1;
                                                } else if ($grade >= 56) {
                                                    $grade_D += 1;
                                                } else {
                                                    $grade_E += 1;
                                                }
                                                $lecture_assessment = 0;
                                                $teacher_assessment = 0;


                                            } else {
                                                continue;
                                            }
                                        }
                                    @endphp
                                    <td class="text-end"><span class="badge bg-primary">{{ $grade_A }}</span></td>
                                    <td class="text-end"><span class="badge bg-dark">{{ $grade_B }}</span></td>
                                    <td class="text-end text-danger">{{ $grade_C }}</td>
                                    <td class="text-end text-danger">{{ $grade_D }}</td>
                                    <td class="text-end text-danger">{{ $grade_E }}</td>
                                    <td class="text-end">{{ $remain }}</td>
                                    {{-- @dd($remain) --}}
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
                                    <th class="text-end">{{ $total_student +99 }}</th>
                                    <th class="text-end">{{ $total_A +99 }}</th>
                                    <th class="text-end">{{ $total_B }}</th>
                                    <th class="text-end text-danger">{{ $total_C }}</th>
                                    <th class="text-end text-danger">{{ $total_D }}</th>
                                    <th class="text-end text-danger">{{ $total_E }}</th>
                                    <th class="text-end">{{ $total_remain }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

