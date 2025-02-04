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
                        Rekap Penilaian Mahasiswa
                    </div>
                    <div class="card-body">
                        <button type="button" class="btn btn-primary btn-sm mb-3 btn-add">+ forms</button>
                        <div class="table-responsive">
                            <div id="form-table_wrapper" class="dataTables_wrapper no-footer">
                                <div class="dataTables_length" id="form-table_length">
                                    <label>Show
                                    <select name="form-table_length" aria-controls="form-table" class="">
                                    <option value="10">10
                                    </option>
                                    <option value="25">25
                                    </option>
                                    <option value="50">50
                                    </option>
                                    <option value="100">100
                                    </option>
                                </select> entries
                                </label>
                                </div>

                                <div id="form-table_filter" class="dataTables_filter">
                                    <label>Search:
                                        <input type="search" class="" placeholder="" aria-controls="form-table">
                                    </label>
                                </div>

                            <div id="form-table_processing" class="dataTables_processing" style="display: none;">Processing...
                            </div>
                            <table class="display nowrap dataTable no-footer" id="form-table" role="grid" aria-describedby="form-table_info" style="width: 1608px;">
                                <thead>
                                <tr role="row">
                                <th title="" width="60" class="text-center sorting_disabled" rowspan="1" colspan="1" aria-label="" style="width: 50px;">
                            </th>
                                <th title="Id" class="sorting sorting_asc" tabindex="0" aria-controls="form-table" rowspan="1" colspan="1" style="width: 37px;" aria-sort="ascending" aria-label="Id: activate to sort column descending">Id
                            </th>
                                <th title="Name" class="sorting" tabindex="0" aria-controls="form-table" rowspan="1" colspan="1" style="width: 1125px;" aria-label="Name: activate to sort column ascending">Name
                            </th>
                                <th title="Type" class="sorting" tabindex="0" aria-controls="form-table" rowspan="1" colspan="1" style="width: 47px;" aria-label="Type: activate to sort column ascending">Type
                            </th>
                                <th title="Count" class="sorting" tabindex="0" aria-controls="form-table" rowspan="1" colspan="1" style="width: 50px;" aria-label="Count: activate to sort column ascending">Count
                            </th>
                                <th title="Max Score" class="sorting" tabindex="0" aria-controls="form-table" rowspan="1" colspan="1" style="width: 83px;" aria-label="Max Score: activate to sort column ascending">Max Score
                            </th>
                            </tr>
                            </thead>
                                <tbody>
                                <tr id="2022L1" class="odd">
                                <td class=" text-center">
                                <button type="button" data-id="2022L1" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022L1" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022L1
                                </td>
                                <td>lembar pengamatan kultur sekolah
                                </td>
                                <td>yes_no
                                </td>
                                <td>8
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022L2" class="even">
                                <td class=" text-center">
                                <button type="button" data-id="2022L2" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022L2" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022L2
                                </td>
                                <td>lembar pengamatan struktur organisasi dan tata kerja (SOTK)
                                </td>
                                <td>yes_no
                                </td>
                                <td>2
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022L3" class="odd">
                                <td class=" text-center">
                                <button type="button" data-id="2022L3" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022L3" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022L3
                                </td>
                                <td>lembar pengamatan kokurikuler dan ekstrakurikuler
                                </td>
                                <td>yes_no
                                </td>
                                <td>5
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N1" class="even">
                                <td class=" text-center">
                                <button type="button" data-id="2022N1" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N1" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N1
                                </td>
                                <td>format penilaian kompetensi kepribadian dan sosial
                                </td>
                                <td>skor_4
                                </td>
                                <td>8
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N2" class="odd">
                                <td class=" text-center">
                                <button type="button" data-id="2022N2" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N2" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N2
                                </td>
                                <td>format penilaian pelaporan PLP
                                </td>
                                <td>skor_40
                                </td>
                                <td>5
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N3" class="even">
                                <td class=" text-center">
                                <button type="button" data-id="2022N3" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N3" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N3
                                </td>
                                <td>format penilaian telaah kurikulum, strategi pembelajaran, sistem evaluasi, dan pemanfaatan TIK dalam pembelajaran/perangkat pembelajaran
                                </td>
                                <td>skor_4
                                </td>
                                <td>6
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N4" class="odd">
                                <td class=" text-center">
                                <button type="button" data-id="2022N4" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N4" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N4
                                </td>
                                <td>format penilaian kemampuan peserta PLP dalam membantu mengembangkan RPP
                                </td>
                                <td>skor_4
                                </td>
                                <td>4
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N5" class="even">
                                <td class=" text-center">
                                <button type="button" data-id="2022N5" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N5" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N5
                                </td>
                                <td>format penilaian latihan mengajar
                                </td>
                                <td>skor_4
                                </td>
                                <td>9
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N6" class="odd">
                                <td class=" text-center">
                                <button type="button" data-id="2022N6" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N6" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N6
                                </td>
                                <td>format penilaian perangkat pembelajaran
                                </td>
                                <td>skor_4
                                </td>
                                <td>6
                                </td>
                                <td>0
                                </td>
                            </tr>
                                <tr id="2022N7" class="even">
                                <td class=" text-center">
                                <button type="button" data-id="2022N7" data-jenis="edit" class="btn btn-primary btn-sm my-1 action">
                                <i class="ti-pencil">
                                </i>
                            </button>
                                <button type="button" data-id="2022N7" data-jenis="delete" class="btn btn-danger btn-sm my-1 action">
                                <i class="ti-trash">
                                </i>
                            </button>
                            </td>
                                <td class="sorting_1">2022N7
                                </td>
                                <td>format penilaian ujian mengajar
                                </td>
                                <td>skor_4
                                </td>
                                <td>9
                                </td>
                                <td>0
                                </td>
                            </tr>
                            </tbody>
                            </table>

                            <div class="dataTables_info" id="form-table_info" role="status" aria-live="polite">Showing 1 to 10 of 11 entries

                            </div>

                            <div class="dataTables_paginate paging_simple_numbers" id="form-table_paginate">
                                <a class="paginate_button previous disabled" aria-controls="form-table" data-dt-idx="0" tabindex="-1" id="form-table_previous">Previous
                                </a>
                                <span>
                                <a class="paginate_button current" aria-controls="form-table" data-dt-idx="1" tabindex="0">1
                                </a>
                                <a class="paginate_button " aria-controls="form-table" data-dt-idx="2" tabindex="0">2
                                </a>
                            </span>
                                <a class="paginate_button next" aria-controls="form-table" data-dt-idx="3" tabindex="0" id="form-table_next">Next
                                </a>
                            </div>
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
        crudDataTables('assessment-table')
    </script>
@endpush
