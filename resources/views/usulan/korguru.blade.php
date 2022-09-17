@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Usulan Koordinator Guru Pamong
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ Koordinator Guru Pamong</button>
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display nowrap dataTable no-footer" id="schoolcoordinator-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Koordinator GP</th>
                                            <th scope="col">Persetujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($coordinators as $coordinator)
                                        <tr>
                                            <td class="text-center align-top">
                                                @can('usulan/schoolcoordinators-update')
                                                <button type="button" data-id="{{ $coordinator->id }}" data-jenis="edit" class="btn btn-primary btn-sm action"><i class="ti-pencil"></i></button>
                                                @endcan
                                                @can('usulan/schoolcoordinators-delete')
                                                <button type="button" data-id="{{ $coordinator->id }}" data-jenis="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>
                                                @endcan
                                            </td>
                                            <td>
                                                {{ $coordinator->name }} <span class="badge bg-light text-dark">{{ $coordinator->nip }}</span>
                                                {{ $coordinator->schools->name }} <span class="badge bg-light text-dark">{{ $coordinator->phone }}</span>
                                            </td>
                                            <td>{{ $coordinator->registered ? 'sudah' : 'belum'}}</td>
                                        </tr>
                                        @empty
                                            <div class="alert alert-info">Belum Mengusulkan</div>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
        crudDataTables('schoolcoordinator-table')
    </script>
@endpush
