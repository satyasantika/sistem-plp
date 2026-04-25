@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
        {{ ucFirst(request()->segment(1)) }} {{ ucFirst(request()->segment(2)) }}
    <div class="content-wrapper">
        <div class="row same-height">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ ucFirst(request()->segment(1)) }} {{ ucFirst(request()->segment(2)) }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-primary btn-sm btn-add">+ {{ request()->segment(2) }}</button>
                                Reload Table
                            </button>
                        </div>
                        <div class="table-responsive">
                        {{ $dataTable->table(['class' => 'display nowrap']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    {{-- <script src="{{ asset('') }}vendor/select2/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    {{ $dataTable->scripts() }}
    <script>
        // Compatibility shim after removing legacy crud-datatables.js.
        window.crudDataTables = window.crudDataTables || function() {};
    </script>
    <script>
        $(function() {
            $('.btn-reload-table').on('click', function() {
                const tableId = $(this).data('table-id')

                if (window.LaravelDataTables?.[tableId]) {
                    window.LaravelDataTables[tableId].ajax.reload(null, false)
                    return
                }

                if ($.fn.DataTable.isDataTable(`#${tableId}`)) {
                    $(`#${tableId}`).DataTable().ajax.reload(null, false)
                }
            })
        })
    </script>
    @stack('jscode')
    <script>
        (function() {
            const pathname = window.location.pathname || ''
            const parts = pathname.split('/').filter(Boolean)
            const konfigurasiIndex = parts.indexOf('konfigurasi')
            const resource = konfigurasiIndex >= 0 ? parts[konfigurasiIndex + 1] : null

            if (!resource || resource === 'users') {
                return
            }

            const modalSelector = '#modalAction'
            const dialogSelector = '#modalAction .modal-dialog'
            const tableId = document.querySelector('.btn-reload-table')?.getAttribute('data-table-id')

            const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

            const toastError = (message) => {
                if (window.iziToast) {
                    window.iziToast.error({ title: 'Gagal', message, position: 'topRight' })
                    return
                }

                console.error(message)
            }

            const toastSuccess = (message) => {
                if (window.iziToast) {
                    window.iziToast.success({ title: 'Saved!', message, position: 'topRight' })
                    return
                }

                console.log(message)
            }

            const ensureModalInBody = () => {
                const modal = document.querySelector(modalSelector)

                if (!modal || modal.parentElement === document.body) {
                    return modal
                }

                document.body.appendChild(modal)
                return modal
            }

            const openModal = () => {
                const modal = ensureModalInBody()
                if (!modal) {
                    return
                }

                modal.classList.add('show')
                modal.style.display = 'block'
                modal.style.position = 'fixed'
                modal.style.inset = '0'
                modal.style.zIndex = '20000'
                modal.style.overflowX = 'hidden'
                modal.style.overflowY = 'auto'
                modal.style.background = 'rgba(0,0,0,0.45)'
                modal.setAttribute('aria-modal', 'true')
                modal.removeAttribute('aria-hidden')

                const dialog = document.querySelector(dialogSelector)
                if (dialog) {
                    dialog.style.pointerEvents = 'auto'
                    dialog.style.margin = '1.75rem auto'
                    dialog.style.zIndex = '20001'
                }

                document.body.classList.add('modal-open')
            }

            const closeModal = () => {
                const modal = document.querySelector(modalSelector)
                if (!modal) {
                    return
                }

                modal.classList.remove('show')
                modal.style.display = 'none'
                modal.removeAttribute('style')
                modal.setAttribute('aria-hidden', 'true')
                modal.removeAttribute('aria-modal')

                const dialog = document.querySelector(dialogSelector)
                if (dialog) {
                    dialog.removeAttribute('style')
                }

                document.body.classList.remove('modal-open')
                document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove())
            }

            const reloadTable = () => {
                if (tableId) {
                    if (window.LaravelDataTables && window.LaravelDataTables[tableId]) {
                        window.LaravelDataTables[tableId].ajax.reload(null, false)
                        return
                    }

                    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.DataTable && window.jQuery.fn.DataTable.isDataTable(`#${tableId}`)) {
                        window.jQuery(`#${tableId}`).DataTable().ajax.reload(null, false)
                        return
                    }
                }

                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.DataTable) {
                    const firstTable = document.querySelector('table[id$="-table"]')
                    if (!firstTable) {
                        return
                    }

                    const firstSelector = `#${firstTable.id}`
                    if (window.LaravelDataTables && window.LaravelDataTables[firstTable.id]) {
                        window.LaravelDataTables[firstTable.id].ajax.reload(null, false)
                        return
                    }

                    if (window.jQuery.fn.DataTable.isDataTable(firstSelector)) {
                        window.jQuery(firstSelector).DataTable().ajax.reload(null, false)
                    }
                }
            }

            const loadModal = async (url, fallbackMessage) => {
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })

                    if (!response.ok) {
                        throw new Error(fallbackMessage)
                    }

                    const html = await response.text()
                    const dialog = document.querySelector(dialogSelector)
                    if (!dialog) {
                        throw new Error('Modal container tidak ditemukan.')
                    }

                    dialog.innerHTML = html
                    openModal()
                } catch (error) {
                    toastError(error.message || fallbackMessage)
                }
            }

            const submitForm = async (form) => {
                const formData = new FormData(form)
                const action = form.getAttribute('action')

                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })

                    const data = await response.json().catch(() => ({}))

                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach((key) => {
                                const input = form.querySelector(`[name="${key}"]`)
                                if (!input || !input.parentElement) {
                                    return
                                }

                                const span = document.createElement('span')
                                span.className = 'text-danger text-small'
                                span.textContent = data.errors[key]
                                input.parentElement.appendChild(span)
                            })
                        }

                        throw new Error(data.message || 'Data gagal disimpan.')
                    }

                    reloadTable()
                    closeModal()
                    toastSuccess(data.message || 'Data berhasil disimpan.')
                } catch (error) {
                    toastError(error.message || 'Data gagal disimpan.')
                }
            }

            const rolePermissionUrl = (jenis, id) => {
                if (jenis === 'rolepermission') {
                    return `{{ url('konfigurasi/rolepermissions') }}/${id}/edit`
                }
                if (jenis === 'userpermission') {
                    return `{{ url('konfigurasi/userpermissions') }}/${id}/edit`
                }

                return `{{ url('konfigurasi/userroles') }}/${id}/edit`
            }

            const resourceBase = `{{ url('konfigurasi') }}/${resource}`

            document.addEventListener('click', function(event) {
                const dismissButton = event.target.closest(`${modalSelector} [data-bs-dismiss="modal"]`)
                if (dismissButton) {
                    event.preventDefault()
                    event.stopPropagation()
                    closeModal()
                    return
                }

                const modalRoot = event.target.closest(modalSelector)
                if (modalRoot && event.target === modalRoot) {
                    event.preventDefault()
                    closeModal()
                    return
                }

                const addButton = event.target.closest('.btn-add')
                if (addButton) {
                    event.preventDefault()
                    event.stopPropagation()
                    loadModal(`${resourceBase}/create`, 'Modal tidak dapat dimuat.')
                    return
                }

                const rolePermissionButton = event.target.closest('.rolepermission-action')
                if (rolePermissionButton) {
                    event.preventDefault()
                    event.stopPropagation()

                    const id = rolePermissionButton.getAttribute('data-id')
                    const jenis = rolePermissionButton.getAttribute('data-jenis')
                    loadModal(rolePermissionUrl(jenis, id), 'Modal role/permission tidak dapat dimuat.')
                    return
                }

                const actionButton = event.target.closest('.action')
                if (actionButton) {
                    event.preventDefault()
                    event.stopPropagation()

                    const id = actionButton.getAttribute('data-id')
                    const jenis = actionButton.getAttribute('data-jenis')

                    if (jenis === 'delete') {
                        const doDelete = window.confirm('Hapus permanen data ini?')
                        if (!doDelete) {
                            return
                        }

                        fetch(`${resourceBase}/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': getCsrf(),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then((response) => response.json().catch(() => ({})).then((data) => ({ response, data })))
                            .then(({ response, data }) => {
                                if (!response.ok) {
                                    throw new Error(data.message || 'Data gagal dihapus.')
                                }

                                reloadTable()
                                if (window.iziToast) {
                                    window.iziToast.warning({ title: 'Deleted!', message: data.message || 'Data berhasil dihapus.', position: 'topRight' })
                                }
                            })
                            .catch((error) => {
                                toastError(error.message || 'Data gagal dihapus.')
                            })

                        return
                    }

                    loadModal(`${resourceBase}/${id}/edit`, 'Modal tidak dapat dimuat.')
                }
            }, true)

            document.addEventListener('submit', function(event) {
                const form = event.target.closest('#formAction')
                if (!form) {
                    return
                }

                event.preventDefault()
                submitForm(form)
            })

            ensureModalInBody()
        })()
    </script>

@endpush
