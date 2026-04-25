<style>
    .obs-modal .modal-content {
        border: 1px solid rgba(85, 116, 159, 0.24);
        border-radius: 16px;
        overflow: hidden;
        background: linear-gradient(165deg, #ffffff 0%, #f6f9ff 100%);
    }

    .obs-modal .modal-header,
    .obs-modal .modal-body,
    .obs-modal .modal-footer {
        margin: 0 !important;
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .obs-modal .modal-header {
        border-bottom: 1px solid rgba(85, 116, 159, 0.2);
        background: linear-gradient(140deg, rgba(237, 244, 255, 0.95), rgba(247, 251, 255, 0.95));
    }

    .obs-modal .modal-title {
        font-size: 0.98rem;
        font-weight: 800;
        letter-spacing: 0.2px;
        color: #233754;
    }

    .obs-modal .guide-box {
        border: 1px solid rgba(74, 134, 215, 0.22);
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(236, 245, 255, 0.8), rgba(247, 251, 255, 0.8));
        padding: 12px;
    }

    .obs-modal .guide-title {
        margin: 0 0 8px;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.42px;
        color: #57719a;
        font-weight: 700;
    }

    .obs-modal .badge-modern {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 0.32rem 0.6rem;
        font-size: 0.71rem;
        font-weight: 700;
        letter-spacing: 0.26px;
        border: 1px solid transparent;
    }

    .obs-modal .badge-modern-info {
        background: rgba(23, 162, 184, 0.16);
        color: #0d7283;
        border-color: rgba(23, 162, 184, 0.28);
    }

    .obs-modal .badge-modern-neutral {
        background: rgba(108, 117, 125, 0.14);
        color: #4e5965;
        border-color: rgba(108, 117, 125, 0.24);
    }

    .obs-modal .obs-item-row {
        border: 1px solid rgba(86, 116, 158, 0.18);
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 10px;
        background: rgba(255, 255, 255, 0.82);
    }

    .obs-modal .obs-item-label {
        color: #294166;
        font-weight: 600;
        line-height: 1.45;
    }

    .obs-modal .btn-option {
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.24px;
        padding: 0.32rem 0.66rem;
        border-width: 1px;
    }

    .obs-modal .btn-check:checked + .btn-option {
        color: #fff;
        border-color: #1f74df;
        background: linear-gradient(135deg, #2476f3, #1759c5);
        box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
    }

    .obs-modal .form-label {
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.2px;
        color: #3d567b;
    }

    .obs-modal .form-control {
        border-radius: 11px;
        border-color: rgba(84, 116, 158, 0.28);
    }

    .obs-modal .modal-footer {
        border-top: 1px solid rgba(85, 116, 159, 0.2);
        background: rgba(248, 251, 255, 0.9);
    }

    .obs-modal .btn-modern {
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.28px;
        padding: 0.42rem 0.84rem;
        transition: transform 0.18s ease, filter 0.2s ease, box-shadow 0.22s ease;
    }

    .obs-modal .btn-modern:hover,
    .obs-modal .btn-modern:focus {
        transform: translateY(-1px);
        filter: saturate(1.08);
    }

    .obs-modal .btn-modern-secondary {
        border-color: rgba(74, 105, 148, 0.34);
        color: #264063;
        background: rgba(255, 255, 255, 0.62);
    }

    .obs-modal .btn-modern-primary {
        color: #fff;
        border-color: transparent;
        background: linear-gradient(135deg, #2476f3, #1759c5);
        box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
    }

    body.dark .obs-modal .modal-content {
        border-color: rgba(157, 185, 224, 0.26);
        background: linear-gradient(165deg, rgba(22, 36, 58, 0.96), rgba(15, 28, 46, 0.96));
    }

    body.dark .obs-modal .modal-header {
        border-bottom-color: rgba(157, 185, 224, 0.24);
        background: linear-gradient(140deg, rgba(35, 53, 82, 0.95), rgba(26, 41, 66, 0.95));
    }

    body.dark .obs-modal .modal-footer {
        border-top-color: rgba(157, 185, 224, 0.24);
        background: rgba(21, 35, 56, 0.9);
    }

    body.dark .obs-modal .modal-title,
    body.dark .obs-modal .obs-item-label,
    body.dark .obs-modal .form-label {
        color: #dce8ff;
    }

    body.dark .obs-modal .guide-title {
        color: #a9bddf;
    }

    body.dark .obs-modal .guide-box,
    body.dark .obs-modal .obs-item-row {
        border-color: rgba(157, 185, 224, 0.24);
        background: rgba(25, 38, 58, 0.56);
    }

    body.dark .obs-modal .badge-modern-info {
        background: rgba(76, 194, 211, 0.2);
        color: #bdebf2;
        border-color: rgba(76, 194, 211, 0.34);
    }

    body.dark .obs-modal .badge-modern-neutral {
        background: rgba(130, 146, 166, 0.22);
        color: #d3deeb;
        border-color: rgba(130, 146, 166, 0.36);
    }

    body.dark .obs-modal .btn-modern-secondary {
        border-color: rgba(146, 182, 230, 0.45);
        color: #cfe3ff;
        background: rgba(43, 66, 103, 0.36);
    }

    body.dark .obs-modal .form-control {
        color: #e2ecff;
        background-color: rgba(21, 35, 56, 0.75);
        border-color: rgba(146, 182, 230, 0.38);
    }

    @media (max-width: 768px) {
        .obs-modal .modal-header,
        .obs-modal .modal-body,
        .obs-modal .modal-footer {
            padding-left: 0.84rem;
            padding-right: 0.84rem;
        }

        .obs-modal .btn-modern,
        .obs-modal .btn-option {
            font-size: 0.7rem;
        }
    }
</style>

<div class="modal-content obs-modal">
    <form id="formAction" action="{{ $studentobservation->id ? route('studentobservations.only.update',['studentobservation'=>$studentobservation->id,'form_id'=>$form->id]) : route('studentobservations.only.store',['form_id' => $form->id]) }}" method="post">
        @csrf
        @if ($studentobservation->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $studentobservation->id ? 'Edit' : 'Pengisian' }} {{ $form->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row guide-box mb-3">
                <input type="hidden" value="{{ $form->id }}" name="form_id" class="form-control" id="form_id" >
                <input type="hidden" value="{{ $map_id }}" name="map_id" class="form-control" id="map_id" >
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
            <div class="row obs-item-row">
                <div class="col-auto">
                    <span class="badge-modern badge-modern-neutral">nomor {{ $item->component_order }}</span>
                </div>
                @php
                    $item_order = 'item'.$item->component_order;
                @endphp
                <div class="col-10">
                    <div class="mb-1 obs-item-label">
                        {{ $item->name }} <br>
                        @foreach (['baik','kurang','tidak'] as $option)
                        <input type="radio" name="item{{ $item->component_order }}" value="{{ $option }}" class="btn-check" id="{{ $item_order }}-{{ $option }}" autocomplete="off" {{ $studentobservation->$item_order === $option ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary btn-sm btn-option" for="{{ $item_order }}-{{ $option }}">{{ $option }}</label>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        @foreach ($form_extras as $extra)
                        <label for="note" class="form-label">Catatan Harian</label>
                        <textarea name="note" rows="8" class="form-control" id="note">{{ $studentobservation->note }}</textarea>
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
