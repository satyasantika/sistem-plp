<style>
    /* ===== MODAL WRAPPER ===== */
    .pma-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,.18);
    }

    /* ===== HEADER ===== */
    .pma-header {
        background: linear-gradient(135deg, #4f8ef7 0%, #2c5fe0 100%);
        padding: 18px 22px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: none;
    }
    .pma-header-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,.18);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: #fff;
        flex-shrink: 0;
    }
    .pma-header-title {
        flex: 1;
        color: #fff;
        font-size: .95rem;
        font-weight: 700;
        margin: 0;
    }
    .pma-header-close {
        background: rgba(255,255,255,.18);
        border: none;
        border-radius: 8px;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: .85rem;
        cursor: pointer;
        transition: background .15s;
        flex-shrink: 0;
    }
    .pma-header-close:hover { background: rgba(255,255,255,.32); }

    /* ===== SECTION BADGES ===== */
    .pma-section {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 4px 0 12px;
    }
    .pma-section-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px 3px 7px;
        border-radius: 20px;
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .pma-section-badge.blue  { background: #eff6ff; color: #2563eb; }
    .pma-section-badge.green { background: #f0fdf4; color: #16a34a; }
    .pma-section-divider {
        flex: 1;
        height: 1px;
        background: #e9ecef;
    }

    /* ===== FIELD LABEL with icon ===== */
    .pma-label {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: .75rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 4px;
    }
    .pma-label i {
        font-size: .7rem;
        opacity: .7;
    }

    /* ===== INPUT ===== */
    .pma-content .form-control,
    .pma-content select.form-control {
        border-radius: 8px;
        border: 1.5px solid #e2e8f0;
        font-size: .85rem;
        padding: 7px 11px;
        transition: border-color .15s, box-shadow .15s;
        background: #fff;
        color: #1a202c;
    }
    .pma-content .form-control:focus,
    .pma-content select.form-control:focus {
        border-color: #4f8ef7;
        box-shadow: 0 0 0 3px rgba(79,142,247,.15);
        outline: none;
    }

    /* ===== GENDER PILLS ===== */
    .pma-gender-group {
        display: flex;
        gap: 8px;
    }
    .pma-gender-label {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 20px;
        font-size: .82rem;
        cursor: pointer;
        transition: border-color .15s, background .15s;
        user-select: none;
    }
    .pma-gender-label input { display: none; }
    .pma-gender-label:has(input:checked) {
        border-color: #4f8ef7;
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
    }
    .pma-gender-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #cbd5e1;
        transition: background .15s;
    }
    .pma-gender-label:has(input:checked) .pma-gender-dot { background: #4f8ef7; }

    /* ===== BODY & FOOTER ===== */
    .pma-body { padding: 18px 22px 6px; }
    .pma-footer {
        padding: 14px 22px;
        border-top: 1px solid #f0f4fa;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }
    .pma-btn-cancel {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 7px 16px; font-size: .82rem; font-weight: 600;
        border-radius: 8px; border: 1.5px solid #e2e8f0;
        background: transparent; color: #64748b; cursor: pointer;
        transition: background .15s, border-color .15s;
    }
    .pma-btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }
    .pma-btn-save {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 20px; font-size: .82rem; font-weight: 700;
        border-radius: 8px; border: none;
        background: linear-gradient(135deg,#4f8ef7,#2c5fe0);
        color: #fff; cursor: pointer;
        transition: opacity .15s, transform .12s;
    }
    .pma-btn-save:hover { opacity: .9; transform: translateY(-1px); }

    /* ===== DARK MODE ===== */
    body.dark .pma-content {
        background: #1a2133;
        box-shadow: 0 20px 60px rgba(0,0,0,.5);
    }
    body.dark .pma-section-badge.blue  { background: #1e3a5f; color: #93c5fd; }
    body.dark .pma-section-badge.green { background: #14532d; color: #86efac; }
    body.dark .pma-section-divider { background: #2d3748; }
    body.dark .pma-label { color: #94a3b8; }
    body.dark .pma-content .form-control,
    body.dark .pma-content select.form-control {
        background: #243044; color: #e2e8f0;
        border-color: #374151;
    }
    body.dark .pma-content .form-control:focus,
    body.dark .pma-content select.form-control:focus {
        border-color: #4f8ef7;
        box-shadow: 0 0 0 3px rgba(79,142,247,.2);
    }
    body.dark .pma-gender-label {
        border-color: #374151; color: #cbd5e1;
    }
    body.dark .pma-gender-label:has(input:checked) {
        border-color: #4f8ef7; background: #1e3a5f; color: #93c5fd;
    }
    body.dark .pma-footer { border-color: #2d3748; }
    body.dark .pma-btn-cancel {
        border-color: #374151; color: #94a3b8;
    }
    body.dark .pma-btn-cancel:hover { background: #243044; }
</style>

<div class="modal-content pma-content">
    <form id="formAction" action="{{ route('profiles.update',$user->id) }}" method="post">
        @csrf
        @if ($user->id)
            @method('PUT')
        @endif

        {{-- HEADER --}}
        <div class="modal-header pma-header">
            <div class="pma-header-icon"><i class="ti-pencil"></i></div>
            <h5 class="pma-header-title" id="largeModalLabel">Edit Profil</h5>
            <button type="button" class="pma-header-close" data-bs-dismiss="modal" aria-label="Close">
                <i class="ti-close"></i>
            </button>
        </div>

        {{-- BODY --}}
        <div class="modal-body pma-body">
            <div class="row g-3">

                {{-- SECTION: Informasi Dasar --}}
                <div class="col-12">
                    <div class="pma-section">
                        <span class="pma-section-badge blue"><i class="ti-id-badge"></i> Informasi Dasar</span>
                        <span class="pma-section-divider"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-user"></i> Nama Lengkap <span class="text-danger ms-1">*</span></label>
                    <input type="text" value="{{ $user->name }}" name="name" class="form-control" id="name" required autofocus>
                </div>

                @hasanyrole('mahasiswa')
                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-map-alt"></i> Tempat Lahir</label>
                    <input type="text" value="{{ $user->birth_place }}" name="birth_place" class="form-control" id="birth_place">
                </div>
                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-calendar"></i> Tanggal Lahir</label>
                    <input type="date" value="{{ $user->birth_date ? $user->birth_date->format('Y-m-d') : date('Y-m-d') }}" name="birth_date" class="form-control" id="birth_date">
                </div>
                @endhasanyrole

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-face-smile"></i> Gender</label>
                    <div class="pma-gender-group">
                        <label class="pma-gender-label">
                            <input type="radio" name="gender" value="L" {{ $user->gender == 'L' ? 'checked' : '' }}>
                            <span class="pma-gender-dot"></span> Laki-laki
                        </label>
                        <label class="pma-gender-label">
                            <input type="radio" name="gender" value="P" {{ $user->gender == 'P' ? 'checked' : '' }}>
                            <span class="pma-gender-dot"></span> Perempuan
                        </label>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-home"></i> Alamat</label>
                    <textarea name="address" class="form-control" id="address" rows="2">{{ $user->address }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-mobile"></i> No. WA Aktif</label>
                    <input type="text" value="{{ $user->phone }}" name="phone" class="form-control" id="phone" placeholder="Contoh: 8512XXXXX">
                </div>

                {{-- SECTION: Kepegawaian & Bank (guru/kepsek/korguru) --}}
                @hasanyrole('guru|kepsek|korguru')
                <div class="col-12 mt-2">
                    <div class="pma-section">
                        <span class="pma-section-badge green"><i class="ti-briefcase"></i> Kepegawaian &amp; Bank</span>
                        <span class="pma-section-divider"></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-signal"></i> Provider</label>
                    <select id="provider" class="form-control" name="provider">
                        <option value="">-- Pilih Provider --</option>
                        @foreach ($providers as $provider)
                            <option value="{{ $provider }}" @selected($user->provider == $provider)>{{ $provider }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-id-badge"></i> Status PNS</label>
                    <select id="is_pns" class="form-control" name="is_pns">
                        @foreach ($is_pns as $key => $value)
                            <option value="{{ $key }}" @selected($user->is_pns == $key)>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-layers"></i> Golongan Kepegawaian</label>
                    <select id="golongan" class="form-control" name="golongan">
                        <option value="">-- Pilih Golongan --</option>
                        @foreach ($golongans as $golongan)
                            <option value="{{ $golongan }}" @selected($user->golongan == $golongan)>Golongan {{ $golongan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-receipt"></i> NPWP</label>
                    <input type="text" value="{{ $user->npwp }}" name="npwp" class="form-control" id="npwp">
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-credit-card"></i> Nama Bank</label>
                    <select id="bank" class="form-control" name="bank">
                        <option value="">-- Pilih Bank --</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank }}" @selected($user->bank == $bank)>{{ $bank }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="pma-label"><i class="ti-layout-list-thumb"></i> Nomor Rekening</label>
                    <input type="text" value="{{ $user->nomor_rekening }}" name="nomor_rekening" class="form-control" id="nomor_rekening">
                </div>
                @endhasanyrole

            </div>
        </div>

        {{-- FOOTER --}}
        <div class="modal-footer pma-footer">
            <button type="button" class="pma-btn-cancel" data-bs-dismiss="modal">
                <i class="ti-close"></i> Batal
            </button>
            <button type="submit" class="pma-btn-save">
                <i class="ti-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
