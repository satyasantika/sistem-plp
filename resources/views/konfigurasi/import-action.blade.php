<div class="modal-content">
    <form id="formAction" action="{{ route('users.import') }}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
        </div>
        <div class="modal-body">

            <label>Pilih file excel</label>
            <div class="form-group">
                <input type="file" name="file" required="required">
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Close</button>
            {{-- <button type="button" class="btn btn-secondary"÷ data-dismiss="modal">Close</button> --}}
            <button type="submit" class="btn btn-primary btn-sm">Import</button>
        </div>
    </form>
</div>
