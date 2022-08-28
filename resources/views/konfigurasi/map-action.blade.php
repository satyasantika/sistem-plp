<div class="modal-content">
    <form id="formAction" action="{{ $map->id ? route('maps.update',$map->id) : route('maps.store') }}" method="post">
        @csrf
        @if ($map->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $map->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Mahasiswa</label>
                        <select id="student_id" class="form-control @error('student_id') is-invalid @enderror" name="student_id">
                            <option value="">-- Pilih mahasiswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}" {{ $student->id == $map->student_id ? 'selected' : '' }}>{{ Str::ucfirst($student->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lecture_id" class="form-label">Dosen Pembimbing Lapangan</label>
                        <select id="lecture_id" class="form-control @error('lecture_id') is-invalid @enderror" name="lecture_id">
                            <option value="">-- Pilih DPL --</option>
                            @foreach ($lectures as $lecture)
                                <option value="{{ $lecture->id }}" {{ $lecture->id == $map->lecture_id ? 'selected' : '' }}>{{ Str::ucfirst($lecture->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Guru Pamong</label>
                        <select id="teacher_id" class="form-control @error('teacher_id') is-invalid @enderror" name="teacher_id">
                            <option value="">-- Pilih Guru Pamong --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $teacher->id == $map->teacher_id ? 'selected' : '' }}>{{ Str::ucfirst($teacher->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Tempat Praktik</label>
                        <select id="school_id" class="js-example-basic-single form-select @error('school_id') is-invalid @enderror" name="school_id">
                            <option>-- Pilih Sekolah --</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}" {{ $school->id == $map->school_id ? 'selected' : '' }}>{{ Str::ucfirst($school->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun</label>
                        <input type="text" value="{{ $map->year }}" name="year" class="form-control" id="year">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="plp1" class="form-label">Ikut PLP 1</label>
                        <select id="plp1" class="form-control @error('plp1') is-invalid @enderror" name="plp1">
                            <option value="1" {{ $map->plp1 === true ? 'selected' : '' }}>YA</option>
                            <option value="0" {{ $map->plp1 === false ? 'selected' : '' }}>TIDAK</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="plp2" class="form-label">Ikut PLP 2</label>
                        <select id="plp2" class="form-control @error('plp2') is-invalid @enderror" name="plp2">
                            <option value="1" {{ $map->plp2 === true ? 'selected' : '' }}>YA</option>
                            <option value="0" {{ $map->plp2 === false ? 'selected' : '' }}>TIDAK</option>
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
