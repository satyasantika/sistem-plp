<div class="modal-content">
    <form
        id="formAction"
        action="{{ $observation->id ? route('observations.update',$observation->id) : route('observations.store') }}"
        method="post">
        @csrf
        @if ($observation->id) @method('PUT') @endif
        <div class="modal-header">
            <h5
                class="modal-title"
                id="largeModalLabel">
                {{ $observation->id ? 'Edit' : 'Tambah' }} {{ ucFirst(request()->segment(2)) }}
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="map_id" class="form-label">Mahasiswa</label>
                        <select
                            id="map_id"
                            class="form-control"
                            name="map_id"
                            @disabled($observation->id)>
                            <option value="">-- Pilih Mahasiswa --</option>
                            @foreach ($maps as $map)
                            <option
                                value="{{ $map->id }}"
                                @selected($map->id == $observation->map_id)>
                                {{ $map->students->name }} dari {{ $map->schools->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="form_id" class="form-label">ID Form</label>
                        <select
                            id="form_id"
                            class="form-control"
                            name="form_id"
                            @disabled($observation->id)>
                            <option value="">-- Pilih Form --</option>
                            {{-- jenis form --}}
                            @foreach ($forms->pluck('id')->sort() as $form)
                            <option
                                value="{{ $form }}"
                                @selected($form == $observation->form_id)>
                                {{ $form }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- pernyataaan item --}}
                <div class="col-md-9 row">
                    @php
                        $max = $forms->max('count');
                        $count = ($observation->id) ? $observation->forms->count : $max;
                    @endphp
                    @for ($i = 0; $i < $count; $i++)
                    @php $item = 'item'.$i+1; @endphp
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="{{ $item }}" class="form-label">{{ $item }}</label>
                            <select id="{{ $item }}" class="form-control" name="{{ $item }}">
                                <option value="">-- Respon --</option>
                                @foreach ($options as $option)
                                <option
                                    value="{{ $option }}"
                                    @selected($option == $observation->$item)>
                                    {{ $option }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endfor
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="note" class="form-label">Catatan</label>
                        <textarea
                            name="note"
                            id="note"
                            class="form-control"
                            rows="5">
                            {{ $observation->note }}
                        </textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
    </form>
</div>
