<script>
    var crudDataTables = function(table) {
        const modalElement = document.getElementById('modalAction');
        const getModal = () => {
            if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
                return null;
            }

            return window.bootstrap.Modal.getOrCreateInstance(modalElement);
        };

        function store() {
            $(document)
                .off('submit.observationStore', '#formAction')
                .on('submit.observationStore', '#formAction', function(e) {
                    e.preventDefault();
                    const _form = this;
                    const formData = new FormData(_form);

                    const url = this.getAttribute('action');

                    $.ajax({
                        method: 'POST',
                        url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $(`#${table}`).load(document.URL + ` #${table}`);
                            const modal = getModal();
                            modal?.hide();
                            iziToast.success({
                                title: 'Saved!',
                                message: response.message,
                                position: 'topRight',
                            });
                        },
                        error: function(response) {
                            let errors = response.responseJSON?.errors;

                            if (errors) {
                                for (const [key, value] of Object.entries(errors)) {
                                    $(`[name='${key}']`)
                                        .parent()
                                        .append(`<span class='text-danger text-small'>${value}</span>`);
                                }
                            }
                        },
                    });
                });
        }

        $(`#${table}`)
            .off('click.observationAction', '.action')
            .on('click.observationAction', '.action', function() {
                let data = $(this).data();
                let id = data.id;
                let jenis = data.jenis;
                let formid = data.formid;

                if (jenis == 'add') {
                    $.ajax({
                        method: 'GET',
                        url: document.URL + `/${formid}/create`,
                        success: function(response) {
                            $('#modalAction').find('.modal-dialog').html(response);
                            const modal = getModal();
                            modal?.show();
                            store();
                        },
                    });
                    return;
                }

                $.ajax({
                    method: 'GET',
                    url: document.URL + `/${formid}/${id}/edit`,
                    success: function(response) {
                        $('#modalAction').find('.modal-dialog').html(response);
                        const modal = getModal();
                        modal?.show();
                        store();
                    },
                });
            });
    };
</script>
