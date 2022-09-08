var updateOnly = (table) => {
    $(`#${table}`).on('click','.action',function() {
        let data = $(this).data()
        let id = data.id

        Swal.fire({
            title: 'Verifikasi?',
            text: "Catatan ini akan diverifikasi!",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, verifikasi!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method: 'PUT',
                    url: URL+`/${id}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success : function(response) {
                        $(`#${table}`).load(document.URL +  ` #${table}`)
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
