<div class="modal-content">
    <form id="formAction" action="{{ $school->id ? route('schools.update',$school->id) : route('schools.store') }}" method="post">
        @csrf
        @if ($school->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $school->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="schoolName" class="form-label">Nama Sekolah</label>
                        <input type="text" value="{{ $school->name }}" name="name" class="form-control" id="schoolName" required autofocus>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="addressName" class="form-label">Alamat</label>
                        <input type="text" value="{{ $school->address }}" name="address" class="form-control" id="addressName">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="headmaster_id" class="form-label">Kepala Sekolah</label>
                        <select id="headmaster_id" class="form-control @error('headmaster_id') is-invalid @enderror" name="headmaster_id">
                            <option value="">-- Pilih nama Kepala --</option>
                            @foreach (App\Models\User::role('kepsek')->select('id','name')->orderBy('name')->get() as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $school->headmaster_id ? 'selected' : '' }}>{{ Str::ucfirst($user->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="coordinator_id" class="form-label">Koordinator Guru Pamong</label>
                        <select id="coordinator_id" class="form-control @error('coordinator_id') is-invalid @enderror" name="coordinator_id">
                            <option value="">-- Pilih nama Koordinator --</option>
                            @foreach (App\Models\User::role('korguru')->select('id','name')->orderBy('name')->get() as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $school->coordinator_id ? 'selected' : '' }}>{{ Str::ucfirst($user->name) }}</option>
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
