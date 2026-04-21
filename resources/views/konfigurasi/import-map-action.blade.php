<div class="modal-content">
    <form id="formAction" action="{{ route('maps.importexcel') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $title }}</h5>
        </div>
        <div class="modal-body">
            <div class="import-feedback"></div>
            <div class="alert alert-light border small">
                Format kolom Excel: <strong>nim_mahasiswa, nidn_dosen, nip_guru, nama_sekolah, mapel, year</strong>
            </div>

            <div class="mb-3">
                <a href="{{ route('maps.importtemplate') }}" class="btn btn-outline-primary btn-sm">
                    Download Template Maps
                </a>
            </div>

            <ul class="small ps-3 mb-3">
                <li>Urutan kolom harus: nim_mahasiswa, nidn_dosen, nip_guru, nama_sekolah, mapel, year.</li>
                <li>Kolom `nim_mahasiswa`, `nidn_dosen`, dan `nip_guru` membaca nilai username user.</li>
                <li>Nama sekolah dan mapel tersedia sebagai dropdown pada template Excel.</li>
                <li>Jika referensi user, sekolah, subject, atau kombinasi map sudah ada, seluruh import dibatalkan.</li>
            </ul>

            <label>Pilih file excel</label>
            <div class="form-group">
                <input type="file" name="file" class="form-control" required="required" accept=".xlsx,.xls,.csv">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Import Maps</button>
        </div>
    </form>
</div>
