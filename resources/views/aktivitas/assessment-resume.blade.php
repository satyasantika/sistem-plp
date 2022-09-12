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
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="assessment-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>Mahasiswa</th>
                                            {{-- PERULANGAN JENIS FORM --}}
                                            @foreach ($forms as $form) <th>{{ substr($form,-2) }}</th> @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($maps as $map)
                                        <tr>
                                            <td>
                                                {{ $map->students->name ?? '' }}
                                            </td>
                                            @foreach ($forms as $form)
                                            {{-- PERULANGAN FORM YANG DINILAI --}}
                                            @php
                                                $plp_order = substr(request()->segment(3),-1);
                                                $assessments = App\Models\Assessment::where('form_id',$form)
                                                                                    ->where('plp_order',$plp_order)
                                                                                    ->where('map_id',$map->id)
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
                                                    href="{{ route('schoolassessments.show',['plp_order' => $plp_order, 'form_id' => $form]) }}"
                                                    class="btn btn-success btn-sm mb-2">
                                                    {{ $grade }}
                                                </a>
                                                @else
                                                <a
                                                    href="{{ route('schoolassessments.show',['plp_order' => substr(request()->segment(3),-1), 'form_id' => $form]) }}"
                                                    class="btn btn-outline-danger btn-sm mb-2">
                                                    {{ 0 }}
                                                </a>
                                                @endif
                                            </td>
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
