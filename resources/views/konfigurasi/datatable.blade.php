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
                        @stack('import')
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-primary btn-sm btn-add">+ {{ request()->segment(2) }}</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm btn-reload-table" data-table-id="{{ $dataTable->getTableAttribute('id') }}">
                                Reload Table
                            </button>
                        </div>
                        <div class="table-responsive">
                        {{ $dataTable->table(['class' => 'display nowrap']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

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
        $(function() {
            $('.btn-reload-table').on('click', function() {
                const tableId = $(this).data('table-id')

                if (window.LaravelDataTables?.[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload(null, false)
                    return
                }

                if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                    $(`#${tableId}`).DataTable().ajax.reload(null, false)
                }
            })
        })
    </script>
    @stack('jscode')

@endpush
