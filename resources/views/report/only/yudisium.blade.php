@extends('layouts.master')

@include('report.partials.yudisium-datatable-assets')

@section('content')
<div class="main-content">
    <div class="title d-flex flex-wrap align-items-center justify-content-between gap-2">
        <span>Yudisium PLP {{ $activeYear }}</span>
        @role('kajur')
            @if(count($plpTabs) > 0)
                <button type="button"
                        id="btn-yudisium-recalculate-grades"
                        class="btn btn-sm btn-outline-primary"
                        data-url="{{ route('yudisium.only.recalculate-grades', array_filter(['tab' => $activeTab])) }}"
                        title="Hitung ulang nilai akhir semua mahasiswa jurusan pada tahun {{ $activeYear }}">
                    Hitung ulang nilai
                </button>
            @endif
        @endrole
    </div>

    @if(count($plpTabs) > 0)
        @if(count($plpTabs) > 1)
            <ul class="nav nav-tabs yudisium-tabs mb-3" id="yudisiumTabs" role="tablist">
                @foreach($plpTabs as $tab)
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link {{ $activeTab === $tab['key'] ? 'active' : '' }}"
                            id="tab-{{ $tab['key'] }}"
                            data-bs-toggle="tab"
                            data-bs-target="#content-{{ $tab['key'] }}"
                            type="button"
                            role="tab"
                            aria-controls="content-{{ $tab['key'] }}"
                            aria-selected="{{ $activeTab === $tab['key'] ? 'true' : 'false' }}"
                        >
                            {{ $tab['label'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="tab-content" id="yudisiumTabsContent">
            @foreach($plpTabs as $tab)
                <div
                    class="tab-pane fade {{ ($activeTab === $tab['key'] || count($plpTabs) === 1) ? 'show active' : '' }}"
                    id="content-{{ $tab['key'] }}"
                    role="tabpanel"
                    aria-labelledby="tab-{{ $tab['key'] }}"
                    data-yudisium-tab-key="{{ $tab['key'] }}"
                    data-yudisium-tab-loaded="{{ !empty($tab['loaded']) ? '1' : '0' }}"
                    @if(empty($tab['loaded']))
                        data-yudisium-tab-url="{{ url('yudisium/plp/tab') }}?tab={{ urlencode($tab['key']) }}"
                    @endif
                >
                    @if(!empty($tab['loaded']))
                        @include('report.partials.yudisium-tab-pane', ['tab' => $tab])
                    @else
                        <div class="yudisium-tab-placeholder text-muted py-5 text-center">
                            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Memuat data {{ $tab['label'] }}…
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info mt-3">
            Belum ada data yudisium PLP untuk tahun {{ $activeYear }}.
            Pastikan Sebaran Form sudah diatur dan ada mahasiswa yang ikut PLP pada tahun ini.
        </div>
    @endif
</div>
@endsection

@role('kajur')
@push('js')
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        (function () {
            const btn = document.getElementById('btn-yudisium-recalculate-grades');
            if (!btn) {
                return;
            }

            btn.addEventListener('click', function () {
                if (!window.confirm('Hitung ulang nilai akhir (PLP / PLP 1 / PLP 2) untuk semua mahasiswa jurusan Anda pada tahun {{ $activeYear }}?')) {
                    return;
                }

                const originalText = btn.textContent;
                btn.disabled = true;
                btn.textContent = 'Menghitung…';

                fetch(btn.getAttribute('data-url'), {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                })
                    .then((r) => r.json().catch(() => ({})).then((data) => ({ r, data })))
                    .then(({ r, data }) => {
                        if (!r.ok) {
                            throw new Error(data.message || 'Gagal menghitung ulang nilai.');
                        }
                        if (typeof iziToast !== 'undefined') {
                            iziToast.success({
                                title: 'Berhasil',
                                message: data.message || 'Nilai akhir dihitung ulang.',
                                position: 'topRight',
                            });
                        }
                        window.location.href = data.reload_url || '{{ url('yudisium/plp') }}';
                    })
                    .catch((err) => {
                        if (typeof iziToast !== 'undefined') {
                            iziToast.error({
                                title: 'Gagal',
                                message: err.message || 'Gagal menghitung ulang nilai.',
                                position: 'topRight',
                            });
                        } else {
                            window.alert(err.message || 'Gagal menghitung ulang nilai.');
                        }
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.textContent = originalText;
                    });
            });
        })();
    </script>
@endpush
@endrole
