<div class="modal-content">
    <form
        id="formAction"
        action="{{ $teachermap->id ? route('teachermaps.update',$teachermap->id) : route('teachermaps.store') }}"
        method="post">
        @csrf
        @if ($teachermap->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5
                class="modal-title"
                id="largeModalLabel">
                {{ $teachermap->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Tempat Praktik</label>
                        <input
                            type="hidden"
                            value="{{ $teachermap->school_id }}"
                            name="school_id"
                            class="form-control">
                        <input
                            type="text"
                            value="{{ $teachermap->schools->name }}"
                            name="school_name"
                            class="form-control"
                            readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="teacher_id" class="form-label">Guru Pamong</label>
                        <select id="teacher_id" class="form-control" name="teacher_id">
                            <option value="">-- Pilih Guru Pamong --</option>
                            @foreach ($teachers as $teacher)
                                <option
                                    @php
                                        $teacher_id = App\Models\User::where('username',$teacher->nip)->value('id');
                                    @endphp
                                    value="{{ $teacher_id }}"
                                    @selected($teacher_id == $teachermap->teacher_id)>
                                    {{ Str::ucfirst($teacher->name) }} - ({{ $teacher->subjects->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Mahasiswa</label>
                        <input
                            type="hidden"
                            value="{{ $teachermap->student_id }}"
                            name="student_id"
                            class="form-control">
                        <input
                            type="text"
                            value="{{ $teachermap->students->name }}"
                            name="student_name"
                            class="form-control"
                            readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lecture_id" class="form-label">DPL</label>
                        <input
                            type="hidden"
                            value="{{ $teachermap->lecture_id }}"
                            name="lecture_id"
                            class="form-control">
                        <input
                            type="text"
                            value="{{ $teachermap->lectures->name }}"
                            name="lecture_name"
                            class="form-control"
                            readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
