@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Profil {{ $user->name }}
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">Edit</button> --}}
                        <div class="table-responsive">
                            <table class="table small-font table-sm" id="profile-table" role="grid">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <button type="button" data-id="{{ $user->id }}" data-jenis="edit" class="btn btn-primary btn-sm action"><i class="ti-pencil"></i> Edit</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nama Lengkap</td>
                                        <td>{{ $user->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        @role('mahasiswa')<td>NPM</td>@endrole
                                        @role('guru')<td>NIP</td>@endrole
                                        @role('dosen')<td>NIDN</td>@endrole
                                        <td>{{ $user->username ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>{{ $user->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        @role('guru')<td>Bidang Studi</td>@endrole
                                        @hasanyrole('mahasiswa|dosen')<td>Jurusan</td>@endhasanyrole
                                        <td>{{ $user->subjects->name ?? '-' }}</td>
                                    </tr>
                                    @role('mahasiswa')
                                    <tr>
                                        <td>Tempat Lahir</td>
                                        <td>{{ $user->birth_place ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Lahir</td>
                                        <td>{{ $user->birth_date ? $user->birth_date->format('d-m-Y') : '-' }}</td>
                                    </tr>
                                    @endrole
                                    <tr>
                                        <td>Alamat</td>
                                        <td>{{ $user->address ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor WA</td>
                                        <td>
                                            {{ $user->phone ?? '-' }}
                                            <br><span class="text-danger">pastikan sesuai format, contoh: 8512XXXXX</span>
                                        </td>
                                    </tr>
                                    @hasanyrole('guru|kepsek|korguru')
                                    <tr>
                                        <td>Provider</td>
                                        <td>{{ $user->provider ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status PNS</td>
                                        <td>{{ ($user->is_pns == 1 ? 'PNS' : 'nonPNS' ) ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Golongan</td>
                                        <td>{{ $user->golongan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>NPWP</td>
                                        <td>{{ $user->npwp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nomor Rekening</td>
                                        <td>{{ $user->nomor_rekening ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nama Bank</td>
                                        <td>{{ $user->bank ?? '-' }}</td>
                                    </tr>
                                    @endhasanyrole
                                </tbody>
                            </table>
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
    {{-- <script src="{{ asset('') }}vendor/select2/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('') }}vendor/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    {{-- {{ $dataTable->scripts() }} --}}
    <script src="{{ asset('') }}assets/js/crud2-datatables.js"></script>
    <script>
        crudDataTables('profiles','profile-table')
    </script>

@endpush
