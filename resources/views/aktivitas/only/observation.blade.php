@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Observasi Kegiatan PLP
    </div>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary float-end">Kembali ke dashboard</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="form-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>Formulir</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($forms as $form)
                                        <tr>
                                            <td>
                                                @php
                                                    $myObservation = App\Models\Observation::where('form_id',$form->id)->where('map_id',$map_id);
                                                @endphp
                                                @if ($myObservation->exists())
                                                <button type="button" data-formid={{ $form->id }} data-id={{ $myObservation->first()->id }} data-jenis="edit" class="btn btn-success btn-sm mb-2 action">Lihat Hasil</button>
                                                @else
                                                <button type="button" data-formid={{ $form->id }} data-jenis="add" class="btn btn-primary btn-sm mb-3 action">Isi Form</button>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $form->name }}
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
    <script src="{{ asset('') }}assets/js/observation-datatables.js"></script>
    <script>
        crudDataTables('form-table')
    </script>
@endpush
