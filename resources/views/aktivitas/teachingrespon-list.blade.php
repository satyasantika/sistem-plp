@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Komentar Latihan
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md">
                <div class="card">
                    <div class="card-header">
                        <h5>Data Mahasiswa Pamongan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table small-font table-striped table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Penampilan</th>
                                        <th>Komentar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $map_id = App\Models\Map::where('student_id',auth()->user()->id)->first()->map_id;
                                        $comments = App\Models\Assessment::where('map_id',$map_id)->whereIn('form_id',['2022N5','2022N7'])->get();
                                    @endphp
                                    @forelse ($comments as $comment)
                                    <tr>
                                        <td>
                                            @if ($comment->form_id == '2022N5')
                                                Latihan ke-{{ $comment->form_order }}
                                            @else
                                                Ujian
                                            @endif
                                        </td>
                                        <td>
                                            {{ $comment->note }}
                                        </td>
                                    </tr>
                                    @empty
                                    <div class="alert alert-info">Belum ada komentar</div>
                                    @endforelse
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

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script src="{{ asset('') }}assets/js/crud2-datatables.js"></script>
    <script>
        crudDataTables('studentdiary-table')
    </script>
@endpush
