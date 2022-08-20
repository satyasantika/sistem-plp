@extends('konfigurasi.datatable')

@push('jscode')
    <script>
        const modal = new bootstrap.Modal($('#modalAction'))

        $('.btn-add').on('click', function() {
            $.ajax({
                method: 'GET',
                url: `{{ url('konfigurasi/schools/create') }}`,
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
                        window.LaravelDataTables['school-table'].ajax.reload()
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

        $('#school-table').on('click','.action',function() {
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
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            method: 'DELETE',
                            url: `{{ url('konfigurasi/schools/') }}/${id}`,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success : function(response) {
                                window.LaravelDataTables['school-table'].ajax.reload()
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
                url: `{{ url('konfigurasi/schools/') }}/${id}/edit`,
                success : function(response) {
                    $('#modalAction').find('.modal-dialog').html(response)
                    modal.show()
                    store()
                }
            })


        })
    </script>
@endpush
