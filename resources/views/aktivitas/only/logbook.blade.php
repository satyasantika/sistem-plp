@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    <style>
        .identity-card {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            background: linear-gradient(155deg, rgba(255, 255, 255, 0.96), rgba(245, 250, 255, 0.96));
            padding: 14px;
            margin-bottom: 16px;
        }

        .identity-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .identity-title {
            font-size: 0.92rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #5f7394;
            margin: 0;
            font-weight: 700;
        }

        .identity-name {
            margin: 0;
            font-size: 1.06rem;
            font-weight: 700;
            color: #233754;
        }

        .identity-meta {
            margin: 2px 0 0;
            color: #6a7f9e;
            font-size: 0.83rem;
        }

        .identity-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .identity-item {
            border: 1px solid rgba(89, 120, 162, 0.2);
            border-radius: 10px;
            padding: 8px 10px;
            background: rgba(255, 255, 255, 0.7);
        }

        .identity-item-label {
            display: block;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            color: #6a7d99;
            margin-bottom: 2px;
        }

        .identity-item-value {
            font-size: 0.86rem;
            font-weight: 600;
            color: #1f2f49;
        }

        .badge-modern {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 0.33rem 0.62rem;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.28px;
            border: 1px solid transparent;
        }

        .badge-modern-primary {
            background: rgba(36, 118, 243, 0.14);
            color: #1d5fbe;
            border-color: rgba(36, 118, 243, 0.26);
        }

        .badge-modern-success {
            background: rgba(24, 151, 105, 0.16);
            color: #0f7e59;
            border-color: rgba(24, 151, 105, 0.28);
        }

        .badge-modern-warning {
            background: rgba(245, 158, 11, 0.18);
            color: #96600a;
            border-color: rgba(245, 158, 11, 0.28);
        }

        .badge-modern-danger {
            background: rgba(220, 53, 69, 0.16);
            color: #a32836;
            border-color: rgba(220, 53, 69, 0.28);
        }

        .badge-modern-neutral {
            background: rgba(108, 117, 125, 0.14);
            color: #4e5965;
            border-color: rgba(108, 117, 125, 0.24);
        }

        .btn-modern {
            border-radius: 999px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            padding: 0.42rem 0.86rem;
            border: 1px solid transparent;
            transition: transform 0.18s ease, box-shadow 0.22s ease, filter 0.2s ease;
        }

        .btn-modern:hover,
        .btn-modern:focus {
            transform: translateY(-1px);
            filter: saturate(1.08);
        }

        .btn-modern-primary {
            color: #fff;
            background: linear-gradient(135deg, #2476f3, #1759c5);
            box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
        }

        .btn-modern-success {
            color: #fff;
            background: linear-gradient(135deg, #12a36f, #0b7f57);
            box-shadow: 0 6px 14px rgba(18, 163, 111, 0.27);
        }

        .btn-modern-danger {
            color: #fff;
            background: linear-gradient(135deg, #de4f5f, #bf2f46);
            box-shadow: 0 6px 14px rgba(222, 79, 95, 0.24);
        }

        .btn-modern-outline {
            border-color: rgba(74, 105, 148, 0.34);
            color: #264063;
            background: rgba(255, 255, 255, 0.62);
        }

        .logbook-wrap {
            border: 1px solid rgba(82, 112, 154, 0.22);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        #studentdiary-table thead th {
            background: linear-gradient(135deg, #edf4ff, #f6f9ff);
            border-bottom: 1px solid rgba(82, 112, 154, 0.22);
            color: #526789;
            text-transform: uppercase;
            letter-spacing: 0.48px;
            font-size: 0.75rem;
        }

        #studentdiary-table tbody td {
            vertical-align: top;
            padding-top: 12px;
            padding-bottom: 12px;
        }

        .summary-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        body.dark .identity-card {
            border-color: rgba(157, 185, 224, 0.24);
            background: linear-gradient(155deg, rgba(24, 37, 57, 0.95), rgba(17, 30, 48, 0.95));
        }

        body.dark .identity-title,
        body.dark .identity-meta,
        body.dark .identity-item-label {
            color: #a8bddd;
        }

        body.dark .identity-name,
        body.dark .identity-item-value {
            color: #e2ecff;
        }

        body.dark .identity-item {
            border-color: rgba(160, 188, 226, 0.24);
            background: rgba(25, 38, 58, 0.52);
        }

        body.dark .logbook-wrap {
            border-color: rgba(157, 185, 224, 0.24);
            background: rgba(15, 26, 42, 0.8);
        }

        body.dark #studentdiary-table thead th {
            background: linear-gradient(135deg, rgba(34, 49, 73, 0.92), rgba(25, 39, 61, 0.92));
            color: #b8cbea;
            border-bottom-color: rgba(157, 185, 224, 0.24);
        }

        body.dark .badge-modern-primary {
            background: rgba(58, 140, 255, 0.22);
            color: #c5dcff;
            border-color: rgba(58, 140, 255, 0.36);
        }

        body.dark .badge-modern-success {
            background: rgba(24, 151, 105, 0.2);
            color: #bdeedc;
            border-color: rgba(24, 151, 105, 0.34);
        }

        body.dark .badge-modern-warning {
            background: rgba(245, 158, 11, 0.22);
            color: #ffe6b5;
            border-color: rgba(245, 158, 11, 0.36);
        }

        body.dark .badge-modern-danger {
            background: rgba(220, 53, 69, 0.22);
            color: #ffc7cd;
            border-color: rgba(220, 53, 69, 0.36);
        }

        body.dark .badge-modern-neutral {
            background: rgba(130, 146, 166, 0.22);
            color: #d3deeb;
            border-color: rgba(130, 146, 166, 0.36);
        }

        body.dark .btn-modern-outline {
            border-color: rgba(146, 182, 230, 0.45);
            color: #cfe3ff;
            background: rgba(43, 66, 103, 0.36);
        }

        @media (max-width: 768px) {
            .identity-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
@section('content')
<div class="main-content">
    <div class="title">
        Catatan Harian PLP
    </div>
    <div class="content-wrapper">
        <div class="row same-height">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('dashboard') }}" class="btn btn-modern btn-modern-outline float-end">Kembali ke dashboard</a>
                    </div>
                    <div class="card-body">
                        @php
                            $totalDiary = $diaries->count();
                            $totalVerified = $diaries->where('verified', 1)->count();
                            $totalUnverified = $totalDiary - $totalVerified;
                        @endphp

                        <div class="identity-card">
                            <div class="identity-head">
                                <div>
                                    <p class="identity-title">Identitas Mahasiswa</p>
                                    <p class="identity-name">{{ $map->students->name ?? '-' }}</p>
                                    <p class="identity-meta">{{ $map->students->username ?? '-' }}</p>
                                </div>
                                <div class="summary-badges">
                                    <span class="badge-modern badge-modern-success">Sudah {{ $totalVerified }}</span>
                                    <span class="badge-modern badge-modern-warning">Belum {{ $totalUnverified }}</span>
                                    <span class="badge-modern badge-modern-neutral">Total {{ $totalDiary }}</span>
                                </div>
                            </div>
                            <div class="identity-grid">
                                <div class="identity-item">
                                    <span class="identity-item-label">Sekolah</span>
                                    <span class="identity-item-value">{{ $map->schools->name ?? '-' }}</span>
                                </div>
                                <div class="identity-item">
                                    <span class="identity-item-label">Program Studi</span>
                                    <span class="identity-item-value">{{ $map->subjects->name ?? '-' }}</span>
                                </div>
                                <div class="identity-item">
                                    <span class="identity-item-label">Dosen Pamong</span>
                                    <span class="identity-item-value">{{ $map->lectures->name ?? '-' }}</span>
                                </div>
                                <div class="identity-item">
                                    <span class="identity-item-label">Guru Pamong</span>
                                    <span class="identity-item-value">{{ $map->teachers->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <button type="button" class="btn btn-modern btn-modern-primary btn-add mb-3">+ Logbook</button>
                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer logbook-wrap">
                                <table class="display dataTable no-footer" id="studentdiary-table" role="grid">
                                    <thead>
                                        <tr role="row">
                                            <th></th>
                                            <th>Waktu</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($diaries as $diary)
                                        <tr>
                                            <td class="text-center align-top">
                                                @canany(['aktivitas/studentdiaries/plp-update'])
                                                @if ($diary->verified == 0)
                                                <button
                                                    type="button"
                                                    data-id={{ $diary->id }}
                                                    data-jenis="edit"
                                                    class="btn btn-modern btn-modern-primary btn-sm mb-2 action">
                                                    <i class="ti-pencil"></i>
                                                </button>
                                                <br>
                                                <button
                                                    type="button"
                                                    data-id={{ $diary->id }}
                                                    data-jenis="delete"
                                                    class="btn btn-modern btn-modern-danger btn-sm action">
                                                    <i class="ti-trash"></i>
                                                </button>
                                                @endif
                                                @endcanany
                                            </td>

                                            <td class="align-top">
                                                hari ke-{{ $diary->day_order ?? '' }} <br>
                                                <span class="badge-modern badge-modern-primary">{{ $diary->log_date ? $diary->log_date->format('d-m-Y') : '' }}</span>
                                                <br>
                                                <span class="badge-modern {{ $diary->verified == 1 ? 'badge-modern-success' : 'badge-modern-danger' }}">{{ $diary->verified == 1 ? 'SUDAH' : 'BELUM' }} diverifikasi</span>
                                            </td>
                                            <td>{{ $diary->note ?? '' }}</td>
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
    <script>
        var initOnlyLogbookCrud = function(table) {
            const baseUrl = "{{ route('studentdiaries.only.index') }}";
            const createUrl = "{{ route('studentdiaries.only.create') }}";
            const $modal = $('#modalAction');
            const modalElement = document.getElementById('modalAction');

            const showModal = () => {
                try {
                    if (window.bootstrap && window.bootstrap.Modal && modalElement) {
                        window.bootstrap.Modal.getOrCreateInstance(modalElement).show();
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                try {
                    if (typeof $modal.modal === 'function') {
                        $modal.modal('show');
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                $modal.addClass('show').css('display', 'block').attr('aria-modal', 'true').removeAttr('aria-hidden');
                if (!$('.modal-backdrop').length) {
                    $('<div class="modal-backdrop fade show"></div>').appendTo(document.body);
                }
                $('body').addClass('modal-open');
            };

            const hideModal = () => {
                try {
                    if (window.bootstrap && window.bootstrap.Modal && modalElement) {
                        window.bootstrap.Modal.getOrCreateInstance(modalElement).hide();
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                try {
                    if (typeof $modal.modal === 'function') {
                        $modal.modal('hide');
                        return;
                    }
                } catch (e) {
                    // fallback below
                }

                $modal.removeClass('show').css('display', 'none').attr('aria-hidden', 'true').removeAttr('aria-modal');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            };

            const reloadTable = () => {
                $(`#${table}`).load(`${baseUrl} #${table}`);
            };

            const bindStore = () => {
                $(document)
                    .off('submit.onlyLogbookStore', '#formAction')
                    .on('submit.onlyLogbookStore', '#formAction', function(e) {
                        e.preventDefault();
                        const _form = this;
                        const formData = new FormData(_form);
                        const url = this.getAttribute('action');

                        $('.text-danger.text-small').remove();

                        $.ajax({
                            method: 'POST',
                            url,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                reloadTable();
                                hideModal();
                                iziToast.success({
                                    title: 'Saved!',
                                    message: response.message,
                                    position: 'topRight',
                                });
                            },
                            error: function(response) {
                                const errors = response.responseJSON?.errors;

                                if (errors) {
                                    for (const [key, value] of Object.entries(errors)) {
                                        $(`[name='${key}']`).parent().append(`<span class='text-danger text-small'>${value}</span>`);
                                    }
                                    return;
                                }

                                iziToast.error({
                                    title: 'Error',
                                    message: 'Gagal menyimpan data logbook.',
                                    position: 'topRight',
                                });
                            },
                        });
                    });
            };

            window.openOnlyLogbookCreate = function() {
                $.ajax({
                    method: 'GET',
                    url: createUrl,
                    success: function(response) {
                        $modal.find('.modal-dialog').html(response);
                        showModal();
                        bindStore();
                    },
                    error: function() {
                        iziToast.error({
                            title: 'Error',
                            message: 'Form tambah logbook tidak bisa dimuat.',
                            position: 'topRight',
                        });
                    },
                });
            };

            $(document)
                .off('click.onlyLogbookAdd', '.btn-add')
                .on('click.onlyLogbookAdd', '.btn-add', function(e) {
                    e.preventDefault();
                    window.openOnlyLogbookCreate();
                });

            $(`#${table}`)
                .off('click.onlyLogbookAction', '.action')
                .on('click.onlyLogbookAction', '.action', function() {
                    let data = $(this).data();
                    let id = data.id;
                    let jenis = data.jenis;

                    if (jenis === 'delete') {
                        Swal.fire({
                            title: 'Hapus permanen?',
                            text: 'Data sepenuhnya akan terhapus dari sistem!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    method: 'DELETE',
                                    url: `${baseUrl}/${id}`,
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    },
                                    success: function(response) {
                                        reloadTable();
                                        iziToast.warning({
                                            title: 'Deleted!',
                                            message: response.message,
                                            position: 'topRight',
                                        });
                                    },
                                });
                            }
                        });
                        return;
                    }

                    $.ajax({
                        method: 'GET',
                        url: `${baseUrl}/${id}/edit`,
                        success: function(response) {
                            $modal.find('.modal-dialog').html(response);
                            showModal();
                            bindStore();
                        },
                        error: function() {
                            iziToast.error({
                                title: 'Error',
                                message: 'Form edit logbook tidak bisa dimuat.',
                                position: 'topRight',
                            });
                        },
                    });
                });
        };
    </script>

    <script>
        initOnlyLogbookCrud('studentdiary-table')
    </script>
@endpush
