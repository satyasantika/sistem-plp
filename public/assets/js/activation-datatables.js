var updateActivation = (table) => {
    $(`#${table}`).on('click','.activation',function() {
        let data = $(this).data()
        let id = data.id

        $.ajax({
            method: 'POST',
            url: `/konfigurasi/users/${id}/activation`,
            // url: `/plp/konfigurasi/users/${id}/activation`, // untuk hosting di supportfkip.unsil.ac.id/plp
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
