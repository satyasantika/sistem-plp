var crudDataTables = (url, table) => {
    const modalElement = document.getElementById("modalAction");
    const modal = bootstrap?.Modal?.getOrCreateInstance
        ? bootstrap.Modal.getOrCreateInstance(modalElement)
        : new bootstrap.Modal(modalElement);
    let lastTableState = null;
    // let {url,table}
    $(".btn-add").on("click", function () {
        $.ajax({
            method: "GET",
            url: `${url}/create`,
            success: function (response) {
                $("#modalAction").find(".modal-dialog").html(response);
                modal.show();
                store();
            },
        });
    });

    const getDataTableInstance = () => {
        if (window.LaravelDataTables?.[`${table}`]) {
            return window.LaravelDataTables[`${table}`];
        }

        if ($.fn.DataTable.isDataTable(`#${table}`)) {
            return $(`#${table}`).DataTable();
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

    function store() {
        $("#formAction").on("submit", function (e) {
            e.preventDefault();
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
                    reloadTableKeepState();
                    modal.hide();
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
                            $(`[name='${key}']`)
                                .parent()
                                .append(
                                    `<span class='text-danger text-small'>${value}</span>`,
                                );
                        }
                    }
                },
            });
        });
    }

    $(`#${table}`).on("click", ".action", function () {
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
                        url: `${url}/${id}`,
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                "content",
                            ),
                        },
                        success: function (response) {
                            reloadTableKeepState();
                            iziToast.warning({
                                title: "Deleted!",
                                message: response.message,
                                position: "topRight",
                            });
                        },
                    });
                }
            });
            return;
        }

        $.ajax({
            method: "GET",
            url: `${url}/${id}/edit`,
            success: function (response) {
                $("#modalAction").find(".modal-dialog").html(response);
                modal.show();
                store();
            },
        });
    });
};
