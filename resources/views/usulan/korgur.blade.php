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
                        <a href="{{ route('coordinators.create') }}" class="btn btn-primary btn-sm mb-3 btn-add">+ {{ request()->segment(2) }}</a>
                        {{-- <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ {{ request()->segment(2) }}</button> --}}
                        <table class="table table-hover" id="coordinator-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Sekolah</th>
                                    <th scope="col">Nama Koordinator GP</th>
                                    <th scope="col">Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coordinators as $coordinator)
                                <tr>
                                    <td scope="row">
                                        <a href="{{ route('coordinators.edit',$coordinator) }}" class="btn btn-primary btn-sm"><i class="ti-pencil"></i></a>
                                        <a href="{{ route('coordinators.destroy',$coordinator) }}" onclick="event.preventDefault();document.getElementById('delete-form').submit();" class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
                                        @can('usulan/coordinators-delete')
                                        <form id="delete-form" action="{{ route('coordinators.destroy',$coordinator->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endcan
                                        {{-- <a href="javascript:alert('Hello World!');">Execute JavaScript</a> --}}
                                        {{-- <button type="button" data-id="{{ $coordinator->id }}" data-jenis="edit" class="btn btn-primary btn-sm my-1 action"><i class="ti-pencil"></i></button> --}}
                                        {{-- <button type="button" data-id="{{ $coordinator->id }}" data-jenis="delete" class="btn btn-danger btn-sm my-1 action"><i class="ti-trash"></i></button> --}}
                                    </td>
                                    <td>{{ $coordinator->schools->name }}</td>
                                    <td>{{ $coordinator->candidate_name }}</td>
                                    <td>{{ $coordinator->registered }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        const modal = new bootstrap.Modal($('#modalAction'))

        $('.btn-add').on('click', function() {
            $.ajax({
                method: 'GET',
                url: `{{ url('usulan/coordinators/create') }}`,
                success : function(response) {
                    $('#modalAction').find('.modal-dialog').html(response)
                    modal.show()
                    store()
                }
            })
        })

        function store() {
            $('#formAction').on('submit',function(e) {
                e.preventDefault()
                const _form = this
                const formData = new FormData(_form)

                const url = this.getAttribute('action')

                $.ajax({
                    method: 'POST',
                    url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success : function(response) {
                        window.LaravelDataTables['coordinator-table'].ajax.reload()
                        modal.hide()
                        iziToast.success({
                            title: 'Saved!',
                            message: response.message,
                            position: 'topRight'
                        });
                    },
                    error: function(response) {
                        let errors = response.responseJSON?.errors

                        if (errors) {
                            for(const [key,value] of Object.entries(errors)){
                                $(`[name='${key}']`).parent().append(`<span class='text-danger text-small'>${value}</span>`)
                            }
                        }
                    }
                })
            })
        }

        $('#coordinator-table').on('click','.action',function() {
            let data = $(this).data()
            let id = data.id
            let jenis = data.jenis

            if (jenis == 'delete') {
                Swal.fire({
                    title: 'Hapus permanen?',
                    text: "Data sepenuhnya akan terhapus dari sistem!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'DELETE',
                            url: `{{ url('usulan/coordinators/') }}/${id}`,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success : function(response) {
                                window.LaravelDataTables['coordinator-table'].ajax.reload()
                                iziToast.warning({
                                    title: 'Deleted!',
                                    message: response.message,
                                    position: 'topRight'
                                });
                            }
                        })
                    }
                })
                return
            }

            $.ajax({
                method: 'GET',
                url: `{{ url('usulan/coordinators/') }}/${id}/edit`,
                success : function(response) {
                    $('#modalAction').find('.modal-dialog').html(response)
                    modal.show()
                    store()
                }
            })
        })
    </script>
@endpush
