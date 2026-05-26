<div class="modal-content">
    <form id="formAction" action="{{ $formitem->id ? route('forms.items.update', [$formContext->id, $formitem->id]) : route('forms.items.store', $formContext->id) }}" method="post">
        @csrf
        @if ($formitem->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $formitem->id ? 'Edit' : 'Tambah' }} item · {{ $formContext->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="form_id" value="{{ $formContext->id }}">
            <p class="text-muted small">Form ID: <strong>{{ $formContext->id }}</strong></p>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="component" class="form-label">Bagian</label>
                        <select id="component" class="form-control @error('component') is-invalid @enderror" name="component">
                            <option value="">-- Pilih tipe bagian --</option>
                            @foreach ($components as $component)
                                <option value="{{ $component }}" {{ $component == $formitem->component ? 'selected' : '' }}>{{ $component }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="component_order" class="form-label">Urutan ke-</label>
                        <input type="text" value="{{ $formitem->component_order }}" name="component_order" class="form-control" id="component_order">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="max_score" class="form-label">Skor maksimal</label>
                        <input type="number" value="{{ $formitem->max_score }}" name="max_score" class="form-control" id="max_score">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Rincian item</label>
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
