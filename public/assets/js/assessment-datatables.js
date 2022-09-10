var crudDataTables = (table) => {
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
                    $(`#${table}`).load(document.URL +  ` #${table}`)
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

    $(`#${table}`).on('click','.action',function() {
        let data = $(this).data()
        let id = data.id
        let jenis = data.jenis
        let form_order = data.form_order
        let map_id = data.map_id

        if (jenis == 'add') {
            $.ajax({
            method: 'GET',
            url: document.URL+`/${form_order}/${map_id}/create`,
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
            url: document.URL+`/${form_order}/${map_id}/${id}/edit`,
            success : function(response) {
                $('#modalAction').find('.modal-dialog').html(response)
                modal.show()
                store()
            }
        })
    })
}

