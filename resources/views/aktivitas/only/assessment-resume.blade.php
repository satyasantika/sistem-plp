@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Penilaian Kegiatan PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Rekap Penilaian Mahasiswa
                        <a href="{{ route('dashboard') }}" class="btn btn-primary float-end">Kembali ke dashboard</a>
                    </div>
                    <div class="card-body">
                        <div>
                            Rekap penilaian ini menyajikan informasi penilaian dari setiap mahasiswa dengan catatan sebagai berikut:
                            <ol>
                                <li>nilai 0 (tulisan merah) menandakan input nilai belum dilakukan</li>
                                <li>nilai sudah masuk ke sistem jika keterangan nilai (walaupun nilai 0) berada pada tombol hijau</li>
                                @role('dosen')
                                    <li>klik pada tombol setiap skor untuk mulai menilai (N2/N6/N7)</li>
                                    <li>nilai DPL = (N2 + N6 + N7) / 3</li>
                                @endrole
                                @role('guru')
                                    <li>klik pada tombol setiap skor untuk mulai menilai (N1/N3/N4/N5/N6/N7)</li>
                                    <li>nilai GP = (N1 + N3 + N4 + N5 + N6 + N7) / 6</li>
                                @endrole
                                <li>nilai gabungan DPL & GP = 60% nilai GP + 40% nilai DPL</li>
                                <li>
                                    ketarangan huruf sebagai berikut:<br>
                                    A (minimal 85) A- (minimal 77) B+ (minimal 69) B (minimal 61) B- (minimal 53) <br>C+ (minimal 45) C (minimal 37) C- (minimal 29) D (minimal 21) E (di bawah 21)
                                </li>
                            </ol>

                        </div>
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="assessment-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>Mahasiswa</th>
                                            {{-- PERULANGAN JENIS FORM --}}
                                            @foreach ($forms as $form) <th>{{ substr($form,-2) }}</th> @endforeach
                                            @role('dosen')
                                                <th class="text-center">Nilai DPL</th>
                                            @endrole
                                            @role('guru')
                                                <th class="text-center">Nilai GP</th>
                                            @endrole
                                            <th class="text-center">Nilai Gab <br>(DPL & GP)</th>
                                            <th class="text-center">Huruf</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($maps as $map)
                                        <tr>
                                            <td>
                                                {{ $map->students->name ?? '' }}
                                            </td>
                                            @php
                                                $count_form = 0;
                                                $total_grade = 0;
                                            @endphp
                                            @foreach ($forms as $form)
                                            {{-- PERULANGAN FORM YANG DINILAI --}}
                                            @php
                                                $assessor = 'guru';
                                                if(auth()->user()->hasrole('dosen')){
                                                    $assessor = 'dosen';
                                                }
                                                $assessments = App\Models\Assessment::where('form_id',$form)
                                                                                    ->where('map_id',$map->id)
                                                                                    ->where('assessor',$assessor)
                                                                                    ;
                                            @endphp
                                            <td>
                                                @if ($assessments->exists())
                                                @php
                                                    $grade = $assessments->sum('grade');
                                                    $form_times = App\Models\Form::find($form)->times;
                                                    $grade = round($grade/$form_times,0);
                                                @endphp
                                                <a
                                                    href="{{ route('schoolassessments.only.show',['form_id' => $form]) }}"
                                                    class="btn btn-success btn-sm mb-2">
                                                    {{ $grade }}
                                                </a>
                                                @php
                                                    // hitung banyaknya form
                                                    $count_form += $assessments->count();
                                                    //hitung nilai total form
                                                    $total_grade += $grade;
                                                @endphp
                                                @else
                                                <a
                                                    href="{{ route('schoolassessments.only.show',['form_id' => $form]) }}"
                                                    class="btn btn-outline-danger btn-sm mb-2">
                                                    {{ 0 }}
                                                </a>
                                                @endif
                                            </td>
                                            @endforeach
                                            @php
                                                if (auth()->user()->hasrole('dosen'))
                                                {
                                                    $forms = ['2024N2','2024N6','2024N7'];
                                                } else {
                                                    $forms = ['2024N1','2024N3','2024N4','2024N5','2024N6','2024N7'];
                                                }
                                                // penilaian dari guru
                                                $assessment_by_teacher = App\Models\Assessment::where([
                                                    'assessor'=>'guru',
                                                    'map_id'=>$map->id,
                                                    ])
                                                    ->whereIn('form_id',$forms)
                                                    ->sum('grade');
                                                    $teacher_form_times = App\Models\Form::whereIn('id',$forms)->sum('times');
                                                    $assessment = $assessment_by_teacher/$teacher_form_times;

                                                    $assessor = 'guru';
                                                    if(auth()->user()->hasrole('dosen')){
                                                        $assessor = 'dosen';
                                                        // penilaian dari dosen
                                                        $assessment_by_lecture = App\Models\Assessment::where([
                                                                                            'assessor'=>'dosen',
                                                                                            'map_id'=>$map->id,
                                                                                        ])
                                                                                        ->whereIn('form_id',$forms)
                                                                                        ->sum('grade');
                                                        $lecture_form_times = App\Models\Form::whereIn('id',$forms)->sum('times');
                                                        $assessment = round($assessment_by_lecture/$lecture_form_times,0);
                                                }
                                                $assessments = App\Models\Assessment::where('assessor',$assessor)
                                                                                    ->where('map_id',$map->id)
                                                                                    ;
                                            @endphp
                                                @if ($assessments->exists())
                                                    <td class="text-center">{{ round($assessment,2) }}</td>
                                                    <td class="text-center">{{ round($map->grade,2) }}</td>
                                                    <td class="text-center">{{ $map->letter }}</td>
                                                @else
                                                <td></td>
                                                <td></td>
                                                @endif
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
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script src="{{ asset('') }}assets/js/crud2-datatables.js"></script>
    <script>
        crudDataTables('assessment-table')
    </script>
@endpush
