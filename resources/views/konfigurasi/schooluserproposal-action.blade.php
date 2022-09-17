<div class="modal-content">
    <form
        id="formAction"
        action="{{ $schooluserproposal->id ? route('schooluserproposals.update',$schooluserproposal->id) : route('schooluserproposals.store') }}"
        method="post"
        >
        @csrf
        @if ($schooluserproposal->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5
                class="modal-title"
                id="largeModalLabel"
                >
                {{ $schooluserproposal->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="school_id" class="form-label">Nama Sekolah</label>
                        <select id="school_id" class="form-control" name="school_id">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($schools as $school)
                                <option
                                    value="{{ $school->id }}"
                                    @selected($school->id == $schooluserproposal->school_id)
                                    >
                                    {{ Str::ucfirst($school->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Bidang Studi (Jika Guru Pamong)</label>
                        <select id="subject_id" class="form-control" name="subject_id">
                            <option value="">-- Pilih bidang studi --</option>
                            @foreach ($subjects as $subject)
                                <option
                                    value="{{ $subject->id }}"
                                    @selected($subject->id == $schooluserproposal->subject_id)
                                    >
                                    {{ Str::ucfirst($subject->name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="role" class="form-label">Diusulkan sebagai</label> &nbsp;
                    <div class="form-check-inline">
                        <input
                            class="btn-check"
                            type="radio"
                            name="role"
                            id="option1"
                            value="guru"
                            @checked($schooluserproposal->role == 'guru')>
                        <label class="btn btn-outline-primary btn-sm" for="option1">Guru Pamong</label>
                    </div>
                    <div class="form-check-inline">
                        <input
                            class="btn-check"
                            type="radio"
                            name="role"
                            id="option2"
                            value="korgur"
                            @checked($schooluserproposal->role == 'korgur')>
                        <label class="btn btn-outline-primary btn-sm" for="option2">Koordinator Guru Pamong</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama yang Diusulkan</label>
                        <input
                            type="text"
                            value="{{ $schooluserproposal->name }}"
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
                            value="{{ $schooluserproposal->nip }}"
                            name="nip"
                            class="form-control"
                            id="nip">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone" class="form-label">no. WA</label>
                        <input
                            type="text"
                            value="{{ $schooluserproposal->phone }}"
                            name="phone"
                            class="form-control"
                            id="phone">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="class_count" class="form-label">Banyak Rombel (jika Guru Pamong)</label>
                        <input
                            type="text"
                            value="{{ $schooluserproposal->class_count }}"
                            name="class_count"
                            class="form-control"
                            id="class_count">
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
