var importData = (table) => {
    const modal = new bootstrap.Modal($('#modalAction'))
    // let {url,table}
    $('.btn-import').on('click', function() {
        $.ajax({
            method: 'GET',
            url: `/konfigurasi/users/import/create`,
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
                    window.LaravelDataTables[`${table}`].ajax.reload()
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

}

