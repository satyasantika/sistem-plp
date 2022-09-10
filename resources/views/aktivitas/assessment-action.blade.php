<div class="modal-content">
    <form
        id="formAction"
        action="@if ($schoolassessment->id)
                    {{ route('schoolassessments.update',array_merge(['schoolassessment'=>$schoolassessment->id],$parameters)) }}
                @else
                    {{ route('schoolassessments.store',$parameters) }}
                @endif"
        method="post">
        @csrf
        @if ($schoolassessment->id) @method('PUT') @endif
        <div class="modal-header m-3">
            <h5 class="modal-title" id="largeModalLabel">{{ $schoolassessment->id ? 'Edit' : 'Pengisian' }} {{ $form->name }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"
                aria-label="Close"></button>
        </div>
        <div class="modal-body m-3">
            <div class="row alert alert-info">
                <input type="hidden" value="{{ $parameters['plp_order'] }}" name="plp_order" class="form-control" id="plp_order" >
                <input type="hidden" value="{{ $parameters['form_id'] }}" name="form_id" class="form-control" id="form_id" >
                <input type="hidden" value="{{ $parameters['form_order'] }}" name="form_order" class="form-control" id="form_order" >
                <input type="hidden" value="{{ $parameters['map_id'] }}" name="map_id" class="form-control" id="map_id" >
                <input type="hidden" value="@hasrole('guru') guru @else dosen @endhasrole" name="assessor" class="form-control" id="assessor" >
                Petunjuk
                <div class="col-md-12">
                    <div class="mb-3">
                        @foreach ($form_guides as $guide)
                            <span class="badge bg-info">{{ $guide->component_order }}</span> {{ $guide->name }}<br>
                        @endforeach
                    </div>
                </div>
            </div>
            @foreach ($form_items as $item)
            <div class="row">
                <div class="col-auto">
                    <span class="badge bg-light text-dark">nomor {{ $item->component_order }}</span>
                </div>
                @php
                    $item_order = 'score'.$item->component_order;
                    $options = [];
                @endphp
                <div class="col-10">
                    <div class="mb-3">
                        {{ $item->name }} <br>
                        @if ($form->type == 'skor_4')
                            @if (substr($form->id,-2) == 'N4')
                                @php $options = $keterpenuhan; @endphp
                            @else
                                @php $options = $kebaikan; @endphp
                            @endif
                            @foreach ($options as $key => $option)
                            <input
                                type="radio"
                                name="{{ $item_order }}"
                                value="{{ $key+1 ?? 0 }}"
                                class="btn-check"
                                id="{{ $item_order }}-{{ $option }}"
                                autocomplete="off"
                                {{ $schoolassessment->$item_order == $key+1 ? 'checked' : '' }}
                                >
                            <label
                                class="btn btn-outline-primary btn-sm mb-1"
                                for="{{ $item_order }}-{{ $option }}"
                                >{{ $option }}</label>
                            @endforeach
                        @else
                            <div class="row">
                                <div class="col-md-6">
                                    <input
                                        type="range"
                                        class="form-range"
                                        id="{{ $item_order }}"
                                        name="{{ $item_order }}"
                                        min="0"
                                        max="{{ $item->max_score }}"
                                        step="1"
                                        oninput="{{ $item_order }}out.value={{ $item_order }}.value"
                                        value="{{ $schoolassessment->$item_order ?? 0 }}">
                                </div>
                                <div class="col-md-6">
                                    <output
                                        class="btn btn-outline-dark disabled"
                                        id="{{ $item_order }}out"
                                        name="{{ $item_order }}out"
                                        for="{{ $item_order }}"
                                        >{{ $schoolassessment->$item_order ?? 0 }}</output>
                                    <small class="text-danger">(maks. {{ $item->max_score }})</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        @foreach ($form_extras as $extra)
                        <label for="note" class="form-label">Catatan Tambahan</label>
                        <textarea name="note" rows="8" class="form-control" id="note">{{ $schoolassessment->note }}</textarea>
                        @endforeach
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
