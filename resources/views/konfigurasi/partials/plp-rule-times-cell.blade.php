@php
    $times = max(1, (int) ($rule->times ?? 1));
@endphp
<td class="text-center text-nowrap">
    @can('konfigurasi/plpfinalgraderules-update')
        <div class="d-inline-flex align-items-center gap-1 plp-times-control"
             data-update-url="{{ route('plpfinalgraderules.times.update', $rule) }}">
            <button type="button"
                    class="btn btn-outline-secondary btn-sm py-0 px-2 plp-times-dec"
                    @disabled($times <= 1)
                    title="Kurangi jumlah pengisian"
                    aria-label="Kurangi">−</button>
            <span class="badge bg-light text-dark border px-2 plp-times-value" title="Berapa kali form diisi">{{ $times }}×</span>
            <button type="button"
                    class="btn btn-outline-secondary btn-sm py-0 px-2 plp-times-inc"
                    @disabled($times >= 20)
                    title="Tambah jumlah pengisian"
                    aria-label="Tambah">+</button>
        </div>
    @else
        <span class="badge bg-light text-dark border" title="Berapa kali form diisi">{{ $times }}×</span>
    @endcan
</td>
