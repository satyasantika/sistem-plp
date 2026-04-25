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
                .off('submit.assessmentStore', '#formAction')
                .on('submit.assessmentStore', '#formAction', function(e) {
                    e.preventDefault();
                    const _form = this;
                    const formData = new FormData(_form);
                    const isUpdate = !!_form.querySelector("input[name='_method'][value='PUT']");
                    const actionType = isUpdate ? 'update' : 'create';

                    const url = this.getAttribute('action');

                    $('.text-danger.text-small').remove();

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

                            if (typeof window.showOnlyAssessmentSubmitStatus === 'function') {
                                window.showOnlyAssessmentSubmitStatus(actionType, response.message);
                                return;
                            }

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
                                return;
                            }

                            iziToast.error({
                                title: 'Error',
                                message: 'Form penilaian gagal disimpan.',
                                position: 'topRight',
                            });
                        },
                    });
                });
        }

        $(`#${table}`)
            .off('click.assessmentAction', '.action')
            .on('click.assessmentAction', '.action', function() {
                let data = $(this).data();
                let id = data.id;
                let jenis = data.jenis;
                let form_order = data.form_order;
                let map_id = data.map_id;

                if (jenis == 'add') {
                    $.ajax({
                        method: 'GET',
                        url: document.URL + `/${form_order}/${map_id}/create`,
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
                    url: document.URL + `/${form_order}/${map_id}/${id}/edit`,
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
