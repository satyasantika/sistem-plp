@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Role
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{ $dataTable->table() }}
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
    {{ $dataTable->scripts() }}
    <script>
        const modal = new bootstrap.Modal($('#modalAction'))
        $('#role-table').on('click','.action',function() {
            let data = $(this).data()
            let id = data.id
            let jenis = data.jenis

            $.ajax({
                method: 'GET',
                url: `{{ url('konfigurasi/roles/') }}/${id}/edit`,
                success : function(response) {
                    $('#modalAction').find('.modal-dialog').html(response)
                    modal.show()
                    store()
                }
            })

            function store() {
                $('#formAction').on('submit',function(e) {
                    e.preventDefault()
                    const _form = this
                    const formData = new FormData(_form)

                    $.ajax({
                        method: 'POST',
                        url: `{{ url('konfigurasi/roles/') }}/${id}`,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success : function(response) {
                            window.LaravelDataTables['role-table'].ajax.reload()
                            modal.hide()
                        },
                        error: function(response) {
                            let errors = response.responseJSON?.errors

                            if (errors) {
                                for(const [key,value] of Object.entries(errors)){
                                    $(`[name='${key}']`).parent().append(`<span class='text-danger text-small'>${value}</span>`)
                                }
                            }
                            console.log(errors);
                        }
                    })
                })
            }
        })
    </script>
@endpush
