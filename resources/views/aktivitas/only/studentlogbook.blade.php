@extends('layouts.master')

@push('css')
    <link href="{{ asset('') }}vendor/datatables.net-dt/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="{{ asset('') }}vendor/datatables.net-responsive-dt/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    <style>
        .owner-card {
            border: 1px solid rgba(76, 107, 149, 0.22);
            border-radius: 14px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(246, 250, 255, 0.95));
            padding: 16px;
            margin-bottom: 16px;
        }

        .owner-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .owner-title {
            font-size: 0.95rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            margin: 0;
            color: #294166;
        }

        .owner-name {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            color: #1f2f49;
        }

        .owner-meta {
            color: #5a6f90;
            font-size: 0.82rem;
            margin: 2px 0 0;
        }

        .owner-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .owner-item {
            border: 1px solid rgba(89, 120, 162, 0.2);
            border-radius: 10px;
            padding: 8px 10px;
            background: rgba(255, 255, 255, 0.68);
        }

        .owner-item-label {
            display: block;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.45px;
            color: #6a7d99;
            margin-bottom: 2px;
        }

        .owner-item-value {
            font-size: 0.86rem;
            font-weight: 600;
            color: #1f2f49;
        }

        .logbook-table-wrap {
            border: 1px solid rgba(89, 120, 162, 0.22);
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
        }

        #studentlogbook-table thead th {
            background: linear-gradient(135deg, #eef4ff, #f6f9ff);
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #526789;
            border-bottom: 1px solid rgba(89, 120, 162, 0.28);
        }

        #studentlogbook-table tbody td {
            vertical-align: top;
            padding-top: 14px;
            padding-bottom: 14px;
        }

        .log-note {
            color: #2b3e5f;
            line-height: 1.5;
        }

        .log-meta {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }

        .summary-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-modern-back {
            border: 1px solid rgba(214, 64, 82, 0.35);
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.42rem 0.84rem;
            letter-spacing: 0.3px;
        }

        .btn-modern-verify {
            border: none;
            border-radius: 999px;
            padding: 0.36rem 0.76rem;
            font-size: 0.73rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #fff;
            background: linear-gradient(135deg, #2476f3, #1759c5);
            box-shadow: 0 6px 14px rgba(36, 118, 243, 0.27);
            transition: transform 0.18s ease, box-shadow 0.22s ease, filter 0.2s ease;
        }

        .btn-modern-verify:hover,
        .btn-modern-verify:focus {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 9px 16px rgba(36, 118, 243, 0.34);
            filter: saturate(1.08);
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

        .badge-modern-neutral {
            background: rgba(108, 117, 125, 0.14);
            color: #4e5965;
            border-color: rgba(108, 117, 125, 0.24);
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

        .badge-modern-soft {
            background: rgba(130, 146, 166, 0.16);
            color: #4f6076;
            border-color: rgba(130, 146, 166, 0.28);
        }

        body.dark .owner-card {
            border-color: rgba(160, 188, 226, 0.24);
            background: linear-gradient(155deg, rgba(25, 38, 58, 0.95), rgba(18, 31, 50, 0.95));
        }

        body.dark .owner-title,
        body.dark .owner-name,
        body.dark .owner-item-value,
        body.dark .log-note {
            color: #dce8ff;
        }

        body.dark .owner-meta,
        body.dark .owner-item-label {
            color: #a9bddf;
        }

        body.dark .owner-item {
            border-color: rgba(160, 188, 226, 0.24);
            background: rgba(25, 38, 58, 0.52);
        }

        body.dark .logbook-table-wrap {
            border-color: rgba(160, 188, 226, 0.24);
            background: rgba(17, 28, 45, 0.75);
        }

        body.dark .btn-modern-back {
            border-color: rgba(238, 128, 142, 0.45);
            color: #ffd4da;
        }

        body.dark .btn-modern-verify {
            background: linear-gradient(135deg, #3a8cff, #2369db);
            box-shadow: 0 6px 14px rgba(58, 140, 255, 0.3);
        }

        body.dark .btn-modern-verify:hover,
        body.dark .btn-modern-verify:focus {
            box-shadow: 0 9px 16px rgba(58, 140, 255, 0.38);
        }

        body.dark .badge-modern-primary {
            background: rgba(58, 140, 255, 0.22);
            color: #c5dcff;
            border-color: rgba(58, 140, 255, 0.36);
        }

        body.dark .badge-modern-neutral {
            background: rgba(130, 146, 166, 0.22);
            color: #d3deeb;
            border-color: rgba(130, 146, 166, 0.36);
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

        body.dark .badge-modern-soft {
            background: rgba(130, 146, 166, 0.22);
            color: #d3deeb;
            border-color: rgba(130, 146, 166, 0.36);
        }

        body.dark #studentlogbook-table thead th {
            background: linear-gradient(135deg, rgba(34, 49, 73, 0.92), rgba(25, 39, 61, 0.92));
            color: #b9cced;
            border-bottom-color: rgba(160, 188, 226, 0.24);
        }

        @media (max-width: 768px) {
            .owner-grid {
                grid-template-columns: 1fr;
            }

            .owner-head {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
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
                        <a href="{{ route('diaryverifications.only.index') }}" class="btn btn-sm btn-outline-danger btn-modern-back float-end">< kembali ke daftar mahasiswa</a>
                    </div>
                    <div class="card-body">
                        @php
                            $totalDiary = $diaries->count();
                            $totalVerified = $diaries->where('verified', 1)->count();
                            $totalUnverified = $totalDiary - $totalVerified;
                        @endphp

                        <div class="owner-card">
                            <div class="owner-head">
                                <div>
                                    <h6 class="owner-title">Identitas Pemilik Catatan Harian</h6>
                                    <p class="owner-name">{{ $map->students->name ?? '-' }}</p>
                                    <p class="owner-meta">{{ $map->students->username ?? '-' }}</p>
                                </div>
                                <div class="summary-badges">
                                    <span class="badge-modern badge-modern-success">Terverifikasi {{ $totalVerified }}</span>
                                    <span class="badge-modern badge-modern-warning">Belum {{ $totalUnverified }}</span>
                                    <span class="badge-modern badge-modern-neutral">Total {{ $totalDiary }}</span>
                                </div>
                            </div>
                            <div class="owner-grid">
                                <div class="owner-item">
                                    <span class="owner-item-label">Sekolah</span>
                                    <span class="owner-item-value">{{ $map->schools->name ?? '-' }}</span>
                                </div>
                                <div class="owner-item">
                                    <span class="owner-item-label">Program Studi</span>
                                    <span class="owner-item-value">{{ $map->subjects->name ?? '-' }}</span>
                                </div>
                                <div class="owner-item">
                                    <span class="owner-item-label">Dosen Pamong</span>
                                    <span class="owner-item-value">{{ $map->lectures->name ?? '-' }}</span>
                                </div>
                                <div class="owner-item">
                                    <span class="owner-item-label">Guru Pamong</span>
                                    <span class="owner-item-value">{{ $map->teachers->name ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div id="role-table_wrapper" class="dataTables_wrapper no-footer logbook-table-wrap">
                                <table class="display dataTable no-footer" id="studentlogbook-table" role="grid">
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
                                                <div class="log-meta">
                                                    <span class="badge-modern badge-modern-primary">Hari ke-{{ $diary->day_order ?? '' }}</span>
                                                    <span class="badge-modern badge-modern-neutral">{{ $diary->log_date ? $diary->log_date->format('d-m-Y') : '' }}</span>
                                                </div>
                                                <div class="log-note">{{ $diary->note ?? '' }}</div>
                                            </td>
                                            <td class="align-top">
                                                @if ($diary->verified == 1)
                                                    <span class="badge-modern badge-modern-success">sudah diverifikasi</span>
                                                    <span class="badge-modern badge-modern-soft">{{ $diary->updated_at->format('Y-m-d') }}</span>
                                                @else
                                                    <span class="badge-modern badge-modern-soft">belum diverifikasi</span>
                                                    <button type="button" data-id={{ $diary->id }} data-jenis="edit" class="btn btn-sm mt-1 action btn-modern-verify">Verifikasi</button>
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
    <script>
        var updateOnly = function(table) {
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

            $(`#${table}`)
                .off('click.verifikasiAction', '.action')
                .on('click.verifikasiAction', '.action', function() {
                    let data = $(this).data();
                    let id = data.id;

                    $modal.find('.modal-dialog').html(`
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Verifikasi Catatan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Catatan ini akan diverifikasi. Lanjutkan?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn btn-primary" id="confirmVerifikasi">Ya, verifikasi</button>
                            </div>
                        </div>
                    `);

                    $modal
                        .off('click.verifikasiConfirm', '#confirmVerifikasi')
                        .on('click.verifikasiConfirm', '#confirmVerifikasi', function() {
                            const basePath = window.location.pathname.replace(/\/+$/, '');

                            $.ajax({
                                method: 'PUT',
                                url: `${basePath}/${id}`,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(response) {
                                    hideModal();
                                    $(`#${table}`).load(document.URL + ` #${table}`, function() {
                                        updateOnly(table);
                                    });
                                    iziToast.success({
                                        title: 'OK!',
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                },
                                error: function() {
                                    iziToast.error({
                                        title: 'Error',
                                        message: 'Verifikasi gagal diproses.',
                                        position: 'topRight'
                                    });
                                }
                            });
                        });

                    showModal();
                });
        };
    </script>
    <script>
        updateOnly('studentlogbook-table')
    </script>
@endpush
