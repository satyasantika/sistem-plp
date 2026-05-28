<div class="modal-content">
    @php
        $sectionLabelsUi = ['petunjuk' => 'Petunjuk', 'item' => 'Item', 'tambahan' => 'Tambahan'];
        $currentComponent = $formitem->component ?? 'petunjuk';
        $componentLabel = $sectionLabelsUi[$currentComponent] ?? ucfirst($currentComponent);
        $showScore = $currentComponent === 'item';
    @endphp
    <form id="formAction" action="{{ $formitem->id ? url('/konfigurasi/forms/'.$formContext->id.'/items/'.$formitem->id) : url('/konfigurasi/forms/'.$formContext->id.'/items') }}" method="post">
        @csrf
        @if ($formitem->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $formitem->id ? 'Edit' : 'Tambah' }} {{ strtolower($componentLabel) }} · {{ $formContext->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="form_id" value="{{ $formContext->id }}">
            <input type="hidden" name="component" value="{{ $currentComponent }}">
            @if (!$showScore)
                <input type="hidden" name="max_score" value="0">
            @endif
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Bagian</label>
                        <p class="form-control-plaintext mb-0 py-2 border rounded px-2 bg-light small">{{ $componentLabel }}</p>
                    </div>
                </div>
                <div class="col-md-{{ $showScore ? '4' : '8' }}">
                    <div class="mb-3">
                        <label for="component_order" class="form-label">Urutan ke-</label>
                        <input type="text" value="{{ $formitem->component_order }}" name="component_order" class="form-control" id="component_order">
                    </div>
                </div>
                @if ($showScore)
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="max_score" class="form-label">Skor maksimal</label>
                        <input type="number" min="0" step="1" value="{{ old('max_score', $formitem->max_score ?? 0) }}" name="max_score" class="form-control" id="max_score">
                    </div>
                </div>
                @endif
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Rincian {{ strtolower($componentLabel) }}</label>
                        <textarea name="name" id="name" class="form-control" rows="3">{{ $formitem->name }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
