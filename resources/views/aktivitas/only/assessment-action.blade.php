<style>
    .assessment-modal .modal-content {
        border: 1px solid rgba(85, 116, 159, 0.24);
        border-radius: 16px;
        overflow: hidden;
        background: linear-gradient(165deg, #ffffff 0%, #f6f9ff 100%);
    }

    .assessment-modal .modal-header,
    .assessment-modal .modal-body,
    .assessment-modal .modal-footer {
        margin: 0 !important;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .assessment-modal .modal-header {
        border-bottom: 1px solid rgba(85, 116, 159, 0.2);
        background: linear-gradient(140deg, rgba(237, 244, 255, 0.95), rgba(247, 251, 255, 0.95));
    }

    .assessment-modal .modal-title {
        font-size: 0.98rem;
        font-weight: 800;
        letter-spacing: 0.2px;
        color: #233754;
    }

    .assessment-modal .identity-box {
        border: 1px solid rgba(82, 112, 154, 0.22);
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(246, 250, 255, 0.96));
        padding: 12px;
        margin-bottom: 12px;
    }

    .assessment-modal .identity-title {
        margin: 0;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.42px;
        color: #57719a;
        font-weight: 700;
    }

    .assessment-modal .identity-name {
        margin: 3px 0 1px;
        font-size: 1rem;
        font-weight: 800;
        color: #213551;
    }

    .assessment-modal .identity-meta {
        margin: 0;
        color: #6b7f9f;
        font-size: 0.82rem;
    }

    .assessment-modal .identity-badges {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .assessment-modal .badge-modern {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.32rem 0.6rem;
        font-size: 0.71rem;
        font-weight: 700;
        letter-spacing: 0.26px;
        border: 1px solid transparent;
    }

    .assessment-modal .badge-modern-info {
        background: rgba(23, 162, 184, 0.16);
        color: #0d7283;
        border-color: rgba(23, 162, 184, 0.28);
    }

    .assessment-modal .badge-modern-neutral {
        background: rgba(108, 117, 125, 0.14);
        color: #4e5965;
        border-color: rgba(108, 117, 125, 0.24);
    }

    .assessment-modal .guide-box {
        border: 1px solid rgba(74, 134, 215, 0.22);
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(236, 245, 255, 0.8), rgba(247, 251, 255, 0.8));
        padding: 12px;
        margin-bottom: 12px;
    }

    .assessment-modal .guide-title {
        margin: 0 0 8px;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.42px;
        color: #57719a;
        font-weight: 700;
    }

    .assessment-modal .item-box {
        border: 1px solid rgba(86, 116, 158, 0.18);
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 10px;
        background: rgba(255, 255, 255, 0.82);
    }

    .assessment-modal .item-label {
        color: #294166;
        font-weight: 600;
        line-height: 1.45;
    }

    .assessment-modal .btn-option {
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.24px;
        padding: 0.32rem 0.66rem;
        border-width: 1px;
    }

    .assessment-modal .btn-check:checked + .btn-option {
        color: #fff;
        border-color: #1f74df;
        background: linear-gradient(135deg, #2476f3, #1759c5);
        box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
    }

    .assessment-modal .score-output {
        border-radius: 999px;
        border: 1px solid rgba(85, 116, 159, 0.24);
        background: rgba(255, 255, 255, 0.8);
        font-weight: 700;
        min-width: 60px;
    }

    .assessment-modal .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.2px;
        color: #3d567b;
    }

    .assessment-modal .form-control {
        border-radius: 11px;
        border-color: rgba(84, 116, 158, 0.28);
    }

    .assessment-modal .modal-footer {
        border-top: 1px solid rgba(85, 116, 159, 0.2);
        background: rgba(248, 251, 255, 0.9);
    }

    .assessment-modal .btn-modern {
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.28px;
        padding: 0.42rem 0.84rem;
        transition: transform 0.18s ease, filter 0.2s ease, box-shadow 0.22s ease;
    }

    .assessment-modal .btn-modern:hover,
    .assessment-modal .btn-modern:focus {
        transform: translateY(-1px);
        filter: saturate(1.08);
    }

    .assessment-modal .btn-modern-secondary {
        border-color: rgba(74, 105, 148, 0.34);
        color: #264063;
        background: rgba(255, 255, 255, 0.62);
    }

    .assessment-modal .btn-modern-primary {
        color: #fff;
        border-color: transparent;
        background: linear-gradient(135deg, #2476f3, #1759c5);
        box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
    }

    body.dark .assessment-modal .modal-content {
        border-color: rgba(157, 185, 224, 0.26);
        background: linear-gradient(165deg, rgba(22, 36, 58, 0.96), rgba(15, 28, 46, 0.96));
    }

    body.dark .assessment-modal .modal-header {
        border-bottom-color: rgba(157, 185, 224, 0.24);
        background: linear-gradient(140deg, rgba(35, 53, 82, 0.95), rgba(26, 41, 66, 0.95));
    }

    body.dark .assessment-modal .modal-footer {
        border-top-color: rgba(157, 185, 224, 0.24);
        background: rgba(21, 35, 56, 0.9);
    }

    body.dark .assessment-modal .modal-title,
    body.dark .assessment-modal .item-label,
    body.dark .assessment-modal .form-label,
    body.dark .assessment-modal .identity-name {
        color: #dce8ff;
    }

    body.dark .assessment-modal .identity-title,
    body.dark .assessment-modal .identity-meta,
    body.dark .assessment-modal .guide-title {
        color: #a9bddf;
    }

    body.dark .assessment-modal .identity-box,
    body.dark .assessment-modal .guide-box,
    body.dark .assessment-modal .item-box {
        border-color: rgba(157, 185, 224, 0.24);
        background: rgba(25, 38, 58, 0.56);
    }

    body.dark .assessment-modal .badge-modern-info {
        background: rgba(76, 194, 211, 0.2);
        color: #bdebf2;
        border-color: rgba(76, 194, 211, 0.34);
    }

    body.dark .assessment-modal .badge-modern-neutral {
        background: rgba(130, 146, 166, 0.22);
        color: #d3deeb;
        border-color: rgba(130, 146, 166, 0.36);
    }

    body.dark .assessment-modal .btn-modern-secondary {
        border-color: rgba(146, 182, 230, 0.45);
        color: #cfe3ff;
        background: rgba(43, 66, 103, 0.36);
    }

    body.dark .assessment-modal .form-control,
    body.dark .assessment-modal .score-output {
        color: #e2ecff;
        background-color: rgba(21, 35, 56, 0.75);
        border-color: rgba(146, 182, 230, 0.38);
    }
</style>

<div class="modal-content assessment-modal">
    <form
        id="formAction"
        action="@if ($schoolassessment->id)
                    {{ route('schoolassessments.only.update',array_merge(['schoolassessment'=>$schoolassessment->id],$parameters)) }}
                @else
                    {{ route('schoolassessments.only.store',$parameters) }}
                @endif"
        method="post">
        @csrf
        @if ($schoolassessment->id) @method('PUT') @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $schoolassessment->id ? 'Edit' : 'Pengisian' }} {{ $form->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="identity-box">
                <p class="identity-title">Identitas Mahasiswa Dinilai</p>
                <p class="identity-name">{{ $map->students->name ?? '-' }}</p>
                <p class="identity-meta">{{ $map->students->username ?? '-' }}</p>
                <div class="identity-badges">
                    <span class="badge-modern badge-modern-neutral">Sekolah {{ $map->schools->name ?? '-' }}</span>
                    <span class="badge-modern badge-modern-neutral">Prodi {{ $map->subjects->name ?? '-' }}</span>
                </div>
            </div>

            <div class="row guide-box">
                <input type="hidden" value="{{ $parameters['form_id'] }}" name="form_id" class="form-control" id="form_id" >
                <input type="hidden" value="{{ $parameters['form_order'] }}" name="form_order" class="form-control" id="form_order" >
                <input type="hidden" value="{{ $parameters['map_id'] }}" name="map_id" class="form-control" id="map_id" >
                <input type="hidden" value="{{ $parameters['plp_order'] ?? 2 }}" name="plp_order" class="form-control" id="plp_order" >
                <input type="hidden" value="@hasrole('guru') guru @else dosen @endhasrole" name="assessor" class="form-control" id="assessor" >
                <p class="guide-title">Petunjuk</p>
                <div class="col-md-12">
                    <div class="mb-3">
                        @foreach ($form_guides as $guide)
                            <span class="badge-modern badge-modern-info">{{ $guide->component_order }}</span> {{ $guide->name }}<br>
                        @endforeach
                    </div>
                </div>
            </div>
            @foreach ($form_items as $item)
            <div class="row item-box">
                <div class="col-auto">
                    <span class="badge-modern badge-modern-neutral">nomor {{ $item->component_order }}</span>
                </div>
                @php
                    $item_order = 'score'.$item->component_order;
                    $options = [];
                @endphp
                <div class="col-10">
                    <div class="mb-1 item-label">
                        {{ $item->name }} <br>
                        @if ($form->type == 'skor_4')
                            @if (substr($form->id,-2) == 'N4')
                                @php $options = $keterpenuhan; @endphp
                            @else
                                @php $options = $kebaikan; @endphp
                            @endif
                            @foreach ($options as $key => $option)
                            <input
                                type="radio"
                                name="{{ $item_order }}"
                                value="{{ $key+1 ?? 0 }}"
                                class="btn-check"
                                id="{{ $item_order }}-{{ $option }}"
                                autocomplete="off"
                                {{ $schoolassessment->$item_order == $key+1 ? 'checked' : '' }}
                                >
                            <label
                                class="btn btn-outline-primary btn-sm mb-1 btn-option"
                                for="{{ $item_order }}-{{ $option }}"
                                >{{ $option }}</label>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="range"
                                        class="form-range"
                                        id="{{ $item_order }}"
                                        name="{{ $item_order }}"
                                        min="0"
                                        max="{{ $item->max_score }}"
                                        step="1"
                                        oninput="{{ $item_order }}out.value={{ $item_order }}.value"
                                        value="{{ $schoolassessment->$item_order ?? 0 }}">
                                </div>
                                <div class="col-md-6">
                                    <output
                                        class="btn disabled score-output"
                                        id="{{ $item_order }}out"
                                        name="{{ $item_order }}out"
                                        for="{{ $item_order }}"
                                        >{{ $schoolassessment->$item_order ?? 0 }}</output>
                                    <small class="text-danger">(maks. {{ $item->max_score }})</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        @foreach ($form_extras as $extra)
                        <label for="note" class="form-label">Catatan Tambahan</label>
                        <textarea name="note" rows="8" class="form-control" id="note">{{ $schoolassessment->note }}</textarea>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-modern btn-modern-secondary"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-modern btn-modern-primary">Save</button>
        </div>
    </form>
</div>
