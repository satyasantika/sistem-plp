@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Catatan Harian Kegiatan PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('diaryverifications.index',$plp_order) }}" class="btn btn-sm btn-outline-danger float-end">< kembali ke daftar mahasiswa</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer">
                                <table class="display dataTable no-footer" id="studentdiary-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th>Catatan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($diaries as $diary)
                                        <tr>
                                            <td>
                                                hari ke-{{ $diary->day_order ?? '' }} <span class="badge bg-secondary">{{ $diary->log_date ? $diary->log_date->format('d-m-Y') : '' }}</span>
                                                <br>{{ $diary->note ?? '' }}
                                            </td>
                                            <td>
                                                @if ($diary->verified == 1)
                                                    <span class="badge bg-success">sudah diverifikasi</span>
                                                    <span class="badge bg-light text-dark">{{ $diary->updated_at->format('Y-m-d') }}</span>
                                                @else
                                                    <span class="badge bg-light text-dark">belum diverifikasi</span>
                                                    <button type="button" data-id={{ $diary->id }} data-jenis="edit" class="btn btn-primary btn-sm mt-1 action">Verifikasi</button>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <div class="alert alert-info">Belum ada catatan</div>
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
    <script src="{{ asset('') }}assets/js/verifikasi-datatables.js"></script>
    <script>
        updateOnly('studentdiary-table')
    </script>
@endpush
