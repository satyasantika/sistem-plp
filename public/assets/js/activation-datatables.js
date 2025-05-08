var updateActivation = (table) => {
    $(`#${table}`).on('click','.activation',function() {
        let data = $(this).data()
        let id = data.id

        $.ajax({
            method: 'POST',
            url: document.URL+`/konfigurasi/users/${id}/activation`,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(response) {
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
