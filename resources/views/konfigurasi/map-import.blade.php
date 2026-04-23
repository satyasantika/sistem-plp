@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
@endpush

@section('content')
<div class="main-content">
    <div class="title">
        Konfigurasi Maps - {{ $title }}
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <div>
                                <h5 class="mb-1">{{ $title }}</h5>
                                <div class="text-muted small">Upload file Excel, review preview data map, lalu commit hanya baris baru yang dipilih.</div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('maps.index') }}" class="btn btn-outline-secondary btn-sm">Kembali ke Maps</a>
                                <a href="{{ route('maps.importtemplate') }}" class="btn btn-outline-primary btn-sm">Download Template Maps</a>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('maps.importpreview') }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end mb-3">
                            @csrf
                            <div class="col-md-8">
                                <label class="form-label">Pilih file excel</label>
                                <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
                            </div>
                            <div class="col-md-4 d-grid">
                                <button type="submit" class="btn btn-primary">Upload dan Preview</button>
                            </div>
                        </form>

                        <div class="alert alert-light border small mb-0">
                            Format kolom Excel: <strong>nim_mahasiswa, nidn_dosen, nip_guru, nama_sekolah, mapel, year</strong>.
                            Data baru akan ditandai badge <strong>Baru</strong> dan dapat dipilih untuk commit.
                        </div>
                    </div>
                </div>

                @if ($draft)
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                <div>
                                    <h5 class="mb-1">Preview Import Maps</h5>
                                    <div class="small text-muted">
                                        File: <strong>{{ $draft['original_name'] }}</strong>
                                        <span class="mx-2">|</span>
                                        Upload: {{ $draft['uploaded_at'] }}
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 small">
                                    <span class="badge bg-dark">Total {{ $previewSummary['total'] }}</span>
                                    <span class="badge bg-success">Baru {{ $previewSummary['selectable'] }}</span>
                                    <span class="badge bg-secondary">Tertahan {{ $previewSummary['blocked'] }}</span>
                                    <span class="badge bg-primary" id="selectedCountBadge">Dipilih 0</span>
                                </div>
                            </div>

                            <form action="{{ route('maps.importcommit') }}" method="POST" id="commitImportForm">
                                @csrf

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                                    <div class="small text-muted">Centang hanya data map dengan status <strong>Baru</strong> untuk dicommit.</div>
                                    <button type="submit" class="btn btn-success btn-sm" id="commitImportButton" disabled>Commit Data Terpilih</button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered w-100" id="map-import-preview-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px;"><input type="checkbox" id="selectAllPreviewRows"></th>
                                                <th>Baris</th>
                                                <th>Status</th>
                                                <th>Mahasiswa</th>
                                                <th>Dosen</th>
                                                <th>Guru</th>
                                                <th>Sekolah</th>
                                                <th>Mapel</th>
                                                <th>Tahun</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($previewRows as $row)
                                                <tr class="{{ $row['is_selectable'] ? 'table-success' : '' }}">
                                                    <td class="text-center">
                                                        @if ($row['is_selectable'])
                                                            <input type="checkbox" name="selected_rows[]" value="{{ $row['row_number'] }}" class="preview-row-checkbox">
                                                        @else
                                                            <input type="checkbox" disabled>
                                                        @endif
                                                    </td>
                                                    <td>{{ $row['row_number'] }}</td>
                                                    <td><span class="badge bg-{{ $row['status_class'] }}">{{ $row['status_label'] }}</span></td>
                                                    <td>{{ $row['nim_mahasiswa'] }} - {{ $row['student_name'] }}</td>
                                                    <td>{{ $row['nidn_dosen'] }} - {{ $row['lecture_name'] }}</td>
                                                    <td>{{ $row['nip_guru'] }} - {{ $row['teacher_name'] }}</td>
                                                    <td>{{ $row['nama_sekolah'] }}</td>
                                                    <td>{{ $row['mapel'] }}</td>
                                                    <td>{{ $row['year'] }}</td>
                                                    <td>
                                                        @if ($row['notes'] === [])
                                                            <span class="text-success small">Data map baru siap dicommit.</span>
                                                        @else
                                                            <ul class="mb-0 ps-3 small">
                                                                @foreach ($row['notes'] as $note)
                                                                    <li>{{ $note }}</li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('') }}vendor/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        $(function() {
            const previewTable = $('#map-import-preview-table')

            if (!previewTable.length) {
                return
            }

            const dataTable = previewTable.DataTable({
                responsive: true,
                pageLength: 25,
                order: [[1, 'asc']],
                columnDefs: [
                    { orderable: false, targets: [0, 9] }
                ]
            })

            const selectAll = $('#selectAllPreviewRows')
            const selectedCountBadge = $('#selectedCountBadge')
            const commitButton = $('#commitImportButton')

            function updateSelectionState() {
                const checkedCount = $('.preview-row-checkbox:checked').length
                const totalVisible = dataTable.rows({ page: 'current', search: 'applied' }).nodes().to$().find('.preview-row-checkbox').length
                const checkedVisible = dataTable.rows({ page: 'current', search: 'applied' }).nodes().to$().find('.preview-row-checkbox:checked').length

                selectedCountBadge.text(`Dipilih ${checkedCount}`)
                commitButton.prop('disabled', checkedCount === 0)
                selectAll.prop('checked', totalVisible > 0 && totalVisible === checkedVisible)
            }

            selectAll.on('change', function() {
                const shouldCheck = $(this).is(':checked')
                dataTable.rows({ page: 'current', search: 'applied' }).nodes().to$().find('.preview-row-checkbox').prop('checked', shouldCheck)
                updateSelectionState()
            })

            previewTable.on('change', '.preview-row-checkbox', function() {
                updateSelectionState()
            })

            dataTable.on('draw', function() {
                updateSelectionState()
            })

            updateSelectionState()
        })
    </script>
@endpush
