@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Cleaning Data Assessment
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="data-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th scope="col">#</th>
                                            <th scope="col">Nama Mahasiswa</th>
                                            <th scope="col">PLP-Form-Order</th>
                                            <th scope="col">Assessor</th>
                                            <th scope="col">Grade</th>
                                            <th scope="col">Update</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($assessments as $assessment)
                                        <tr>
                                            <td class="text-center align-top">
                                                {{-- @can('usulan/schoolassessments-update')
                                                <button type="button" data-id="{{ $assessment->id }}" data-jenis="edit" class="btn btn-primary btn-sm action">Edit</button>
                                                @endcan
                                                @can('usulan/schoolassessments-delete') --}}
                                                <button type="button" data-id="{{ $assessment->id }}" data-jenis="delete" class="btn btn-danger btn-sm action"><i class="ti-trash"></i></button>
                                                {{-- @endcan --}}
                                            </td>
                                            <td>
                                                {{ $assessment->maps->students->name }}
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <span class="badge bg-warning">{{ $assessment->plp_order }} </span>
                                                    {{ $assessment->form_id }}
                                                    <span class="badge bg-primary">{{ $assessment->form_order }}</span>
                                                </span><br>
                                            </td>
                                            <td>{{ $assessment->assessor }}</td>
                                            <td>{{ $assessment->grade }}</td>
                                            <td>{{ $assessment->updated_at }}</td>
                                        </tr>
                                        @empty
                                            <div class="alert alert-info">Penilaian duplikat belum ada</div>
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
        crudDataTables('data-table')
    </script>
@endpush
