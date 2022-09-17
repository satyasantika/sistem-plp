<div class="modal-content">
    <form
        id="formAction"
        action="{{ $schoolcoordinator->id ? route('schoolcoordinators.update',$schoolcoordinator->id) : route('schoolcoordinators.store') }}"
        method="post"
        >
        @csrf
        @if ($schoolcoordinator->id)
            @method('PUT')
        @endif
        <input type="hidden" name="role" value="korguru">
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">
                {{ $schoolcoordinator->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}
            </h5>
            <button
                type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Asal Sekolah</label>
                        <select id="school_id" class="form-control" name="school_id">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($myschool as $school)
                                <option
                                    value="{{ $school->id }}"
                                    @selected($school->id == $schoolcoordinator->school_id)
                                    >
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Koordinator Guru Pamong</label>
                        <input
                            type="text"
                            value="{{ $schoolcoordinator->name }}"
                            name="name"
                            class="form-control"
                            id="name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input
                            type="text"
                            value="{{ $schoolcoordinator->nip }}"
                            name="nip"
                            class="form-control"
                            id="nip">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">no. WA (Gunakan format: 81XXXXX)</label>
                        <input
                            type="text"
                            value="{{ $schoolcoordinator->phone }}"
                            name="phone"
                            class="form-control"
                            id="phone">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button
                type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            <button
                type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
