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
                        <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ {{ request()->segment(2) }}</button>
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <div class="dataTables_length" id="role-table_length">
                                    <label>Show <select name="role-table_length" aria-controls="role-table" class="">
                                        <option value="10">10</option><option value="25">25</option><option value="50">50</option>
                                        <option value="100">100</option></select> entries</label>
                                    </div>
                                    <div id="role-table_filter" class="dataTables_filter"><label>Search:
                                        <input type="search" class="" placeholder="" aria-controls="role-table">
                                    </label>
                                    </div>
                                    <div id="role-table_processing" class="dataTables_processing" style="display: none;">Processing...
                                    </div>
                                    <table class="display nowrap dataTable no-footer" id="role-table" role="grid" aria-describedby="role-table_info" style="width: 938px;">
                                        <thead>
                                            <tr role="row">
                                                <th title="" width="100" class="text-center sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 100px;">
                                                </th>
                                                <th title="Name" class="sorting sorting_asc" tabindex="0" aria-controls="role-table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 262px;">Name
                                                </th>
                                                <th title="Updated At" class="sorting" tabindex="0" aria-controls="role-table" rowspan="1" colspan="1" aria-label="Updated At: activate to sort column ascending" style="width: 468px;">Updated At
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="1" class="odd">
                                                <td class=" text-center">
                                                    <button type="button" data-id="1" data-jenis="edit" class="btn btn-primary btn-sm action"><i class="ti-pencil"></i></button>
                                                    <button type="button" data-id="1" data-jenis="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>
                                                </td>
                                                <td class="sorting_1">admin
                                                </td>
                                                <td>01/09/2022 19:39:43
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
    {{-- <script src="{{ asset('') }}vendor/select2/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    {{ $dataTable->scripts() }}
    <script src="{{ asset('') }}assets/js/crud-datatables.js"></script>
    <script>
        crudDataTables('forms','form-table')
    </script>

@endpush
