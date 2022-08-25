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
                        <a href="{{ route('coordinators.create') }}" class="btn btn-primary btn-sm mb-3 btn-add">+ {{ request()->segment(2) }}</a>
                        <table class="table table-hover" id="coordinator-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama Sekolah</th>
                                    <th scope="col">Nama Koordinator GP</th>
                                    <th scope="col">Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coordinators as $coordinator)
                                <tr>
                                    <td scope="row">
                                        <a href="{{ route('coordinators.edit',$coordinator) }}" class="btn btn-primary btn-sm"><i class="ti-pencil"></i></a>
                                        <a href="{{ route('coordinators.destroy',$coordinator) }}" onclick="event.preventDefault();document.getElementById('delete-form').submit();" class="btn btn-danger btn-sm"><i class="ti-trash"></i></a>
                                        @can('usulan/coordinators-delete')
                                        <form id="delete-form" action="{{ route('coordinators.destroy',$coordinator->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endcan
                                    </td>
                                    <td>{{ $coordinator->schools->name }}</td>
                                    <td>{{ $coordinator->candidate_name }}</td>
                                    <td>{{ $coordinator->registered }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
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
@endpush
