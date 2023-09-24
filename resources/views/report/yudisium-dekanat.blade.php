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
                                $letters = ['A','A-','B+','B','B-','C+','C','C-','D','E']
                            @endphp
                            <thead>
                                <tr>
                                    <th>Jurusan</th>
                                    <th class="text-end">Peserta</th>
                                    @foreach ($letters as $letter)
                                    <th class="text-end">{{ $letter }}</th>
                                    @endforeach
                                    <th class="text-end">Belum Dinilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $penmas = 0;
                                    // $penmas = 112;
                                @endphp
                                {{-- <tr>
                                    <td>penmas</td>
                                    <td class="text-end">{{ $penmas }}</td>
                                    <td class="text-end text-primary">{{ $penmas }}</td>
                                    <td class="text-end text-primary">{{ 0 }}</td>
                                    <td class="text-end text-primary">{{ 0 }}</td>
                                    <td class="text-end text-primary">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end text-danger">{{ 0 }}</td>
                                    <td class="text-end">{{ 0 }}</td>
                                </tr> --}}

                                @foreach ($subjects as $subject)
                                @continue($subject->id == '03')
                                <tr>
                                    <th>{{ $subject->name }}</th>
                                    @php
                                        $students = App\Models\Map::where([
                                                                'year'=>2023,
                                                                request()->segment(2)=>1,
                                                                'subject_id'=>$subject->id,
                                                            ])->whereNotNull('student_id');
                                    @endphp
                                    <td class="text-end">{{ $students->count() }}</td>
                                    @foreach ($letters as $letter)
                                        @if (in_array($letter,['A','A-','B+','B']))
                                            <td class="text-end text-primary">
                                        @else
                                            <td class="text-end text-danger">
                                        @endif
                                            {{ App\Models\Map::where([
                                                                'year'=>2023,
                                                                request()->segment(2)=>1,
                                                                'subject_id'=>$subject->id,
                                                                'letter1'=>$letter,
                                                            ])->whereNotNull('student_id')->count() }}
                                        </td>
                                    @endforeach
                                    <td class="text-end">
                                        {{ App\Models\Map::where([
                                                                'year'=>2023,
                                                                request()->segment(2)=>1,
                                                                'subject_id'=>$subject->id,
                                                                'grade1'=>0,
                                                            ])->whereNotNull('student_id')->count() }}
                                    </td>
                                </tr>
                                @endforeach

                                <tr class="text-primary bg-light">
                                    @php
                                        $total_student = App\Models\Map::where([
                                                                'year'=>2023,
                                                                request()->segment(2)=>1,
                                                            ])->whereNotNull('student_id')
                                                            ;
                                    @endphp

                                    <th>Total:</th>
                                    <th class="text-end">{{ $total_student->count() + $penmas }}</th>
                                    @foreach ($letters as $letter)
                                    @if (in_array($letter,['A','A-','B+','B']))
                                    <th class="text-end text-primary">
                                    @else
                                    <th class="text-end text-danger">
                                    @endif
                                        {{ $total_student = App\Models\Map::where([
                                                                'year'=>2023,
                                                                request()->segment(2)=>1,
                                                            ])->whereNotNull('student_id')->where('letter1',$letter)->count() }}
                                    </th>
                                    @endforeach
                                    <th class="text-end">{{ $total_student = App\Models\Map::where([
                                                                'year'=>2023,
                                                                request()->segment(2)=>1,
                                                                'grade1'=>0,
                                                            ])->whereNotNull('student_id')->count() }}</th>
{{--
                                    <th class="text-end">{{ $total_A +99 }}</th>
                                    <th class="text-end">{{ $total_B }}</th>
                                    <th class="text-end text-danger">{{ $total_C }}</th>
                                    <th class="text-end text-danger">{{ $total_D }}</th>
                                    <th class="text-end text-danger">{{ $total_E }}</th>
                                    <th class="text-end">{{ $total_remain }}</th> --}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

