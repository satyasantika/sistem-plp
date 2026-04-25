@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    <style>
        .departementmap-table-wrap {
            border-radius: 14px;
            border: 1px solid #d9e3f1;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
            box-shadow: 0 10px 24px rgba(20, 44, 79, 0.08);
            overflow: hidden;
        }

        #departementmap-table {
            border-collapse: separate !important;
            border-spacing: 0;
            width: 100% !important;
            table-layout: fixed;
        }

        #departementmap-table thead th {
            background: linear-gradient(135deg, #edf4ff 0%, #e8f6f4 100%);
            color: #24364f;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.35px;
            text-transform: uppercase;
            border-bottom: 1px solid #d4e0f0 !important;
            padding: 12px 14px;
        }

        #departementmap-table.dataTable thead th.sorting,
        #departementmap-table.dataTable thead th.sorting_asc,
        #departementmap-table.dataTable thead th.sorting_desc {
            background-image: none !important;
            position: relative;
            padding-right: 26px;
        }

        #departementmap-table.dataTable thead th.sorting:after,
        #departementmap-table.dataTable thead th.sorting_asc:after,
        #departementmap-table.dataTable thead th.sorting_desc:after {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            line-height: 1;
            opacity: 0.55;
        }

        #departementmap-table.dataTable thead th.sorting:after {
            content: '↕';
        }

        #departementmap-table.dataTable thead th.sorting_asc:after {
            content: '▲';
            opacity: 0.8;
        }

        #departementmap-table.dataTable thead th.sorting_desc:after {
            content: '▼';
            opacity: 0.8;
        }

        #departementmap-table thead th:first-child,
        #departementmap-table tbody td:first-child {
            width: 64px;
            text-align: center;
        }

        #departementmap-table thead th:nth-child(2) {
            width: 42%;
        }

        #departementmap-table thead th:nth-child(3),
        #departementmap-table thead th:nth-child(4) {
            width: 29%;
        }

        #departementmap-table tbody td {
            border-bottom: 1px solid #e7edf7;
            padding: 13px 14px;
            vertical-align: middle;
            color: #2a3c57;
            background: transparent;
            transition: background-color .2s ease;
        }

        #departementmap-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .map-school-name {
            font-weight: 600;
            color: #23344f;
            line-height: 1.45;
            word-break: break-word;
        }

        .map-chip-cell {
            display: inline-flex;
            align-items: center;
            min-height: 30px;
        }

        #departementmap-table tbody tr:hover td {
            background: #f4f9ff;
        }

        .departementmap-table-wrap .dataTables_length,
        .departementmap-table-wrap .dataTables_filter,
        .departementmap-table-wrap .dataTables_info,
        .departementmap-table-wrap .dataTables_paginate {
            padding: 10px 14px;
            color: #4a5f7e !important;
        }

        .departementmap-table-wrap .dataTables_filter input,
        .departementmap-table-wrap .dataTables_length select {
            border: 1px solid #c8d7eb;
            border-radius: 10px;
            background: #ffffff;
            color: #29405f;
            padding: 6px 10px;
        }

        .departementmap-table-wrap .dataTables_length,
        .departementmap-table-wrap .dataTables_filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .departementmap-table-wrap .dataTables_filter input {
            min-width: 220px;
        }

        .departementmap-table-wrap .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1px solid transparent !important;
        }

        .departementmap-table-wrap .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #1bb5a8, #57d0c5) !important;
            color: #082236 !important;
            border-color: #16a094 !important;
        }

        .btn-map-action {
            border-radius: 10px;
            border-width: 1px;
            padding: 5px 9px;
            min-width: 36px;
        }

        .chip-label {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .chip-lecture {
            background: #eef2ff;
            color: #3643a8;
            border-color: #d7ddff;
        }

        .chip-student {
            background: #eaf9f7;
            color: #0d7e73;
            border-color: #c8ece8;
        }

        body.dark .departementmap-table-wrap {
            border-color: rgba(173, 193, 223, 0.2);
            background: linear-gradient(180deg, #1a2639 0%, #162233 100%);
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.32);
        }

        body.dark #departementmap-table thead th {
            background: linear-gradient(135deg, #253753 0%, #1f3f4d 100%);
            color: #e9f2ff;
            border-bottom-color: rgba(173, 193, 223, 0.2) !important;
        }

        body.dark #departementmap-table.dataTable thead th.sorting:after,
        body.dark #departementmap-table.dataTable thead th.sorting_asc:after,
        body.dark #departementmap-table.dataTable thead th.sorting_desc:after {
            color: #d6e6ff;
            opacity: 0.7;
        }

        body.dark #departementmap-table tbody td {
            color: #d4e2f8;
            border-bottom-color: rgba(173, 193, 223, 0.14);
        }

        body.dark .map-school-name {
            color: #dfebff;
        }

        body.dark #departementmap-table tbody tr:hover td {
            background: rgba(111, 157, 214, 0.12);
        }

        body.dark .departementmap-table-wrap .dataTables_length,
        body.dark .departementmap-table-wrap .dataTables_filter,
        body.dark .departementmap-table-wrap .dataTables_info,
        body.dark .departementmap-table-wrap .dataTables_paginate {
            color: #b8c9e4 !important;
        }

        body.dark .departementmap-table-wrap .dataTables_filter input,
        body.dark .departementmap-table-wrap .dataTables_length select {
            border-color: rgba(173, 193, 223, 0.3);
            background: #111d2d;
            color: #d7e4fa;
        }

        body.dark .departementmap-table-wrap .dataTables_paginate .paginate_button.current {
            color: #062638 !important;
        }

        body.dark .chip-lecture {
            background: rgba(125, 137, 255, 0.2);
            color: #cad1ff;
            border-color: rgba(149, 160, 255, 0.35);
        }

        body.dark .chip-student {
            background: rgba(56, 187, 167, 0.2);
            color: #bdf4ec;
            border-color: rgba(94, 215, 196, 0.35);
        }

        @media (max-width: 992px) {
            .departementmap-table-wrap .dataTables_filter input {
                min-width: 160px;
            }

            #departementmap-table thead th,
            #departementmap-table tbody td {
                padding: 11px 10px;
            }
        }
    </style>
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
                        <div class="table-responsive departementmap-table-wrap">
                                <table class="display" id="departementmap-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Sekolah</th>
                                            <th>DPL</th>
                                            <th>Mahasiswa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($my_subject_maps as $map)
                                        <tr>
                                            <td class=" text-center">
                                                @can('mapping/departementmaps-update')
                                                <button type="button" data-id="{{ $map->id }}" data-jenis="edit" class="btn btn-outline-primary btn-sm action btn-map-action"><i class="ti-location-arrow"></i></button>
                                                @endcan
                                            </td>
                                            <td>
                                                <div class="map-school-name">{{ $map->schools->name ?? '-' }}</div>
                                            </td>
                                            <td>
                                                <div class="map-chip-cell">
                                                    <span class="chip-label chip-lecture">{{ $map->lectures->name ?? '-' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="map-chip-cell">
                                                    <span class="chip-label chip-student">{{ $map->students->name ?? '-' }}</span>
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
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js" defer></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js" defer></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js" defer></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js" defer></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js" defer></script>
        <script>
        if (typeof window.crudDataTables !== 'function') {
            window.crudDataTables = function(table) {
                const modalElement = document.getElementById('modalAction');
                const getModal = () => {
                    if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
                        return null;
                    }

                    return window.bootstrap.Modal.getOrCreateInstance(modalElement);
                };

                $('.btn-add').off('click.legacyCrud').on('click.legacyCrud', function() {
                    $.ajax({
                        method: 'GET',
                        url: document.URL + '/create',
                        success: function(response) {
                            $('#modalAction').find('.modal-dialog').html(response);
                            const modal = getModal();
                            modal?.show();
                            store();
                        },
                    });
                });

                function store() {
                    $(document)
                        .off('submit.legacyCrudStore', '#formAction')
                        .on('submit.legacyCrudStore', '#formAction', function(e) {
                            e.preventDefault();
                            const _form = this;
                            const formData = new FormData(_form);

                            const url = this.getAttribute('action');

                            $.ajax({
                                method: 'POST',
                                url,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    $(`#${table}`).load(document.URL + ` #${table}`);
                                    const modal = getModal();
                                    modal?.hide();
                                    iziToast.success({
                                        title: 'Saved!',
                                        message: response.message,
                                        position: 'topRight',
                                    });
                                },
                                error: function(response) {
                                    let errors = response.responseJSON?.errors;

                                    if (errors) {
                                        for (const [key, value] of Object.entries(errors)) {
                                            $(`[name='${key}']`)
                                                .parent()
                                                .append(`<span class='text-danger text-small'>${value}</span>`);
                                        }
                                    }
                                },
                            });
                        });
                }

                $(`#${table}`)
                    .off('click.legacyCrudAction', '.action')
                    .on('click.legacyCrudAction', '.action', function() {
                        let data = $(this).data();
                        let id = data.id;
                        let jenis = data.jenis;

                        if (jenis == 'delete') {
                            Swal.fire({
                                title: 'Hapus permanen?',
                                text: 'Data sepenuhnya akan terhapus dari sistem!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        method: 'DELETE',
                                        url: document.URL + `/${id}`,
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                        },
                                        success: function(response) {
                                            $(`#${table}`).load(document.URL + ` #${table}`);
                                            iziToast.warning({
                                                title: 'Deleted!',
                                                message: response.message,
                                                position: 'topRight',
                                            });
                                        },
                                    });
                                }
                            });
                            return;
                        }

                        $.ajax({
                            method: 'GET',
                            url: document.URL + `/${id}/edit`,
                            success: function(response) {
                                $('#modalAction').find('.modal-dialog').html(response);
                                const modal = getModal();
                                modal?.show();
                                store();
                            },
                        });
                    });
            };
        }
    </script>

    <script>
        window.addEventListener('DOMContentLoaded', function () {
            if ($.fn.DataTable) {
                $('#departementmap-table').DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    order: [[1, 'asc']],
                    columnDefs: [
                        {
                            targets: 0,
                            orderable: false,
                            searchable: false,
                            width: '56px'
                        }
                    ],
                    language: {
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        infoFiltered: '(difilter dari _MAX_ data)',
                        zeroRecords: 'Data tidak ditemukan',
                        paginate: {
                            first: 'Awal',
                            last: 'Akhir',
                            next: '›',
                            previous: '‹'
                        }
                    }
                });
            }

            crudDataTables('departementmap-table');
        });
    </script>

@endpush
