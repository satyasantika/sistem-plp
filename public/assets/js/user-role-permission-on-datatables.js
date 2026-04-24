var userRolePermission = (table) => {
    const modalSelector = "#modalAction";
    const modalDialogSelector = "#modalAction .modal-dialog";
    const tableSelector = `#${table}`;
    const eventNs =
        (table || "rolepermission").replace(/[^a-zA-Z0-9]/g, "") ||
        "rolepermission";

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

        $(modalDialogSelector).css({
            pointerEvents: "auto",
            margin: "1.75rem auto",
            zIndex: "20001",
        });

        $("body").addClass("modal-open");
    };

    const resetForcedStyle = () => {
        $(modalSelector)
            .removeAttr("style")
            .removeClass("show")
            .attr("aria-hidden", "true")
            .removeAttr("aria-modal");

        $(modalDialogSelector).removeAttr("style");
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

    const showAjaxError = (response, fallbackMessage) => {
        iziToast.error({
            title: "Gagal",
            message: response.responseJSON?.message || fallbackMessage,
            position: "topRight",
        });
    };

    function bindStore() {
        $(document)
            .off(`submit.userRolePermissionStore.${eventNs}`, "#formAction")
            .on(
                `submit.userRolePermissionStore.${eventNs}`,
                "#formAction",
                function (e) {
                    e.preventDefault();
                    $("#formAction .text-danger.text-small").remove();

                    const _form = this;
                    const formData = new FormData(_form);

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
                            window.LaravelDataTables[`${table}`].ajax.reload();
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
                                for (const [key, value] of Object.entries(
                                    errors,
                                )) {
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
                },
            );
    }

    const loadModalContent = (url, fallbackMessage) => {
        $.ajax({
            method: "GET",
            url,
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
        .off(
            `click.rolePermissionAction.${eventNs}`,
            `${tableSelector} .rolepermission-action`,
        )
        .on(
            `click.rolePermissionAction.${eventNs}`,
            `${tableSelector} .rolepermission-action`,
            function () {
                let data = $(this).data();
                let id = data.id;
                let jenis = data.jenis;

                if (jenis == "rolepermission") {
                    loadModalContent(
                        `/konfigurasi/rolepermissions/${id}/edit`,
                        "Modal role permission tidak dapat dimuat.",
                    );
                    return;
                }

                if (jenis == "userpermission") {
                    loadModalContent(
                        `/konfigurasi/userpermissions/${id}/edit`,
                        "Modal user permission tidak dapat dimuat.",
                    );
                    return;
                }

                loadModalContent(
                    `/konfigurasi/userroles/${id}/edit`,
                    "Modal user role tidak dapat dimuat.",
                );
            },
        );

    $(document)
        .off(
            `click.userRolePermissionModalDismiss.${eventNs}`,
            `${modalSelector} [data-bs-dismiss="modal"]`,
        )
        .on(
            `click.userRolePermissionModalDismiss.${eventNs}`,
            `${modalSelector} [data-bs-dismiss="modal"]`,
            function (e) {
                e.preventDefault();
                closeModal();
            },
        );

    $(document)
        .off(`click.userRolePermissionModalBackdrop.${eventNs}`, modalSelector)
        .on(
            `click.userRolePermissionModalBackdrop.${eventNs}`,
            modalSelector,
            function (e) {
                if (e.target === this) {
                    closeModal();
                }
            },
        );

    ensureModalInBody();
};
