@php
    $isReadOnly = !empty($readOnly);
    $ruleContext = $ruleContext ?? 'dosen';
    $isMahasiswa = $ruleContext === 'mahasiswa';
    $isDosenCtx = $ruleContext === 'dosen';
    $contextTitle = match ($ruleContext) {
        'mahasiswa' => 'Peran Mahasiswa',
        'dosen' => 'Dosen',
        'guru' => 'Guru pamong',
        default => 'Aturan PLP',
    };
    $formLabel = $isMahasiswa ? 'Form observasi' : 'Form penilaian';
    $modalAction = $isReadOnly ? $formLabel : 'Atur '.$formLabel;
@endphp
<div class="modal-content">
    <form
        id="formAction"
        action="{{ route('plpfinalgraderules.store') }}"
        method="post"
        data-rule-context="{{ $ruleContext }}"
        @if ($isReadOnly) data-readonly="1" @endif
    >
        @csrf
        <input type="hidden" name="context" value="{{ $ruleContext }}">
        <div class="modal-header">
            <h5 class="modal-title">
                {{ $modalAction }}
                — <span class="text-secondary">{{ $contextTitle }}</span>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <fieldset class="border-0 p-0 m-0" @disabled($isReadOnly)>
                @if ($isMahasiswa)
                    <input type="hidden" name="assessor" value="mahasiswa">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label for="plp_order_input" class="form-label mb-0 small text-nowrap">PLP</label>
                        <select id="plp_order_input" class="form-select form-select-sm" name="plp_order" style="width: auto;">
                            <option value="0" @selected((int) $plpOrder === 0)>PLP</option>
                            <option value="1" @selected((int) $plpOrder === 1)>PLP 1</option>
                            <option value="2" @selected((int) $plpOrder === 2)>PLP 2</option>
                        </select>
                        <span class="small text-muted">Form observasi (<code>*L1</code>, <code>*L2</code>, <code>*L3</code>) — tahun {{ $activeYear }}</span>
                    </div>
                @elseif ($isDosenCtx)
                    <input type="hidden" name="assessor" value="dosen">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label for="plp_order_input" class="form-label mb-0 small text-nowrap">PLP</label>
                        <select id="plp_order_input" class="form-select form-select-sm" name="plp_order" style="width: auto;">
                            <option value="0" @selected((int) $plpOrder === 0)>PLP</option>
                            <option value="1" @selected((int) $plpOrder === 1)>PLP 1</option>
                            <option value="2" @selected((int) $plpOrder === 2)>PLP 2</option>
                        </select>
                        <span class="small text-muted">Tahun {{ $activeYear }}</span>
                    </div>
                @else
                    <input type="hidden" name="assessor" value="guru">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <label for="plp_order_input" class="form-label mb-0 small text-nowrap">PLP</label>
                        <select id="plp_order_input" class="form-select form-select-sm" name="plp_order" style="width: auto;">
                            <option value="0" @selected((int) $plpOrder === 0)>PLP</option>
                            <option value="1" @selected((int) $plpOrder === 1)>PLP 1</option>
                            <option value="2" @selected((int) $plpOrder === 2)>PLP 2</option>
                        </select>
                        <span class="small text-muted">Tahun {{ $activeYear }}</span>
                    </div>
                @endif

                @unless ($isReadOnly)
                    <p class="small text-muted mb-2">Simpan akan <strong>mengganti seluruh</strong> daftar untuk kombinasi ini. Kosongkan semua pilihan untuk menghapus aturan blok.</p>
                @endunless

                <div class="d-flex align-items-center gap-2 mb-1">
                    <label class="form-label small mb-0 flex-grow-1">{{ $formLabel }}</label>
                    <span class="small text-muted text-center" style="width:7.5rem;">Jumlah pengisian</span>
                </div>
                <div class="border rounded p-2" style="max-height: 280px; overflow-y: auto;">
                    @forelse ($formOptions as $opt)
                        @php $isChecked = in_array($opt['id'], $selectedFormIds, true); @endphp
                        <div class="d-flex align-items-center gap-2 mb-1 py-1">
                            <input
                                type="checkbox"
                                class="form-check-input flex-shrink-0 mt-0"
                                name="form_ids[]"
                                id="fid_{{ $opt['id'] }}"
                                value="{{ $opt['id'] }}"
                                @checked($isChecked)
                            >
                            <label class="form-check-label flex-grow-1 mb-0" for="fid_{{ $opt['id'] }}">
                                <code class="small text-secondary">{{ $opt['id'] }}</code> {{ $opt['name'] }}
                            </label>
                            <div class="input-group input-group-sm flex-shrink-0 modal-times-stepper" style="width:7.5rem;">
                                <button type="button" class="btn btn-outline-secondary modal-times-dec" aria-label="Kurangi">−</button>
                                <input
                                    type="number"
                                    name="form_times[{{ $opt['id'] }}]"
                                    value="{{ max(1, (int) ($selectedFormTimes[$opt['id']] ?? 1)) }}"
                                    min="1"
                                    max="20"
                                    class="form-control text-center modal-times-input"
                                    title="Berapa kali form ini harus diisi (minimal 1)"
                                >
                                <button type="button" class="btn btn-outline-secondary modal-times-inc" aria-label="Tambah">+</button>
                            </div>
                        </div>
                    @empty
                        <p class="small text-muted mb-0">Tidak ada form tersedia.</p>
                    @endforelse
                </div>
                @foreach ($errors->get('form_ids') ?? [] as $msg)
                    <span class="text-danger small d-block mt-1">{{ $msg }}</span>
                @endforeach
                @foreach ($errors->get('form_ids.*') ?? [] as $msg)
                    <span class="text-danger small d-block mt-1">{{ $msg }}</span>
                @endforeach
            </fieldset>
        </div>
        <div class="modal-footer">
            @if ($isReadOnly)
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            @else
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            @endif
        </div>
    </form>
</div>
