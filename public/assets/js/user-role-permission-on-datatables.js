var userRolePermission = (table) => {
    const modal = new bootstrap.Modal($('#modalAction'))

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

    $(`#${table}`).on('click','.rolepermission-action',function() {
        let data = $(this).data()
        let id = data.id
        let jenis = data.jenis

        if (jenis == 'rolepermission') {
            $.ajax({
                method: 'GET',
                url: `rolepermissions/${id}/edit`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(response) {
                    $('#modalAction').find('.modal-dialog').html(response)
                    modal.show()
                    store()
                }
            })
            return
        }

        if (jenis == 'userpermission') {
            $.ajax({
                method: 'GET',
                url: `userpermissions/${id}/edit`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success : function(response) {
                    $('#modalAction').find('.modal-dialog').html(response)
                    modal.show()
                    store()
                }
            })
            return
        }

        $.ajax({
            method: 'GET',
            url: `userroles/${id}/edit`,
            success : function(response) {
                $('#modalAction').find('.modal-dialog').html(response)
                modal.show()
                store()
            }
        })
    })
}

