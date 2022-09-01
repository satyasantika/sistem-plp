<div class="modal-content">
    <form id="formAction" action="{{ $navigation->id ? route('navigations.update',$navigation->id) : route('navigations.store') }}" method="post">
        @csrf
        @if ($navigation->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $navigation->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Menu</label>
                        <input type="text" value="{{ $navigation->name }}" name="name" class="form-control" id="name" required autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="url" class="form-label">URL</label>
                        <input type="text" value="{{ $navigation->url }}" name="url" class="form-control" id="url" required >
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="icon" class="form-label">icon</label>
                        <input type="text" value="{{ $navigation->icon }}" name="icon" class="form-control" id="icon">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="order" class="form-label">Urutan</label>
                        <input type="text" value="{{ $navigation->order }}" name="order" class="form-control" id="order">
                    </div>
                </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">Parent Menu</label>
                            <select id="parent_id" class="form-control @error('parent_id') is-invalid @enderror" name="parent_id">
                                <option value="">-- Parent Menu --</option>
                                @foreach ($parent_navs as $nav)
                                    <option value="{{ $nav->id }}" {{ $nav->id == $navigation->parent_id ? 'selected' : '' }}>{{ $nav->name }}</option>
                                @endforeach
                            </select>
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
