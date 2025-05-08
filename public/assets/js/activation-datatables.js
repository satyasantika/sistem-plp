var updateActivation = (table) => {
    $(`#${table}`).on('click','.activation',function() {
        let data = $(this).data()
        let id = data.id

        $.ajax({
            method: 'POST',
            url: URL+`/konfigurasi/users/${id}/activation`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response) {
                window.LaravelDataTables[`${table}`].ajax.reload()
                iziToast.success({
                    title: 'OK!',
                    message: response.message,
                    position: 'topRight'
                });
            }
        })
        return
    })
}
