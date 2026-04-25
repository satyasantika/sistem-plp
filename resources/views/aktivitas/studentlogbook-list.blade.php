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
                                        $maps = App\Models\Map::where('lecture_id',auth()->user()->id)->where('year',2023)
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
