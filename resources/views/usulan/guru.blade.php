@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Usulan Guru Pamong
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @can('usulan/schoolteachers-create')
                        <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ Guru Pamong</button>
                        @endcan
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="schoolteacher-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Guru</th>
                                            <th scope="col">Persetujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($teachers as $teacher)
                                        <tr>
                                            <td class="text-center align-top">
                                                @can('usulan/schoolteachers-update')
                                                <button type="button" data-id="{{ $teacher->id }}" data-jenis="edit" class="btn btn-primary btn-sm action">Edit</button>
                                                @endcan
                                                @can('usulan/schoolteachers-delete')
                                                <button type="button" data-id="{{ $teacher->id }}" data-jenis="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>
                                                @endcan
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $teacher->subjects->name }}</span>
                                                {{ $teacher->name }} <span class="badge bg-light text-dark">{{ $teacher->nip }}</span><br>
                                                {{ $teacher->schools->name }} <span
                                                @class([
                                                    'badge',
                                                    'bg-light',
                                                    (isset($teacher->phone)) ? 'text-dark' : 'text-danger'
                                                ])>{{ $teacher->phone ?? 'no.WA belum ada, mohon diisi' }}</span>
                                            </td>
                                            <td class="text-center align-top">{{ $teacher->registered ? 'sudah' : 'belum'}}</td>
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
        crudDataTables('schoolteacher-table')
    </script>
@endpush
