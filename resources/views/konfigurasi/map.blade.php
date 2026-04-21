@extends('konfigurasi.datatable')

@push('import')
    <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
        <button type="button" class="btn btn-primary btn-sm btn-import" data-title="Import Maps">
            Import Maps
        </button>
    </div>
@endpush

@push('jscode')
    <script>
        crudDataTables('maps','map-table')

        const mapImportModal = new bootstrap.Modal($('#modalAction'))

        $('.btn-import').on('click', function() {
            $.ajax({
                method: 'GET',
                url: '/konfigurasi/maps/import/create',
                data: {
                    title: $(this).data('title'),
                },
                success: function(response) {
                    $('#modalAction').find('.modal-dialog').removeClass('modal-lg').addClass('modal-xl').html(response)
                    mapImportModal.show()
                    bindMapImportForm()
                },
                error: function(response) {
                    iziToast.error({
                        title: 'Import gagal',
                        message: response.responseJSON?.message || 'Modal import maps gagal dimuat.',
                        position: 'topRight'
                    })
                }
            })
        })

        function bindMapImportForm() {
            const form = $('#formAction')

            form.off('submit.mapImport').on('submit.mapImport', function(e) {
                e.preventDefault()
                form.find('.import-feedback').empty()
                form.find('.text-danger.text-small').remove()

                $.ajax({
                    method: 'POST',
                    url: this.getAttribute('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (window.LaravelDataTables?.['map-table']) {
                            window.LaravelDataTables['map-table'].ajax.reload(null, false)
                        }

                        mapImportModal.hide()
                        iziToast.success({
                            title: 'Saved!',
                            message: response.message,
                            position: 'topRight'
                        })
                    },
                    error: function(response) {
                        const feedback = form.find('.import-feedback')
                        const errors = response.responseJSON?.errors || {}
                        const message = response.responseJSON?.message
                        let toastMessages = []

                        if (message) {
                            feedback.append(`<div class='alert alert-danger small mb-3'>${message}</div>`)
                            toastMessages.push(message)
                        }

                        for (const [key, value] of Object.entries(errors)) {
                            const messages = Array.isArray(value) ? value : [value]
                            toastMessages = toastMessages.concat(messages)

                            if (form.find(`[name='${key}']`).length) {
                                form.find(`[name='${key}']`).parent().append(`<span class='text-danger text-small'>${messages.join('<br>')}</span>`)
                                continue
                            }

                            feedback.append(`<div class='alert alert-danger small mb-2'>${messages.join('<br>')}</div>`)
                        }

                        if (toastMessages.length) {
                            iziToast.error({
                                title: 'Import gagal',
                                message: toastMessages.join('<br>'),
                                position: 'topRight',
                                timeout: 10000,
                            })
                        }
                    }
                })
            })
        }
    </script>
@endpush
