<div class="modal-content">
    <form id="formAction" action="{{ route('diaryverifications.update',['diaryverification'=>$diaryverification->id,'plp_order'=>$plp_order]) }}" method="post">
        @csrf
        @method('PUT')
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $diaryverification->id ? 'Edit' : 'Tambah' }} Logbook</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" value="{{ $diaryverification->map_id }}" name="map_id" class="form-control" id="map_id" >
                <input type="hidden" value="{{ $plp_order }}" name="plp_order" class="form-control" id="plp_order" >
                <input type="hidden" value="1" name="verified" class="form-control" id="verified" >
                <div class="col-md-4">
                    <div class="mb-3">
                        hari ke-{{ $diaryverification->day_order ?? '' }} <span class="badge bg-light text-dark">{{ $diaryverification->log_date ? $diaryverification->log_date->format('d-m-Y') : '' }}</span>
                        <br>{{ $diaryverification->note ?? '' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm"
                data-bs-dismiss="modal">Batalkan</button>
            <button type="submit" class="btn btn-primary btn-sm">Ya, Verifikasi!</button>
        </div>
    </form>
</div>
