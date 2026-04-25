@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Catatan Harian PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('dashboard') }}" class="btn btn-primary float-end">Kembali ke dashboard</a>
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ Logbook</button>
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="studentdiary-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Waktu</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($diaries as $diary)
                                        <tr>
                                            <td class="text-center align-top">
                                                @canany(['aktivitas/studentdiaries/plp-update'])
                                                @if ($diary->verified == 0)
                                                <button
                                                    type="button"
                                                    data-id={{ $diary->id }}
                                                    data-jenis="edit"
                                                    class="btn btn-primary btn-sm mb-2 action">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                                <br>
                                                <button
                                                    type="button"
                                                    data-id={{ $diary->id }}
                                                    data-jenis="delete"
                                                    class="btn btn-danger btn-sm action">
                                                    <i class="ti-trash"></i>
                                                </button>
                                                @endif
                                                @endcanany
                                            </td>

                                            <td class="align-top">
                                                hari ke-{{ $diary->day_order ?? '' }} <br>
                                                <span class="badge bg-primary">{{ $diary->log_date ? $diary->log_date->format('d-m-Y') : '' }}</span>
                                                <br>
                                                <span class="badge bg-{{ $diary->verified == 1 ? 'success' : 'danger' }}">{{ $diary->verified == 1 ? 'SUDAH' : 'BELUM' }} diverifikasi</span>
                                            </td>
                                            <td>{{ $diary->note ?? '' }}</td>
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
        crudDataTables('studentdiary-table')
    </script>
@endpush
