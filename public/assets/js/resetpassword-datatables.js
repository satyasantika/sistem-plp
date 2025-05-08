var updateOnly = (table) => {
    $(`#${table}`).on('click','.reset',function() {
        let data = $(this).data()
        let id = data.id

        Swal.fire({
            title: 'Reset Password?',
            text: "Password user ini akan direset sehingga USERNAME = PASSWORD!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, reset!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'POST',
                    url: document.URL+`/password/reset/${id}`,
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
            }
        })
        return
    })
}
