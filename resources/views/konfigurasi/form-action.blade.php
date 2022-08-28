<div class="modal-content">
    <form id="formAction" action="{{ $form->id ? route('forms.update',$form->id) : route('forms.store') }}" method="post">
        @csrf
        @if ($form->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $form->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="id" class="form-label">ID Form</label>
                        <input type="text" value="{{ $form->id }}" name="id" class="form-control" id="id" {{ $form->id ? 'readonly' : '' }}>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Form</label>
                        <input type="text" value="{{ $form->name }}" name="name" class="form-control" id="name">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Form</label>
                        <select id="type" class="form-control @error('type') is-invalid @enderror" name="type">
                            <option value="">-- Pilih Tipe Form --</option>
                            @foreach ($formtypes as $formtype)
                                <option value="{{ $formtype }}" {{ $formtype == $form->type ? 'selected' : '' }}>{{ $formtype }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="count" class="form-label">Banyak Item</label>
                        <input type="number" value="{{ $form->count }}" name="count" class="form-control" id="count">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="max_score" class="form-label">Skor Maksimal</label>
                        <input type="number" value="{{ $form->max_score }}" name="max_score" class="form-control" id="max_score">
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
