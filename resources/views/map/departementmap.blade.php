@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        {{ ucFirst(request()->segment(1)) }} {{ ucFirst(request()->segment(2)) }}
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ {{ request()->segment(2) }}</button> --}}
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display nowrap dataTable no-footer" id="departementmap-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Sekolah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($my_subject_maps as $map)
                                        <tr>
                                            <td class=" text-center">
                                                @can('mapping/departementmaps-update')
                                                <button type="button" data-id="{{ $map->id }}" data-jenis="edit" class="btn btn-outline-primary btn-sm action"><i class="ti-location-arrow"></i></button>
                                                @endcan
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-4">{{ $map->schools->name ?? '' }}</div>
                                                    <div class="col-md-4">DPL<span class="badge bg-light text-dark">{{ $map->lectures->name ?? '' }}</span></div>
                                                    <div class="col-md-4">mhs<span class="badge bg-light text-primary">{{ $map->students->name ?? '' }}</span></div>
                                                </div>
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
    <script src="{{ asset('') }}assets/js/crud2-datatables.js"></script>
    <script>
        crudDataTables('departementmap-table')
    </script>

@endpush
