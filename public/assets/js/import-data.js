var importData = (table, options = {}) => {
    const modalElement = document.getElementById("modalAction");
    const modalDialog = $("#modalAction").find(".modal-dialog");
    const getModal = () => {
        if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
            return null;
        }

        return window.bootstrap.Modal.getOrCreateInstance(modalElement);
    };
    const settings = {
        createUrl: "/konfigurasi/users/import/create",
        ...options,
    };

    $(document)
        .off("click.importData", ".btn-import")
        .on("click.importData", ".btn-import", function () {
            const role = this.dataset.role;
            const title = this.dataset.title;

            $.ajax({
                method: "GET",
                url: settings.createUrl,
                data: {
                    role,
                    title,
                },
                success: function (response) {
                    modalDialog
                        .removeClass("modal-lg")
                        .addClass("modal-xl")
                        .html(response);
                    const modal = getModal();
                    modal?.show();
                    bindImportFlow();
                },
                error: function (response) {
                    const message =
                        response.responseJSON?.message ||
                        "Modal import gagal dimuat.";

                    iziToast.error({
                        title: "Import gagal",
                        message,
                        position: "topRight",
                        timeout: 10000,
                    });
                },
            });
        });

    if (modalElement) {
        modalElement.addEventListener("hidden.bs.modal", function () {
            modalDialog.html("");
        });
    }

    function bindImportFlow() {
        const form = $("#formAction");

        form.find("[name='file']").on("change", function () {
            clearFeedback(form);
        });

        form.off("submit.importData").on("submit.importData", function (e) {
            e.preventDefault();
            clearFeedback(form);

            const formData = new FormData(this);
            const url = this.getAttribute("action");

            $.ajax({
                method: "POST",
                url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content",
                    ),
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    reloadTable();
                    const modal = getModal();
                    modal?.hide();

                    iziToast.success({
                        title: "Saved!",
                        message: response.message,
                        position: "topRight",
                    });
                },
                error: function (response) {
                    renderErrors(response, form);
                },
            });
        });
    }

    function reloadTable() {
        if (window.LaravelDataTables?.[table]) {
            window.LaravelDataTables[table].ajax.reload(null, false);
            return;
        }

        if ($.fn.DataTable.isDataTable(`#${table}`)) {
            $(`#${table}`).DataTable().ajax.reload(null, false);
        }
    }

    function clearFeedback(form) {
        form.find(".import-feedback").empty();
        form.find(".text-danger.text-small").remove();
    }

    function renderErrors(response, form) {
        const feedback = form.find(".import-feedback");
        const errors = response.responseJSON?.errors;
        const message = response.responseJSON?.message;
        let toastMessages = [];

        if (message) {
            feedback.html(
                `<div class='alert alert-danger small mb-3'>${message}</div>`,
            );
            toastMessages.push(message);
        }

        if (errors) {
            for (const [key, value] of Object.entries(errors)) {
                const messages = Array.isArray(value) ? value : [value];
                toastMessages = toastMessages.concat(messages);

                if (form.find(`[name='${key}']`).length) {
                    form.find(`[name='${key}']`)
                        .parent()
                        .append(
                            `<span class='text-danger text-small'>${messages.join("<br>")}</span>`,
                        );
                    continue;
                }

                feedback.append(
                    `<div class='alert alert-danger small mb-2'>${messages.join("<br>")}</div>`,
                );
            }
        }

        if (toastMessages.length) {
            iziToast.error({
                title: "Import gagal",
                message: toastMessages.join("<br>"),
                position: "topRight",
                timeout: 10000,
            });
        }
    }
};
