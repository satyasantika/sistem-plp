<div class="modal-content">
    <form id="formAction" action="{{ $departementmap->id ? route('departementmaps.update',$departementmap->id) : route('departementmaps.store') }}" method="post">
        @csrf
        @if ($departementmap->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $departementmap->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Tempat Praktik</label>
                        <input type="hidden" value="{{ $departementmap->school_id }}" name="school_id" class="form-control" id="school_id" required readonly>
                        <input type="text" value="{{ $departementmap->schools->name }}" name="school_name" class="form-control" id="school_id" required readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lecture_id" class="form-label">Dosen Pembimbing Lapangan</label>
                        <select id="lecture_id" class="form-control @error('lecture_id') is-invalid @enderror" name="lecture_id">
                            <option value="">-- Pilih DPL --</option>
                            @foreach ($lectures as $lecture)
                                <option value="{{ $lecture->id }}" {{ $lecture->id == $departementmap->lecture_id ? 'selected' : '' }}>{{ Str::ucfirst($lecture->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Mahasiswa</label>
                        <select id="student_id" class="form-control @error('student_id') is-invalid @enderror" name="student_id">
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ $student->id == $departementmap->student_id ? 'selected' : '' }}>{{ Str::ucfirst($student->name) }}</option>
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
