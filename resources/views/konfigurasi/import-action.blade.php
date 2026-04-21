<div class="modal-content">
    <form id="formAction" action="{{ route('users.importexcel') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="hidden" name="role" value="{{ $role }}">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
        </div>
        <div class="modal-body">
            <div class="import-feedback"></div>
            <div class="alert alert-light border small">
                Format kolom Excel: <strong>username, name, email, password, subject_id</strong>
            </div>

            <div class="mb-3">
                <a href="{{ route('users.importtemplate', ['role' => $role]) }}" class="btn btn-outline-primary btn-sm">
                    Download Template {{ ucfirst($role) }}
                </a>
            </div>

            <ul class="small ps-3 mb-3">
                <li>Urutan kolom harus: username, name, email, password, subject_id.</li>
                <li>Semua kolom wajib diisi.</li>
                <li>Email harus valid.</li>
                <li>Password minimal 6 karakter.</li>
                <li>Template Excel menyediakan dropdown subject_id otomatis dari tabel subject.</li>
                <li>Jika username atau email ganda di file atau sudah terdaftar, seluruh import dibatalkan.</li>
            </ul>

            <label>Pilih file excel</label>
            <div class="form-group">
                <input type="file" name="file" class="form-control" required="required" accept=".xlsx,.xls,.csv">
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Import {{ ucfirst($role) }}</button>
        </div>
    </form>
</div>
