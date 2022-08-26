<div class="modal-content">
    <form id="formAction" action="{{ $schooluserproposal->id ? route('schooluserproposals.update',$schooluserproposal->id) : route('schooluserproposals.store') }}" method="post">
        @csrf
        @if ($schooluserproposal->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $schooluserproposal->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Nama Sekolah</label>
                        <select id="school_id" class="form-control @error('school_id') is-invalid @enderror" name="school_id">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}" {{ $school->id == $schooluserproposal->school_id ? 'selected' : '' }}>{{ Str::ucfirst($school->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="candidate_name" class="form-label">Nama yang Diusulkan</label>
                        <input type="text" value="{{ $schooluserproposal->candidate_name }}" name="candidate_name" class="form-control" id="candidate_name" required>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="candidate_role" class="form-label">Diusulkan sebagai</label> &nbsp;
                    <div class="form-check-inline">
                        <input class="btn-check" type="radio" name="candidate_role" id="option1" value="guru" {{ $schooluserproposal->candidate_role == 'guru' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary btn-sm" for="option1">Guru Pamong</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="btn-check" type="radio" name="candidate_role" id="option2" value="korgur" {{ $schooluserproposal->candidate_role == 'korgur' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary btn-sm" for="option2">Koordinator Guru Pamong</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Bidang Studi (Jika Guru Pamong)</label>
                        <select id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" name="subject_id">
                            <option value="">-- Pilih bidang studi --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $subject->id == $schooluserproposal->subject_id ? 'selected' : '' }}>{{ Str::ucfirst($subject->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="class_count" class="form-label">Banyak Rombel (jika Guru Pamong)</label>
                        <input type="text" value="{{ $schooluserproposal->class_count }}" name="class_count" class="form-control" id="class_count">
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
