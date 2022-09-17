<div class="modal-content">
    <form
        id="formAction"
        action="{{ $schoolteacher->id ? route('schoolteachers.update',$schoolteacher->id) : route('schoolteachers.store') }}"
        method="post"
        >
        @csrf
        @if ($schoolteacher->id)
            @method('PUT')
        @endif
        <input type="hidden" name="role" value="guru">
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">
                {{ $schoolteacher->id ? 'Edit' : 'Tambah' }} Guru Pamong
            </h5>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="modal"
                aria-label="Close">
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Asal Sekolah</label>
                        <select
                            id="school_id"
                            class="form-control"
                            name="school_id"
                            @if (auth()->user()->hasrole('korguru')) disabled @endif
                            >
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($myschool as $school)
                                @if (auth()->user()->hasrole('kepsek'))
                                    <option
                                        value="{{ $school->id }}"
                                        @selected($school->id == $schoolteacher->school_id)
                                        >
                                        {{ $school->name }}
                                    </option>
                                @else
                                    <option value="{{ $school->id }}" selected>{{ $school->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Mata Pelajaran</label>
                        <select
                            id="subject_id"
                            class="form-control"
                            name="subject_id"
                            >
                            <option value="">-- Pilih Mapel --</option>
                            @foreach ($subjects as $subject)
                                @continue($subject->name == 'penmas')
                                <option
                                    value="{{ $subject->id }}"
                                    @selected($subject->id == $schoolteacher->subject_id)
                                    >
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Guru Pamong</label>
                        <input
                            type="text"
                            value="{{ $schoolteacher->name }}"
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
                            value="{{ $schoolteacher->nip }}"
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
                            value="{{ $schoolteacher->phone }}"
                            name="phone"
                            class="form-control"
                            id="phone">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="class_count" class="form-label">Banyak Rombel</label>
                        <input
                            type="text"
                            value="{{ $schoolteacher->class_count }}"
                            name="class_count"
                            class="form-control"
                            id="class_count">
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
