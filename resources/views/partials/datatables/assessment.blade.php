<script>
    var crudDataTables = function(table) {
        const modalElement = document.getElementById('modalAction');
        const $modal = $('#modalAction');
        const basePath = window.location.pathname.replace(/\/+$/, '');
        const currentViewUrl = `${basePath}${window.location.search || ''}`;
        let lastViewportY = window.scrollY || window.pageYOffset || 0;
        const createUrlTemplate = "{{ route('schoolassessments.only.create', ['form_id' => '__FORM__', 'form_order' => '__ORDER__', 'map_id' => '__MAP__']) }}";
        const editUrlTemplate = "{{ route('schoolassessments.only.edit', ['form_id' => '__FORM__', 'form_order' => '__ORDER__', 'map_id' => '__MAP__', 'schoolassessment' => '__ID__']) }}";

        const buildUrl = (template, replacements) => {
            let url = template;
            Object.entries(replacements).forEach(([key, value]) => {
                url = url.replace(key, encodeURIComponent(String(value)));
            });
            return url;
        };

        const getModal = () => {
            if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
                return null;
            }

            return window.bootstrap.Modal.getOrCreateInstance(modalElement);
        };

        const showModal = () => {
            try {
                const modal = getModal();
                if (modal) {
                    modal.show();
                    return;
                }
            } catch (e) {
                // fallback below
            }

            try {
                if (typeof $modal.modal === 'function') {
                    $modal.modal('show');
                    return;
                }
            } catch (e) {
                // fallback below
            }

            $modal.addClass('show').css('display', 'block').attr('aria-modal', 'true').removeAttr('aria-hidden');
            if (!$('.modal-backdrop').length) {
                $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
            }
            $('body').addClass('modal-open');
        };

        const hideModal = () => {
            try {
                const modal = getModal();
                if (modal) {
                    modal.hide();
                    return;
                }
            } catch (e) {
                // fallback below
            }

            try {
                if (typeof $modal.modal === 'function') {
                    $modal.modal('hide');
                    return;
                }
            } catch (e) {
                // fallback below
            }

            $modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true').removeAttr('aria-modal');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
        };

        const restoreViewport = () => {
            window.requestAnimationFrame(() => {
                window.scrollTo({
                    top: lastViewportY,
                    left: 0,
                    behavior: 'auto',
                });
            });
        };

        const reloadView = (callback) => {
            $(`#${table}`).load(`${currentViewUrl} #${table} > *`, function() {
                restoreViewport();
                if (typeof callback === 'function') {
                    callback();
                }
            });
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
                            hideModal();
                            reloadView(function() {
                                if (typeof window.showOnlyAssessmentSubmitStatus === 'function') {
                                    window.showOnlyAssessmentSubmitStatus(actionType, response.message);
                                    return;
                                }

                                iziToast.success({
                                    title: 'Saved!',
                                    message: response.message,
                                    position: 'topRight',
                                });
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
                lastViewportY = window.scrollY || window.pageYOffset || 0;
                let data = $(this).data();
                let id = data.id;
                let jenis = data.jenis;
                let formid = data.formid;
                let form_order = data.form_order;
                let map_id = data.map_id;

                const actionUrl = jenis == 'add'
                    ? buildUrl(createUrlTemplate, {
                        '__FORM__': formid,
                        '__ORDER__': form_order,
                        '__MAP__': map_id,
                    })
                    : buildUrl(editUrlTemplate, {
                        '__FORM__': formid,
                        '__ORDER__': form_order,
                        '__MAP__': map_id,
                        '__ID__': id,
                    });

                if (jenis == 'add') {
                    $.ajax({
                        method: 'GET',
                        url: actionUrl,
                        success: function(response) {
                            $('#modalAction').find('.modal-dialog').html(response);
                            showModal();
                            store();
                        },
                        error: function() {
                            iziToast.error({
                                title: 'Error',
                                message: 'Form penilaian tidak bisa dimuat.',
                                position: 'topRight',
                            });
                        },
                    });
                    return;
                }

                $.ajax({
                    method: 'GET',
                    url: actionUrl,
                    success: function(response) {
                        $('#modalAction').find('.modal-dialog').html(response);
                        showModal();
                        store();
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Form penilaian tidak bisa dimuat.',
                            position: 'topRight',
                        });
                    },
                });
            });
    };
</script>
