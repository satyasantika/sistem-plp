<div class="modal-content">
    <form id="formAction" action="{{ $diary->id ? route('diaries.update',$diary->id) : route('diaries.store') }}" method="post">
        @csrf
        @if ($diary->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $diary->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="map_id" class="form-label">Mahasiswa</label>
                        <select id="map_id" class="form-control @error('map_id') is-invalid @enderror" name="map_id" {{ $diary->id ? 'disabled' : '' }}>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($maps as $map)
                                <option value="{{ $map->id }}" {{ $map->id == $diary->map_id ? 'selected' : '' }}>{{ $map->students->name }} dari {{ $map->schools->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="plp_order" class="form-label">Waktu Pelaksanaan </label> &nbsp;
                    <div class="form-check-inline">
                        <input class="btn-check" type="radio" name="plp_order" id="plp_order_option1" value="1" {{ $diary->plp_order == 1 ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary btn-sm" for="plp_order_option1">PLP 1</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="btn-check" type="radio" name="plp_order" id="plp_order_option2" value="2" {{ $diary->plp_order == 2 ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary btn-sm" for="plp_order_option2">PLP 2</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="day_order" class="form-label">Hari ke-</label>
                        <select id="day_order" class="form-control @error('day_order') is-invalid @enderror" name="day_order">
                            <option value="">-- Pilih urutan hari --</option>
                            @foreach ($day as $value)
                                <option value="{{ $value }}" {{ $value == $diary->day_order ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="log_date" class="form-label">Tanggal</label>
                        <input type="date" value="{{ $diary->log_date ? $diary->log_date->format('Y-m-d') : date('Y-m-d') }}" name="log_date" class="form-control" id="log_date">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="verified" class="form-label">Diverifikasi</label> &nbsp;
                    <div class="form-check-inline">
                        <input class="btn-check" type="radio" name="verified" id="verified_option1" value="0" {{ $diary->verified == 0 ? 'checked' : '' }} >
                        <label class="btn btn-outline-primary btn-sm" for="verified_option1">Belum</label>
                    </div>
                    <div class="form-check-inline">
                        <input class="btn-check" type="radio" name="verified" id="verified_option2" value="1" {{ $diary->verified == 1 ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary btn-sm" for="verified_option2">Sudah</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea name="note" id="note" class="form-control" rows="5">{{ $diary->note }}</textarea>
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
