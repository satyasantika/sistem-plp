var crudDataTables = (url, table) => {
    const modalSelector = "#modalAction";
    const modalDialogSelector = "#modalAction .modal-dialog";
    const tableSelector = `#${table}`;
    const eventNs = (table || "crud").replace(/[^a-zA-Z0-9]/g, "") || "crud";
    const baseUrl = url.startsWith("/") ? url : `/konfigurasi/${url}`;
    let lastTableState = null;

    const ensureModalInBody = () => {
        const modalElement = document.querySelector(modalSelector);

        if (!modalElement || modalElement.parentElement === document.body) {
            return;
        }

        document.body.appendChild(modalElement);
    };

    const getModal = () => {
        const modalElement = document.getElementById("modalAction");

        if (!modalElement || !window.bootstrap || !window.bootstrap.Modal) {
            return null;
        }

        return window.bootstrap.Modal.getOrCreateInstance(modalElement);
    };

    const forceVisible = () => {
        const $modal = $(modalSelector);
        const $dialog = $(modalDialogSelector);

        $modal
            .css({
                display: "block",
                position: "fixed",
                inset: "0",
                zIndex: "20000",
                overflowX: "hidden",
                overflowY: "auto",
                background: "rgba(0,0,0,0.45)",
            })
            .addClass("show")
            .attr("aria-modal", "true")
            .removeAttr("aria-hidden");

        $dialog.css({
            pointerEvents: "auto",
            margin: "1.75rem auto",
            zIndex: "20001",
        });

        $("body").addClass("modal-open");
    };

    const resetForcedStyle = () => {
        const $modal = $(modalSelector);
        const $dialog = $(modalDialogSelector);

        $modal
            .removeAttr("style")
            .removeClass("show")
            .attr("aria-hidden", "true")
            .removeAttr("aria-modal");

        $dialog.removeAttr("style");
        $("body").removeClass("modal-open");
        $(".modal-backdrop").remove();
    };

    const openModal = () => {
        const modal = getModal();

        if (modal) {
            modal.show();
            forceVisible();
            return true;
        }

        if ($.fn.modal && $(modalSelector).length) {
            $(modalSelector).modal("show");
            forceVisible();
            return true;
        }

        if ($(modalSelector).length) {
            forceVisible();
            return true;
        }

        return false;
    };

    const closeModal = () => {
        const modal = getModal();

        if (modal) {
            modal.hide();
            resetForcedStyle();
            return;
        }

        if ($.fn.modal && $(modalSelector).length) {
            $(modalSelector).modal("hide");
            resetForcedStyle();
            return;
        }

        resetForcedStyle();
    };

    const getDataTableInstance = () => {
        if (window.LaravelDataTables?.[`${table}`]) {
            return window.LaravelDataTables[`${table}`];
        }

        if ($.fn.DataTable.isDataTable(tableSelector)) {
            return $(tableSelector).DataTable();
        }

        return null;
    };

    const rememberTableState = () => {
        const dt = getDataTableInstance();

        if (!dt) {
            return;
        }

        lastTableState = {
            page: dt.page(),
        };
    };

    const reloadTableKeepState = () => {
        const dt = getDataTableInstance();

        if (!dt) {
            return;
        }

        const fallbackState = {
            page: dt.page(),
        };
        const state = lastTableState || fallbackState;

        dt.ajax.reload(function () {
            if (dt.page() !== state.page) {
                dt.page(state.page);
            }

            dt.draw("page");
        }, false);

        lastTableState = null;
    };

    const clearValidationErrors = () => {
        $("#formAction .text-danger.text-small").remove();
    };

    const showAjaxError = (response, fallbackMessage) => {
        iziToast.error({
            title: "Gagal",
            message: response.responseJSON?.message || fallbackMessage,
            position: "topRight",
        });
    };

    function bindStore() {
        $(document)
            .off(`submit.crudStore.${eventNs}`, "#formAction")
            .on(`submit.crudStore.${eventNs}`, "#formAction", function (e) {
                e.preventDefault();
                clearValidationErrors();

                const _form = this;
                const formData = new FormData(_form);

                const actionUrl = this.getAttribute("action");

                $.ajax({
                    method: "POST",
                    url: actionUrl,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content",
                        ),
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        reloadTableKeepState();
                        closeModal();
                        iziToast.success({
                            title: "Saved!",
                            message: response.message,
                            position: "topRight",
                        });
                    },
                    error: function (response) {
                        let errors = response.responseJSON?.errors;

                        if (errors) {
                            for (const [key, value] of Object.entries(errors)) {
                                $("#formAction")
                                    .find(`[name='${key}']`)
                                    .parent()
                                    .append(
                                        `<span class='text-danger text-small'>${value}</span>`,
                                    );
                            }
                        }

                        showAjaxError(response, "Data gagal disimpan.");
                    },
                });
            });
    }

    const loadModalContent = (targetUrl, fallbackMessage) => {
        $.ajax({
            method: "GET",
            url: targetUrl,
            success: function (response) {
                ensureModalInBody();
                $(modalDialogSelector).html(response);

                if (!openModal()) {
                    iziToast.error({
                        title: "Gagal",
                        message: "Modal API tidak tersedia.",
                        position: "topRight",
                    });
                    return;
                }

                bindStore();
            },
            error: function (response) {
                showAjaxError(response, fallbackMessage);
            },
        });
    };

    $(document)
        .off(`click.crudAdd.${eventNs}`, ".btn-add")
        .on(`click.crudAdd.${eventNs}`, ".btn-add", function () {
            loadModalContent(`${baseUrl}/create`, "Modal tidak dapat dimuat.");
        });

    $(document)
        .off(`click.crudAction.${eventNs}`, `${tableSelector} .action`)
        .on(
            `click.crudAction.${eventNs}`,
            `${tableSelector} .action`,
            function () {
                let data = $(this).data();
                let id = data.id;
                let jenis = data.jenis;

                rememberTableState();

                if (jenis == "delete") {
                    Swal.fire({
                        title: "Hapus permanen?",
                        text: "Data sepenuhnya akan terhapus dari sistem!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                method: "DELETE",
                                url: `${baseUrl}/${id}`,
                                headers: {
                                    "X-CSRF-TOKEN": $(
                                        'meta[name="csrf-token"]',
                                    ).attr("content"),
                                },
                                success: function (response) {
                                    reloadTableKeepState();
                                    iziToast.warning({
                                        title: "Deleted!",
                                        message: response.message,
                                        position: "topRight",
                                    });
                                },
                                error: function (response) {
                                    showAjaxError(
                                        response,
                                        "Data gagal dihapus.",
                                    );
                                },
                            });
                        }
                    });
                    return;
                }

                loadModalContent(
                    `${baseUrl}/${id}/edit`,
                    "Modal tidak dapat dimuat.",
                );
            },
        );

    $(document)
        .off(
            `click.crudModalDismiss.${eventNs}`,
            `${modalSelector} [data-bs-dismiss="modal"]`,
        )
        .on(
            `click.crudModalDismiss.${eventNs}`,
            `${modalSelector} [data-bs-dismiss="modal"]`,
            function (e) {
                e.preventDefault();
                closeModal();
            },
        );

    $(document)
        .off(`click.crudModalBackdrop.${eventNs}`, modalSelector)
        .on(`click.crudModalBackdrop.${eventNs}`, modalSelector, function (e) {
            if (e.target === this) {
                closeModal();
            }
        });

    ensureModalInBody();
};
