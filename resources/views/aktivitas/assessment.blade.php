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
                        Rekap Penilaian {{ substr($form_id,-2) }}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="studentlogbook-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>Mahasiswa</th>
                                            <th>Nilai {{ substr($form_id,-2) }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($maps as $map)
                                        @php
                                            $evaluation = App\Models\Assessment::where([
                                                            'plp_order' => $plp_order,
                                                            'map_id' => $map->id,
                                                            'form_id' => $form_id,
                                            ]);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $map->students->name ?? '' }}
                                            </td>
                                            <td>
                                                @if ($evaluations->exists())
                                                <button type="button" data-formid={{ $form_id }} data-id={{ $evaluation->first()->id }} data-jenis="edit" class="btn btn-success btn-sm mb-2 action">Lihat Hasil</button>
                                                @else
                                                <button type="button" data-formid={{ $form_id }} data-jenis="add" class="btn btn-primary btn-sm mb-3 action">Isi Form</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <div class="alert alert-info">Belum ada catatan</div>
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
    <script src="{{ asset('') }}assets/js/verifikasi-datatables.js"></script>
    <script>
        updateOnly('studentlogbook-table')
    </script>
@endpush
