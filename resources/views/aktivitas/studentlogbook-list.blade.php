@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Catatan Harian Kegiatan PLP
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
                                        <th></th>
                                        <th>Nama</th>
                                        <th>Diverifikasi?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $maps = App\Models\Map::where('lecture_id',auth()->user()->id)
                                                                ->get()
                                    @endphp
                                    @forelse ($maps as $map)
                                    <tr>
                                        <td class="text-end">
                                            <a href="{{ route('diaryverifications.show',['plp_order'=>$plp_order, 'map_id'=>$map->id]) }}" class="btn btn-sm btn-success">Logbook</a>
                                        </td>
                                        <td>
                                            {{ $map->students->name ?? '' }}
                                        </td>
                                        <td>
                                            sudah <span class="badge bg-success">{{ App\Models\Diary::where('map_id',$map->id)
                                                                            ->where('plp_order',$plp_order)
                                                                            ->where('verified',1)
                                                                            ->count() }}</span>
                                            belum <span class="badge bg-danger">{{ App\Models\Diary::where('map_id',$map->id)
                                                                            ->where('plp_order',$plp_order)
                                                                            ->where('verified',0)
                                                                            ->count() }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <div class="alert alert-info">Belum ada data</div>
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
