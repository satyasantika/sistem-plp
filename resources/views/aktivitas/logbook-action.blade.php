<div class="modal-content">
    <form id="formAction" action="{{ $studentdiary->id ? route('studentdiaries.update',['studentdiary'=>$studentdiary->id,'plp'=>$plp]) : route('studentdiaries.store',['plp' => $plp]) }}" method="post">
        @csrf
        @if ($studentdiary->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $studentdiary->id ? 'Edit' : 'Tambah' }} Logbook</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <input type="hidden" value="{{ $myMapId }}" name="map_id" class="form-control" id="map_id" >
                <input type="hidden" value="{{ $plp }}" name="plp_order" class="form-control" id="plp_order" >
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="day_order" class="form-label">Hari ke-</label>
                        <select id="day_order" class="form-control @error('day_order') is-invalid @enderror" name="day_order">
                            <option value="">-- --</option>
                            @foreach ($days as $day)
                                <option value="{{ $day }}" {{ $day == $studentdiary->day_order ? 'selected' : '' }}>{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="log_date" class="form-label">Tanggal dicatatkan</label>
                        <input type="date" value="{{ $studentdiary->log_date ? $studentdiary->log_date->format('Y-m-d') : date('Y-m-d') }}" name="log_date" class="form-control" id="log_date">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan Harian</label>
                        <textarea name="note" class="form-control" id="note">{{ $studentdiary->note }}</textarea>
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
