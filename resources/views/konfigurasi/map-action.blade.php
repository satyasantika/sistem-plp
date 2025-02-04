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
                        <select id="student_id" class="form-control" name="student_id">
                            <option value="">-- Pilih mahasiswa --</option>
                            @if ($map->id)
                                <option value="{{ $map->student_id ?? '' }}" selected>
                                    {{ $map->students->name ?? '' }}
                                </option>
                            @endif
                            @foreach ($students as $student)
                                <option
                                    value="{{ $student->id }}"
                                    {{-- @selected($student->id == $map->student_id) --}}
                                    >
                                    {{ Str::ucfirst($student->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lecture_id" class="form-label">Dosen Pembimbing Lapangan</label>
                        <select id="lecture_id" class="form-control" name="lecture_id">
                            <option value="">-- Pilih DPL --</option>
                            @foreach ($lectures as $lecture)
                                <option
                                    value="{{ $lecture->id }}"
                                    @selected($lecture->id == $map->lecture_id)>
                                    {{ Str::ucfirst($lecture->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Guru Pamong</label>
                        <select id="teacher_id" class="form-control" name="teacher_id">
                            <option value="">-- Pilih Guru Pamong --</option>
                            @foreach ($teachers as $teacher)
                                <option
                                    value="{{ $teacher->id }}"
                                    @selected($teacher->id == $map->teacher_id)>
                                    {{ Str::ucfirst($teacher->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Tempat Praktik</label>
                        <select id="school_id" class="js-example-basic-single form-select" name="school_id">
                            <option>-- Pilih Sekolah --</option>
                            @foreach ($schools as $school)
                                <option
                                    value="{{ $school->id }}"
                                    @selected($school->id == $map->school_id)>
                                    {{ Str::ucfirst($school->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran @if (!$map->id) <div class="text-danger">(wajib diisi)</div> @endif</label>
                        <select id="subject_id" class="js-example-basic-single form-select" name="subject_id" @if ($map->id) disabled @endif required>
                            <option>-- Pilih Mapel --</option>
                            @foreach ($subjects as $subject)
                                <option
                                    value="{{ $subject->id }}"
                                    @selected($subject->id == $map->subject_id)>
                                    {{ Str::ucfirst($subject->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="year" class="form-label">Tahun</label>
                        <input type="text"
                            value="{{ $map->year }}" name="year" class="form-control" id="year">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="plp1" class="form-label">Ikut PLP</label>
                        <select id="plp1" class="form-control @error('plp1') is-invalid @enderror" name="plp">
                            <option value="1" @selected($map->plp)>YA</option>
                            <option value="0" @selected($map->plp!=1)>TIDAK</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="plp1" class="form-label">Ikut PLP 1</label>
                        <select id="plp1" class="form-control @error('plp1') is-invalid @enderror" name="plp1">
                            <option value="1" @selected($map->plp1)>YA</option>
                            <option value="0" @selected($map->plp1!=1)>TIDAK</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-2">
                        <label for="plp2" class="form-label">Ikut PLP 2</label>
                        <select id="plp2" class="form-control @error('plp2') is-invalid @enderror" name="plp2">
                            <option value="1" @selected($map->plp2)>YA</option>
                            <option value="0" @selected($map->plp2!=1)>TIDAK</option>
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
