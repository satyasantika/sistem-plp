@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Penilaian Kegiatan PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Penilaian {{ substr($form->id,-2) }}
                    </div>
                    <div class="card-body">
                        <div class="modal-content">
                            <form id="formAction" action="{{ $assessment->id ? route('assessments.update',['assessment'=>$assessment->id,'form_id'=>$form->id]) : route('assessments.store',['form_id' => $form->id]) }}" method="post">
                                @csrf
                                @if ($assessment->id)
                                    @method('PUT')
                                @endif
                                <div class="modal-header m-3">
                                    <h5 class="modal-title" id="largeModalLabel">{{ $assessment->id ? 'Edit' : 'Pengisian' }} {{ $form->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body m-3">
                                    <div class="row alert alert-info">
                                        <input type="hidden" value="{{ $form->id }}" name="form_id" class="form-control" id="form_id" >
                                        <input type="hidden" value="{{ $map_id }}" name="map_id" class="form-control" id="map_id" >
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
                                            $item_order = 'item'.$item->component_order;
                                        @endphp
                                        <div class="col-10">
                                            <div class="mb-3">
                                                {{ $item->name }} <br>
                                                @foreach (['baik','kurang','tidak'] as $option)
                                                <input type="radio" name="item{{ $item->component_order }}" value="{{ $option }}" class="btn-check" id="{{ $item_order }}-{{ $option }}" autocomplete="off" {{ $assessment->$item_order === $option ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary btn-sm" for="{{ $item_order }}-{{ $option }}">{{ $option }}</label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                @foreach ($form_extras as $extra)
                                                <label for="note" class="form-label">Catatan Harian</label>
                                                <textarea name="note" rows="8" class="form-control" id="note">{{ $assessment->note }}</textarea>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">

        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script src="{{ asset('') }}assets/js/crud2-datatables.js"></script>
    <script>
        crudDataTables('assessment-table')
    </script>
@endpush
