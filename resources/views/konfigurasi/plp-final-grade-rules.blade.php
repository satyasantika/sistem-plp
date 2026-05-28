@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
<div class="main-content">
    <h3>Sebaran Form</h3>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="card">
                <div class="card-body">
                    @php
                        $plpLabel = static function (int $order): string {
                            return match ($order) {
                                0 => 'PLP',
                                1 => 'PLP 1',
                                2 => 'PLP 2',
                                default => 'PLP ?',
                            };
                        };
                        $assessorLabel = static function (string $a): string {
                            return $a === 'guru' ? 'Guru pamong' : 'Dosen';
                        };
                    @endphp
                    <div class="card border-secondary mb-4">
                        <div class="card-body py-3">
                            <p class="small text-muted mb-3">
                                Tahun akademik: <strong>{{ $activeYear }}</strong> (sesuai pemilih tahun di header).
                            </p>
                            <h6 class="text-secondary mb-1">Bobot gabungan Dosen &amp; Guru pamong</h6>
                            <p class="small text-muted mb-2">Proporsi nilai dosen dan guru pamong dalam perhitungan nilai akhir. Jumlah keduanya harus 1,00.</p>
                            <form method="post" action="{{ route('plpfinalgraderules.weights') }}" class="row gy-2 gx-3 align-items-end">
                                @csrf
                                <div class="col-auto">
                                    <label class="form-label mb-1 small text-muted">Sesi PLP</label>
                                    <select name="plp_order" id="weightPlpOrder" class="form-select form-select-sm" style="width:auto;">
                                        <option value="0">PLP</option>
                                        <option value="1">PLP 1</option>
                                        <option value="2" selected>PLP 2</option>
                                    </select>
                                </div>
                                <div class="col-auto">
                                    <label class="form-label mb-1 small text-muted">Bobot Dosen</label>
                                    <input type="number" step="0.01" min="0" max="1" name="dosen_weight" id="weightDosen" value="{{ old('dosen_weight', number_format((float) $weights[2]->dosen_weight, 2, '.', '')) }}" class="form-control form-control-sm" style="width: 6rem;"/>
                                    @error('dosen_weight')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-auto">
                                    <label class="form-label mb-1 small text-muted">Bobot Guru pamong</label>
                                    <input type="number" step="0.01" min="0" max="1" name="guru_weight" id="weightGuru" value="{{ old('guru_weight', number_format((float) $weights[2]->guru_weight, 2, '.', '')) }}" class="form-control form-control-sm" style="width: 6rem;"/>
                                    @error('guru_weight')<span class="text-danger small d-block">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Simpan bobot</button>
                                </div>
                            </form>
                            @if (session('status'))
                                <p class="text-success small mt-2 mb-0">{{ session('status') }}</p>
                            @endif

                            @php
                                $savedWeights = collect($weights)->filter(fn($w) => $w->exists);
                            @endphp
                            @if ($savedWeights->isNotEmpty())
                            <div class="table-responsive mt-3">
                                <table class="table table-sm table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Sesi</th>
                                            <th class="text-center">Bobot Dosen</th>
                                            <th class="text-center">Bobot Guru pamong</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ([0, 1, 2] as $order)
                                            @php $w = $weights[$order]; @endphp
                                            @if ($w->exists)
                                            <tr>
                                                <td>{{ $plpLabel($order) }}</td>
                                                <td class="text-center">{{ number_format((float) $w->dosen_weight, 2) }}</td>
                                                <td class="text-center">{{ number_format((float) $w->guru_weight, 2) }}</td>
                                                <td class="text-end text-nowrap">
                                                    @can('konfigurasi/plpfinalgraderules-update')
                                                    <button type="button" class="btn btn-outline-secondary btn-sm plp-weight-edit"
                                                        data-plp-order="{{ $order }}"
                                                        data-dosen="{{ number_format((float) $w->dosen_weight, 2, '.', '') }}"
                                                        data-guru="{{ number_format((float) $w->guru_weight, 2, '.', '') }}"
                                                        title="Ubah bobot {{ $plpLabel($order) }}">
                                                        <i class="ti-pencil"></i>
                                                    </button>
                                                    @endcan
                                                    @can('konfigurasi/plpfinalgraderules-delete')
                                                    <button type="button" class="btn btn-outline-danger btn-sm plp-weight-delete"
                                                        data-delete-url="{{ route('plpfinalgraderules.weights.destroy', $order) }}"
                                                        data-plp-label="{{ $plpLabel($order) }}"
                                                        title="Hapus bobot {{ $plpLabel($order) }}">
                                                        <i class="ti-trash"></i>
                                                    </button>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>

                    <h5 class="text-secondary mt-2 mb-2">Mahasiswa</h5>
                        @can('konfigurasi/plpfinalgraderules-create')
                            <button type="button"
                                    class="btn btn-primary btn-sm btn-add-plp"
                                    data-create-url="{{ route('plpfinalgraderules.create', ['context' => 'mahasiswa']) }}">
                                + Form
                            </button>
                        @endcan
                    <p class="small text-muted mb-2">Form observasi mahasiswa (kode <code>*L1</code>, <code>*L2</code>, <code>*L3</code>). Kolom <strong>Jumlah pengisian</strong> = berapa kali form harus diisi (minimal 1×).</p>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-striped align-middle">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">PLP</th>
                                    <th>ID Form</th>
                                    <th>Nama Form</th>
                                    <th class="text-center text-nowrap">Jumlah pengisian</th>
                                    <th class="text-end text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rulesMahasiswa as $rule)
                                    <tr>
                                        <td>{{ $plpLabel((int) $rule->plp_order) }}</td>
                                        <td><code>{{ $rule->form_id }}</code></td>
                                        <td>{{ $rule->form->name ?? '—' }}</td>
                                        @include('konfigurasi.partials.plp-rule-times-cell', ['rule' => $rule])
                                        <td class="text-end">
                                            @can('konfigurasi/plpfinalgraderules-delete')
                                                <button type="button" class="btn btn-outline-danger btn-sm plp-fgr-rule-delete" data-rule-url="{{ route('plpfinalgraderules.destroy', $rule) }}" title="Hapus dari sebaran">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted small">Belum ada form untuk kategori ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="text-secondary mt-2 mb-2">Dosen</h5>
                        @can('konfigurasi/plpfinalgraderules-create')
                            <button type="button"
                                    class="btn btn-primary btn-sm btn-add-plp"
                                    data-create-url="{{ route('plpfinalgraderules.create', ['context' => 'dosen']) }}">
                                + Form
                            </button>
                        @endcan
                    <p class="small text-muted mb-2">Form penilaian dosen (DPL) untuk PLP, PLP&nbsp;1, dan PLP&nbsp;2. Atur jumlah pengisian per form (mis. N3 = beberapa perangkat).</p>
                    <div class="table-responsive mb-4">
                        <table class="table table-sm table-striped align-middle">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">PLP</th>
                                    <th>ID Form</th>
                                    <th>Nama Form</th>
                                    <th class="text-center text-nowrap">Jumlah pengisian</th>
                                    <th class="text-end text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rulesDosen as $rule)
                                    <tr>
                                        <td>{{ $plpLabel((int) $rule->plp_order) }}</td>
                                        <td><code>{{ $rule->form_id }}</code></td>
                                        <td>{{ $rule->form->name ?? '—' }}</td>
                                        @include('konfigurasi.partials.plp-rule-times-cell', ['rule' => $rule])
                                        <td class="text-end">
                                            @can('konfigurasi/plpfinalgraderules-delete')
                                                <button type="button" class="btn btn-outline-danger btn-sm plp-fgr-rule-delete" data-rule-url="{{ route('plpfinalgraderules.destroy', $rule) }}" title="Hapus dari sebaran">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted small">Belum ada form untuk kategori ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h5 class="text-secondary mt-2 mb-2">Guru pamong</h5>
                        @can('konfigurasi/plpfinalgraderules-create')
                            <button type="button"
                                    class="btn btn-primary btn-sm btn-add-plp"
                                    data-create-url="{{ route('plpfinalgraderules.create', ['context' => 'guru']) }}">
                                + Form
                            </button>
                        @endcan
                    <p class="small text-muted mb-2">Form penilaian guru pamong untuk PLP, PLP&nbsp;1, dan PLP&nbsp;2. Atur jumlah pengisian per form sesuai kebutuhan kegiatan.</p>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-striped align-middle">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">PLP</th>
                                    <th>ID Form</th>
                                    <th>Nama Form</th>
                                    <th class="text-center text-nowrap">Jumlah pengisian</th>
                                    <th class="text-end text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rulesGuru as $rule)
                                    <tr>
                                        <td>{{ $plpLabel((int) $rule->plp_order) }}</td>
                                        <td><code>{{ $rule->form_id }}</code></td>
                                        <td>{{ $rule->form->name ?? '—' }}</td>
                                        @include('konfigurasi.partials.plp-rule-times-cell', ['rule' => $rule])
                                        <td class="text-end">
                                            @can('konfigurasi/plpfinalgraderules-delete')
                                                <button type="button" class="btn btn-outline-danger btn-sm plp-fgr-rule-delete" data-rule-url="{{ route('plpfinalgraderules.destroy', $rule) }}" title="Hapus dari sebaran">
                                                    <i class="ti-trash"></i>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted small">Belum ada form untuk kategori ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document"></div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        (function () {
            const createUrlBase = @json($createUrlBase ?? route('plpfinalgraderules.create'));
            const plpWeights = @json($weightsJs);
            const modalSelector = '#modalAction';
            const dialogSelector = '#modalAction .modal-dialog';

            const getCsrf = () =>
                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const toastError = (message) => {
                if (window.iziToast) {
                    window.iziToast.error({ title: 'Gagal', message, position: 'topRight' });
                    return;
                }
                console.error(message);
            };

            const toastSuccess = (message) => {
                if (window.iziToast) {
                    window.iziToast.success({ title: 'Berhasil', message, position: 'topRight' });
                    return;
                }
                console.log(message);
            };

            const toastWarning = (message) => {
                if (window.iziToast) {
                    window.iziToast.warning({ title: 'Dihapus', message, position: 'topRight' });
                    return;
                }
                console.warn(message);
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

                try {
                    if (window.bootstrap?.Modal) {
                        window.bootstrap.Modal.getOrCreateInstance(modal).show();
                        return;
                    }
                } catch (e) {}

                modal.classList.add('show');
                modal.style.display = 'block';
                modal.style.position = 'fixed';
                modal.style.inset = '0';
                modal.style.zIndex = '20000';
                modal.style.overflowX = 'hidden';
                modal.style.overflowY = 'auto';
                modal.setAttribute('aria-modal', 'true');
                modal.removeAttribute('aria-hidden');

                const dialog = document.querySelector(dialogSelector);
                if (dialog) {
                    dialog.style.pointerEvents = 'auto';
                    dialog.style.margin = '1.75rem auto';
                    dialog.style.zIndex = '20001';
                }

                if (!document.querySelector('.modal-backdrop')) {
                    const backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                }

                document.body.classList.add('modal-open');
            };

            const closeModal = () => {
                const modal = document.querySelector(modalSelector);
                if (!modal) {
                    return;
                }

                try {
                    if (window.bootstrap?.Modal) {
                        const instance = window.bootstrap.Modal.getInstance(modal);
                        if (instance) {
                            instance.hide();
                            return;
                        }
                    }
                } catch (e) {}

                modal.classList.remove('show');
                modal.style.display = 'none';
                modal.removeAttribute('style');
                modal.setAttribute('aria-hidden', 'true');
                modal.removeAttribute('aria-modal');

                const dialog = document.querySelector(dialogSelector);
                if (dialog) {
                    dialog.removeAttribute('style');
                }

                document.body.classList.remove('modal-open');
                document.querySelectorAll('.modal-backdrop').forEach((el) => el.remove());
            };

            const loadModal = async (url, fallbackMessage) => {
                try {
                    const response = await fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'text/html',
                        },
                    });

                    if (!response.ok) {
                        throw new Error(`${fallbackMessage} (HTTP ${response.status})`);
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

            const reloadModalFromFilters = async () => {
                const dialog = document.querySelector(dialogSelector);
                if (!dialog) {
                    return;
                }

                const form = dialog.querySelector('#formAction');
                const ctx = form?.getAttribute('data-rule-context');
                if (!ctx) {
                    return;
                }

                const plpSelect = dialog.querySelector('select[name="plp_order"]');
                if (!plpSelect) {
                    return;
                }

                if (form?.getAttribute('data-readonly') === '1') {
                    return;
                }

                const url = `${createUrlBase}?context=${encodeURIComponent(ctx)}&plp_order=${encodeURIComponent(plpSelect.value || '1')}`;
                await loadModal(url, 'Form tidak dapat dimuat.');
            };

            const submitForm = async (form) => {
                form.querySelectorAll('.text-danger.text-small').forEach((el) => el.remove());

                const formData = new FormData(form);
                const action = form.getAttribute('action');

                try {
                    const response = await fetch(action, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json',
                        },
                        body: formData,
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        if (data.errors) {
                            Object.keys(data.errors).forEach((key) => {
                                const input = form.querySelector(`[name="${key}"], [name="${key}[]"]`);
                                if (!input?.parentElement) {
                                    return;
                                }
                                const span = document.createElement('span');
                                span.className = 'text-danger text-small d-block';
                                span.textContent = Array.isArray(data.errors[key])
                                    ? data.errors[key][0]
                                    : data.errors[key];
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

            document.addEventListener('click', function (event) {
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

                const addButton = event.target.closest('.btn-add-plp');
                if (addButton) {
                    event.preventDefault();
                    event.stopPropagation();
                    const url = addButton.getAttribute('data-create-url');
                    if (url) {
                        loadModal(url, 'Modal tidak dapat dimuat.');
                    }
                    return;
                }

                const timesBtn = event.target.closest('.plp-times-inc, .plp-times-dec');
                if (timesBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    const control = timesBtn.closest('.plp-times-control');
                    const url = control?.getAttribute('data-update-url');
                    if (!url || timesBtn.disabled) {
                        return;
                    }
                    const action = timesBtn.classList.contains('plp-times-inc') ? 'increment' : 'decrement';
                    fetch(url, {
                        method: 'PATCH',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                            Accept: 'application/json',
                        },
                        body: JSON.stringify({ action }),
                    })
                        .then((r) => r.json().catch(() => ({})).then((data) => ({ r, data })))
                        .then(({ r, data }) => {
                            if (!r.ok) {
                                throw new Error((data.message || 'Gagal memperbarui jumlah pengisian.') + ` (HTTP ${r.status})`);
                            }
                            const valueEl = control.querySelector('.plp-times-value');
                            const decBtn = control.querySelector('.plp-times-dec');
                            const incBtn = control.querySelector('.plp-times-inc');
                            if (valueEl) {
                                valueEl.textContent = `${data.times}×`;
                            }
                            if (decBtn) {
                                decBtn.disabled = !data.can_decrement;
                            }
                            if (incBtn) {
                                incBtn.disabled = !data.can_increment;
                            }
                            toastSuccess(data.message || 'Jumlah pengisian diperbarui.');
                        })
                        .catch((err) => toastError(err.message || 'Gagal memperbarui jumlah pengisian.'));
                    return;
                }

                const modalTimesBtn = event.target.closest('.modal-times-inc, .modal-times-dec');
                if (modalTimesBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    const stepper = modalTimesBtn.closest('.modal-times-stepper');
                    const input = stepper?.querySelector('.modal-times-input');
                    if (!input) {
                        return;
                    }
                    let val = parseInt(input.value, 10) || 1;
                    if (modalTimesBtn.classList.contains('modal-times-inc')) {
                        val = Math.min(20, val + 1);
                    } else {
                        val = Math.max(1, val - 1);
                    }
                    input.value = String(val);
                    return;
                }

                const delBtn = event.target.closest('.plp-fgr-rule-delete');
                if (delBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    const delUrl = delBtn.getAttribute('data-rule-url');
                    if (!delUrl) {
                        return;
                    }
                    if (!window.confirm('Hapus form ini dari sebaran?')) {
                        return;
                    }
                    fetch(delUrl, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json',
                        },
                    })
                        .then((response) => response.json().catch(() => ({})).then((data) => ({ response, data })))
                        .then(({ response, data }) => {
                            if (!response.ok) {
                                throw new Error((data.message || 'Data gagal dihapus.') + ` (HTTP ${response.status})`);
                            }
                            toastWarning(data.message || 'Form dihapus dari sebaran.');
                            window.location.reload();
                        })
                        .catch((error) => toastError(error.message || 'Data gagal dihapus.'));
                    return;
                }

                const weightEditBtn = event.target.closest('.plp-weight-edit');
                if (weightEditBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    const plpSel = document.getElementById('weightPlpOrder');
                    const dosenIn = document.getElementById('weightDosen');
                    const guruIn = document.getElementById('weightGuru');
                    if (plpSel) {
                        plpSel.value = weightEditBtn.dataset.plpOrder ?? plpSel.value;
                    }
                    if (dosenIn) {
                        dosenIn.value = weightEditBtn.dataset.dosen ?? dosenIn.value;
                    }
                    if (guruIn) {
                        guruIn.value = weightEditBtn.dataset.guru ?? guruIn.value;
                    }
                    plpSel?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    dosenIn?.focus();
                    if (window.iziToast) {
                        window.iziToast.info({
                            title: 'Ubah bobot',
                            message: 'Nilai dimuat ke formulir di atas. Klik Simpan bobot untuk menyimpan.',
                            position: 'topRight',
                        });
                    }
                    return;
                }

                const weightDelBtn = event.target.closest('.plp-weight-delete');
                if (weightDelBtn) {
                    event.preventDefault();
                    event.stopPropagation();
                    const label = weightDelBtn.dataset.plpLabel || 'PLP';
                    if (!window.confirm('Hapus bobot gabungan ' + label + '?')) {
                        return;
                    }
                    fetch(weightDelBtn.dataset.deleteUrl, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': getCsrf(),
                            'X-Requested-With': 'XMLHttpRequest',
                            Accept: 'application/json',
                        },
                    })
                        .then((r) => r.json().catch(() => ({})).then((data) => ({ r, data })))
                        .then(({ r, data }) => {
                            if (!r.ok) {
                                throw new Error((data.message || 'Gagal menghapus.') + ` (HTTP ${r.status})`);
                            }
                            toastWarning(data.message || 'Bobot dihapus.');
                            window.location.reload();
                        })
                        .catch((err) => toastError(err.message || 'Gagal menghapus.'));
                }
            }, true);

            document.addEventListener('submit', function (event) {
                const form = event.target.closest('#formAction');
                if (!form) {
                    return;
                }
                event.preventDefault();
                submitForm(form);
            });

            document.addEventListener('change', (e) => {
                const dialog = e.target.closest(`${dialogSelector}`);
                if (!dialog) {
                    return;
                }
                if (e.target.name !== 'plp_order' || e.target.tagName !== 'SELECT') {
                    return;
                }
                reloadModalFromFilters();
            });

            const weightSelect = document.getElementById('weightPlpOrder');
            const dosenInput = document.getElementById('weightDosen');
            const guruInput = document.getElementById('weightGuru');
            if (weightSelect && dosenInput && guruInput) {
                weightSelect.addEventListener('change', function () {
                    const w = plpWeights[this.value];
                    if (w) {
                        dosenInput.value = w.dosen_weight;
                        guruInput.value = w.guru_weight;
                    }
                });
            }

            ensureModalInBody();
        })();
    </script>
@endpush
