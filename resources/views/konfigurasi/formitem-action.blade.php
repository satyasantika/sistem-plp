<div class="modal-content">
    <form id="formAction" action="{{ $formitem->id ? route('formitems.update',$formitem->id) : route('formitems.store') }}" method="post">
        @csrf
        @if ($formitem->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $formitem->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="form_id" class="form-label">ID Form</label>
                        <select id="form_id" class="form-control @error('form_id') is-invalid @enderror" name="form_id">
                            <option value="">-- Pilih Form --</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form }}" {{ $form == $formitem->form_id ? 'selected' : '' }}>{{ $form }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="component" class="form-label">Bagian</label>
                        <select id="component" class="form-control @error('component') is-invalid @enderror" name="component">
                            <option value="">-- Pilih Tipe Form --</option>
                            @foreach ($components as $component)
                                <option value="{{ $component }}" {{ $component == $formitem->component ? 'selected' : '' }}>{{ $component }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="component_order" class="form-label">Urutan ke-</label>
                        <input type="text" value="{{ $formitem->component_order }}" name="component_order" class="form-control" id="component_order">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="max_score" class="form-label">Skor Maksimal</label>
                        <input type="number" value="{{ $formitem->max_score }}" name="max_score" class="form-control" id="max_score">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Rincian Item</label>
                        <input type="text" value="{{ $formitem->name }}" name="name" class="form-control" id="name">
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
