@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    @if(!empty($enableFormItemsReorder))
        <style>
            .form-items-section table tbody tr[data-item-id] {
                cursor: grab;
            }

            .form-items-section table tbody tr[data-item-id].dragging {
                opacity: 0.88;
                background: rgba(128, 128, 128, 0.1);
            }
        </style>
    @endif
@endpush

@section('content')
    <div class="main-content">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <h3 class="mb-0">Item form — {{ $form->id }} · {{ $form->name }}</h3>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('forms.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Daftar form</a>
                @if(!empty($enableFormItemsReorder))
                    <button type="button" id="btnSaveFormItemsOrder" class="btn btn-success btn-sm">Simpan urutan</button>
                    <span class="text-muted small align-self-center d-none d-md-inline">Tarik baris dalam satu bagian untuk mengurutkan.</span>
                @endif
            </div>
        </div>

        <div class="content-wrapper">
            @foreach ($sectionLabels as $componentKey => $sectionTitle)
                <div class="card mb-3 form-items-section" id="section-{{ $componentKey }}">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2">
                        <span class="fw-semibold">{{ $sectionTitle }}</span>
                        @can('formitems-create')
                            <button type="button"
                                class="btn btn-primary btn-sm btn-add-form-item"
                                data-create-url="{{ $sectionCreateUrls[$componentKey] ?? '#' }}">
                                + Tambah {{ strtolower($sectionTitle) }}
                            </button>
                        @endcan
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-sm table-striped mb-0" id="tbl-section-{{ $componentKey }}">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5rem">Urutan</th>
                                    <th>Rincian item</th>
                                    <th style="width: 6rem" class="text-end">Skor</th>
                                    <th style="width: 6rem" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($groupedRows[$componentKey] ?? [] as $fi)
                                    <tr data-item-id="{{ $fi->id }}" @if(!empty($enableFormItemsReorder)) draggable="true" @endif>
                                        <td class="text-end">{{ $fi->component_order }}</td>
                                        <td>{!! nl2br(e($fi->name)) !!}</td>
                                        <td class="text-end">{{ $fi->max_score }}</td>
                                        <td class="text-center text-nowrap">
                                            @can('formitems-update')
                                                <button type="button" data-id="{{ $fi->id }}" data-jenis="edit"
                                                    class="btn btn-primary btn-sm py-0 px-1 action"><i class="ti-pencil"></i></button>
                                            @endcan
                                            @can('formitems-delete')
                                                <button type="button" data-id="{{ $fi->id }}" data-jenis="delete"
                                                    class="btn btn-danger btn-sm py-0 px-1 action"><i class="ti-trash"></i></button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="form-items-empty-placeholder">
                                        <td colspan="4" class="text-muted small ps-3 py-2">Belum ada item pada bagian ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            @if ($otherItems->isNotEmpty())
                <div class="card mb-3 form-items-section border-warning" id="section-other">
                    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2 py-2 bg-warning bg-opacity-10">
                        <span class="fw-semibold">Komponen lain <span class="text-muted small fw-normal">(nilai <code>component</code> di luar tiga bagian standar)</span></span>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-sm table-striped mb-0" id="tbl-section-other">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 6rem">Komponen</th>
                                    <th style="width: 5rem">Urutan</th>
                                    <th>Rincian item</th>
                                    <th style="width: 6rem" class="text-end">Skor</th>
                                    <th style="width: 6rem" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($otherItems as $fi)
                                    <tr data-item-id="{{ $fi->id }}" @if(!empty($enableFormItemsReorder)) draggable="true" @endif>
                                        <td><code>{{ $fi->component }}</code></td>
                                        <td class="text-end">{{ $fi->component_order }}</td>
                                        <td>{!! nl2br(e($fi->name)) !!}</td>
                                        <td class="text-end">{{ $fi->max_score }}</td>
                                        <td class="text-center text-nowrap">
                                            @can('formitems-update')
                                                <button type="button" data-id="{{ $fi->id }}" data-jenis="edit"
                                                    class="btn btn-primary btn-sm py-0 px-1 action"><i class="ti-pencil"></i></button>
                                            @endcan
                                            @can('formitems-delete')
                                                <button type="button" data-id="{{ $fi->id }}" data-jenis="delete"
                                                    class="btn btn-danger btn-sm py-0 px-1 action"><i class="ti-trash"></i></button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document"></div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        (function() {
            const resourceBase = @json($itemsResourceBase);
            const reorderUrl = @json($formsItemsReorderUrl ?? '');
            const enableReorder = @json(!empty($enableFormItemsReorder));
            const sectionKeys = @json(array_keys($sectionLabels));
            const hasOtherSection = @json($otherItems->isNotEmpty());

            const modalSelector = '#modalAction';
            const dialogSelector = '#modalAction .modal-dialog';

            const getCsrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const toastError = (message) => {
                if (window.iziToast) {
                    window.iziToast.error({ title: 'Gagal', message, position: 'topRight' });
                    return;
                }
                console.error(message);
            };

            const toastSuccess = (message) => {
                if (window.iziToast) {
                    window.iziToast.success({ title: 'Saved!', message, position: 'topRight' });
                    return;
                }
                console.log(message);
            };

            const ensureModalInBody = () => {
                const modal = document.querySelector(modalSelector);
                if (!modal || modal.parentElement === document.body) {
                    return modal;
                }
                document.body.appendChild(modal);
                return modal;
            };

            const openModal = () => {
                const modal = ensureModalInBody();
                if (!modal) {
                    return;
                }
                modal.classList.add('show');
                modal.style.display = 'block';
                modal.style.position = 'fixed';
                modal.style.inset = '0';
                modal.style.zIndex = '20000';
                modal.style.overflowX = 'hidden';
                modal.style.overflowY = 'auto';
                modal.style.background = 'rgba(0,0,0,0.45)';
                modal.setAttribute('aria-modal', 'true');
                modal.removeAttribute('aria-hidden');
                const dialog = document.querySelector(dialogSelector);
                if (dialog) {
                    dialog.style.pointerEvents = 'auto';
                    dialog.style.margin = '1.75rem auto';
                    dialog.style.zIndex = '20001';
                }
                document.body.classList.add('modal-open');
            };

            const closeModal = () => {
                const modal = document.querySelector(modalSelector);
                if (!modal) {
                    return;
                }
                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.removeAttribute('style');
                modal.setAttribute('aria-hidden', 'true');
                modal.removeAttribute('aria-modal');
                const dialog = document.querySelector(dialogSelector);
                if (dialog) {
                    dialog.removeAttribute('style');
                    dialog.innerHTML = '';
                }
                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove());
            };

            const loadModal = async (url, fallbackMessage) => {
                try {
                    const response = await fetch(url, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    if (!response.ok) {
                        throw new Error(fallbackMessage);
                    }
                    const html = await response.text();
                    const dialog = document.querySelector(dialogSelector);
                    if (!dialog) {
                        throw new Error('Modal container tidak ditemukan.');
                    }
                    dialog.innerHTML = html;
                    openModal();
                } catch (error) {
                    toastError(error.message || fallbackMessage);
                }
            };

            const submitForm = async (form) => {
                const formData = new FormData(form);
                const action = form.getAttribute('action');
                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });
                    const data = await response.json().catch(() => ({}));
                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach((key) => {
                                const input = form.querySelector(`[name="${key}"]`);
                                if (!input || !input.parentElement) {
                                    return;
                                }
                                const span = document.createElement('span');
                                span.className = 'text-danger text-small';
                                span.textContent = data.errors[key];
                                input.parentElement.appendChild(span);
                            });
                        }
                        throw new Error(data.message || 'Data gagal disimpan.');
                    }
                    closeModal();
                    toastSuccess(data.message || 'Data berhasil disimpan.');
                    window.location.reload();
                } catch (error) {
                    toastError(error.message || 'Data gagal disimpan.');
                }
            };

            function wireDelegatedDnD(tableEl) {
                if (!tableEl || tableEl.dataset.dndBound === '1') {
                    return;
                }
                tableEl.dataset.dndBound = '1';
                let srcRow = null;

                tableEl.addEventListener('dragstart', function(e) {
                    const row = e.target.closest('tbody tr[data-item-id]');
                    if (!row || !tableEl.contains(row)) {
                        return;
                    }
                    srcRow = row;
                    try {
                        e.dataTransfer.effectAllowed = 'move';
                        e.dataTransfer.setData('text/plain', row.getAttribute('data-item-id') || '');
                    } catch (err) {}
                    row.classList.add('dragging');
                });

                tableEl.addEventListener('dragend', function(e) {
                    const row = e.target.closest('tbody tr[data-item-id]');
                    if (row) {
                        row.classList.remove('dragging');
                    }
                    srcRow = null;
                });

                tableEl.addEventListener('dragover', function(e) {
                    if (!srcRow) {
                        return;
                    }
                    const tbody = tableEl.querySelector('tbody');
                    const row = e.target.closest('tbody tr[data-item-id]');
                    if (!tbody || !row || row === srcRow || !tbody.contains(row)) {
                        return;
                    }
                    e.preventDefault();
                    const rect = row.getBoundingClientRect();
                    const after = e.clientY > rect.top + rect.height / 2;
                    tbody.insertBefore(srcRow, after ? row.nextSibling : row);
                });
            }

            function markRowsDraggable() {
                document.querySelectorAll('.form-items-section table tbody tr[data-item-id]').forEach(function(row) {
                    row.setAttribute('draggable', 'true');
                });
            }

            function collectOrder() {
                const order = [];
                sectionKeys.forEach(function(comp) {
                    document.querySelectorAll('#tbl-section-' + comp + ' tbody tr[data-item-id]').forEach(function(tr) {
                        const id = parseInt(tr.getAttribute('data-item-id'), 10);
                        if (!Number.isNaN(id)) {
                            order.push(id);
                        }
                    });
                });
                if (hasOtherSection) {
                    document.querySelectorAll('#tbl-section-other tbody tr[data-item-id]').forEach(function(tr) {
                        const id = parseInt(tr.getAttribute('data-item-id'), 10);
                        if (!Number.isNaN(id)) {
                            order.push(id);
                        }
                    });
                }
                return order;
            }

            document.addEventListener('click', function(event) {
                const dismissButton = event.target.closest(`${modalSelector} [data-bs-dismiss="modal"]`);
                if (dismissButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    closeModal();
                    return;
                }

                const modalRoot = event.target.closest(modalSelector);
                if (modalRoot && event.target === modalRoot) {
                    event.preventDefault();
                    closeModal();
                    return;
                }

                const addBtn = event.target.closest('.btn-add-form-item');
                if (addBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    const url = addBtn.getAttribute('data-create-url');
                    if (url) {
                        loadModal(url, 'Modal tidak dapat dimuat.');
                    }
                    return;
                }

                const actionButton = event.target.closest('.action');
                if (actionButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    const id = actionButton.getAttribute('data-id');
                    const jenis = actionButton.getAttribute('data-jenis');

                    if (jenis === 'delete') {
                        if (!window.confirm('Hapus permanen data ini?')) {
                            return;
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
                                    throw new Error(data.message || 'Data gagal dihapus.');
                                }
                                window.location.reload();
                            })
                            .catch((error) => toastError(error.message || 'Data gagal dihapus.'));
                        return;
                    }

                    loadModal(`${resourceBase}/${id}/edit`, 'Modal tidak dapat dimuat.');
                }
            }, true);

            document.addEventListener('submit', function(event) {
                const form = event.target.closest('#formAction');
                if (!form) {
                    return;
                }
                event.preventDefault();
                submitForm(form);
            });

            if (enableReorder && reorderUrl) {
                document.getElementById('btnSaveFormItemsOrder')?.addEventListener('click', function() {
                    const order = collectOrder();
                    if (!order.length) {
                        return;
                    }
                    fetch(reorderUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrf(),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ order: order })
                        })
                        .then((r) => r.json().catch(() => ({})).then((json) => ({ r, json })))
                        .then(({ r, json }) => {
                            if (!r.ok) {
                                throw new Error(json.message || 'Urutan gagal disimpan.');
                            }
                            toastSuccess(json.message || 'Urutan disimpan.');
                            window.location.reload();
                        })
                        .catch((err) => toastError(err.message || 'Urutan gagal disimpan.'));
                });

                sectionKeys.forEach(function(comp) {
                    wireDelegatedDnD(document.getElementById('tbl-section-' + comp));
                });
                if (hasOtherSection) {
                    wireDelegatedDnD(document.getElementById('tbl-section-other'));
                }
                markRowsDraggable();
            }

            ensureModalInBody();
        })();
    </script>
@endpush
