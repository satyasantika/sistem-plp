@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        {{ ucFirst(request()->segment(1)) }} {{ ucFirst(request()->segment(2)) }}
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="modal-content">
                            <form id="formAction" action="{{  route('teachers.store') }}" method="post">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="largeModalLabel">Tambah {{ ucFirst(request()->segment(2)) }}</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="candidate_name" class="form-label">Nama Lengkap</label>
                                                <input type="text" name="candidate_name" class="form-control" id="candidate_name" required autofocus>
                                            </div>
                                        </div>
                                        <input type="hidden" value="guru" name="candidate_role">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="school_id" class="form-label">Asal Sekolah</label>
                                                <select id="school_id" class="form-control @error('school_id') is-invalid @enderror" name="school_id">
                                                    <option value="">-- Pilih Sekolah --</option>
                                                    @foreach ($myschool as $school)
                                                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="subject_id" class="form-label">Asal Sekolah</label>
                                                <select id="subject_id" class="form-control @error('subject_id') is-invalid @enderror" name="subject_id">
                                                    <option value="">-- Pilih Mapel --</option>
                                                    @foreach ($subjects as $subject)
                                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary btn-sm btn-add">Cancel</a>
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
@endpush
