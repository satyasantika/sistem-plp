<div class="modal-content">
    <form id="formAction" action="{{ $assessment->id ? route('assessments.update',$assessment->id) : route('assessments.store') }}" method="post">
        @csrf
        @if ($assessment->id)
            @method('PUT')
        @endif
        <div class="modal-header">
            <h5 class="modal-title" id="largeModalLabel">{{ $assessment->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="map_id" class="form-label">Mahasiswa</label>
                        <select id="map_id" class="form-control @error('map_id') is-invalid @enderror" name="map_id" {{ $assessment->id ? 'disabled' : '' }}>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($maps as $map)
                                <option value="{{ $map->id }}" {{ $map->id == $assessment->map_id ? 'selected' : '' }}>{{ $map->students->name }} dari {{ $map->schools->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="assessor" class="form-label">Penilai</label> &nbsp;
                    <div class="mb-3">
                        <div class="form-check-inline">
                            <input class="btn-check" type="radio" name="assessor" id="assessor_option1" value="guru" {{ $assessment->assessor == 'guru' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="assessor_option1">Guru Pamong</label>
                        </div>
                        <div class="form-check-inline">
                            <input class="btn-check" type="radio" name="assessor" id="assessor_option2" value="dosen" {{ $assessment->assessor == 'dosen' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="assessor_option2">Dosen</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="plp_order" class="form-label">Waktu Pelaksanaan </label> &nbsp;
                    <div class="mb-3">
                        <div class="form-check-inline">
                            <input class="btn-check" type="radio" name="plp_order" id="plp_order_option1" value="1" {{ $assessment->plp_order == 1 ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="plp_order_option1">PLP 1</label>
                        </div>
                        <div class="form-check-inline">
                            <input class="btn-check" type="radio" name="plp_order" id="plp_order_option2" value="2" {{ $assessment->plp_order == 2 ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary btn-sm" for="plp_order_option2">PLP 2</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="form_id" class="form-label">ID Form</label>
                        <select id="form_id" class="form-control @error('form_id') is-invalid @enderror" name="form_id">
                            <option value="">-- Pilih Form --</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form }}" {{ $form == $assessment->form_id ? 'selected' : '' }}>{{ $form }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="form_order" class="form-label">Form ke-</label>
                        <select id="form_order" class="form-control @error('form_order') is-invalid @enderror" name="form_order">
                            <option value="">-- Pilih urutan penilaian --</option>
                            @foreach ($form_order as $value)
                                <option value="{{ $value }}" {{ $value == $assessment->form_order ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @foreach ($items as $item)
                <div class="col-md-2">
                    <div class="mb-3">
                        <label for="{{ $item }}" class="form-label">Skor {{ $item }}</label>
                        <input type="number" value="{{ $assessment->$item }}" name="{{ $item }}" class="form-control" id="{{ $item }}">
                    </div>
                </div>
                @endforeach
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea name="note" id="note" class="form-control" rows="5">{{ $assessment->note }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exam_thema" class="form-label">Tema (khusus n7)</label>
                        <textarea name="exam_thema" id="exam_thema" class="form-control" rows="5">{{ $assessment->exam_thema }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="exam_subthema" class="form-label">Subtema (khusus n7)</label>
                        <textarea name="exam_subthema" id="exam_subthema" class="form-control" rows="5">{{ $assessment->exam_subthema }}</textarea>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exam_class" class="form-label">Kelas Ujian (khusus n7)</label>
                        <input type="text" value="{{ $assessment->exam_class }}" name="exam_class" class="form-control" id="exam_class">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exam_date" class="form-label">Tanggal Ujian (khusus n7)</label>
                        <input type="date" value="{{ $assessment->exam_date ? $assessment->exam_date->format('Y-m-d') : '' }}" name="exam_date" class="form-control" id="exam_date">
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
