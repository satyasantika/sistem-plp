@extends('layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('') }}vendor/izitoast/dist/css/iziToast.min.css">
    <style>
        /* ===== PROFILE CARD ===== */
        .prof-wrap {
            max-width: 720px;
            margin: 0 auto;
        }
        .prof-card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            overflow: hidden;
        }
        .prof-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f0f0f0;
        }
        .prof-avatar {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg,#4f8ef7,#2c5fe0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: #fff;
            flex-shrink: 0;
        }
        .prof-header-info {
            flex: 1;
            min-width: 0;
        }
        .prof-header-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .prof-header-role {
            font-size: .8rem;
            color: #6c757d;
            text-transform: capitalize;
        }
        .prof-edit-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            font-size: .82rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            background: #4f8ef7;
            color: #fff;
            cursor: pointer;
            transition: background .18s, transform .12s;
            white-space: nowrap;
        }
        .prof-edit-btn:hover {
            background: #2c5fe0;
            transform: translateY(-1px);
        }
        .prof-body {
            padding: 20px 24px 24px;
        }
        .prof-section-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #9ca3af;
            margin: 0 0 10px;
        }
        .prof-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 24px;
            margin-bottom: 18px;
        }
        @media (max-width: 540px) {
            .prof-grid { grid-template-columns: 1fr; }
        }
        .prof-field {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .prof-field-label {
            font-size: .72rem;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: .05em;
        }
        .prof-field-value {
            font-size: .92rem;
            color: #1a202c;
            font-weight: 500;
            word-break: break-word;
        }
        .prof-field-hint {
            font-size: .72rem;
            color: #ef4444;
            margin-top: 2px;
        }
        .prof-divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 16px 0;
        }

        /* ===== DARK MODE ===== */
        body.dark .prof-card {
            background: #1e2535;
            box-shadow: 0 2px 14px rgba(0,0,0,.3);
        }
        body.dark .prof-header {
            border-color: #2d3748;
        }
        body.dark .prof-header-name { color: #e2e8f0; }
        body.dark .prof-header-role { color: #94a3b8; }
        body.dark .prof-section-label { color: #64748b; }
        body.dark .prof-field-label { color: #64748b; }
        body.dark .prof-field-value { color: #e2e8f0; }
        body.dark .prof-divider { border-color: #2d3748; }
    </style>
@endpush

@section('content')
<div class="main-content">
    <div class="title">Profil Saya</div>
    <div class="content-wrapper">
        <div class="prof-wrap" id="profile-table">
            <div class="prof-card">

                {{-- HEADER --}}
                <div class="prof-header">
                    <div class="prof-avatar">
                        <i class="ti-user"></i>
                    </div>
                    <div class="prof-header-info">
                        <p class="prof-header-name">{{ $user->name }}</p>
                        <span class="prof-header-role">
                            @role('mahasiswa')Mahasiswa @endrole
                            @role('dosen')Dosen Pembimbing @endrole
                            @role('guru')Guru Pamong @endrole
                            @role('kepsek')Kepala Sekolah @endrole
                            @role('korguru')Koordinator Guru @endrole
                            @role('admin')Administrator @endrole
                        </span>
                    </div>
                    <button type="button"
                        class="prof-edit-btn"
                        id="btnEditProfile"
                        data-edit-url="{{ route('profiles.edit', $user->id) }}">
                        <i class="ti-pencil"></i> Edit Profil
                    </button>
                </div>

                {{-- BODY --}}
                <div class="prof-body">

                    <p class="prof-section-label">Informasi Akun</p>
                    <div class="prof-grid">
                        <div class="prof-field">
                            <span class="prof-field-label">Nama Lengkap</span>
                            <span class="prof-field-value">{{ $user->name ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">
                                @role('mahasiswa')NPM @endrole
                                @role('guru')NIP @endrole
                                @role('dosen')NIDN @endrole
                                @hasanyrole('kepsek|korguru|admin')Username @endhasanyrole
                            </span>
                            <span class="prof-field-value">{{ $user->username ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Email</span>
                            <span class="prof-field-value">{{ $user->email ?? '-' }}</span>
                        </div>
                        @hasanyrole('mahasiswa|dosen|guru')
                        <div class="prof-field">
                            <span class="prof-field-label">
                                @role('guru')Bidang Studi @endrole
                                @hasanyrole('mahasiswa|dosen')Jurusan @endhasanyrole
                            </span>
                            <span class="prof-field-value">{{ $user->subjects->name ?? '-' }}</span>
                        </div>
                        @endhasanyrole
                    </div>

                    <hr class="prof-divider">
                    <p class="prof-section-label">Informasi Pribadi</p>
                    <div class="prof-grid">
                        @role('mahasiswa')
                        <div class="prof-field">
                            <span class="prof-field-label">Tempat Lahir</span>
                            <span class="prof-field-value">{{ $user->birth_place ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Tanggal Lahir</span>
                            <span class="prof-field-value">{{ $user->birth_date ? $user->birth_date->format('d-m-Y') : '-' }}</span>
                        </div>
                        @endrole
                        <div class="prof-field">
                            <span class="prof-field-label">Gender</span>
                            <span class="prof-field-value">
                                @if($user->gender == 'L') Laki-laki
                                @elseif($user->gender == 'P') Perempuan
                                @else - @endif
                            </span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Alamat</span>
                            <span class="prof-field-value">{{ $user->address ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Nomor WA</span>
                            <span class="prof-field-value">
                                {{ $user->phone ?? '-' }}
                                @if(!$user->phone)
                                <span class="prof-field-hint">Belum diisi — format: 8512XXXXX</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    @hasanyrole('guru|kepsek|korguru')
                    <hr class="prof-divider">
                    <p class="prof-section-label">Data Kepegawaian &amp; Bank</p>
                    <div class="prof-grid">
                        <div class="prof-field">
                            <span class="prof-field-label">Provider</span>
                            <span class="prof-field-value">{{ $user->provider ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Status PNS</span>
                            <span class="prof-field-value">
                                @if($user->is_pns == 0) nonPNS
                                @elseif($user->is_pns == 1) PNS
                                @elseif($user->is_pns == 2) PPPK
                                @else - @endif
                            </span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Golongan</span>
                            <span class="prof-field-value">{{ $user->golongan ? 'Golongan '.$user->golongan : '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">NPWP</span>
                            <span class="prof-field-value">{{ $user->npwp ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Nama Bank</span>
                            <span class="prof-field-value">{{ $user->bank ?? '-' }}</span>
                        </div>
                        <div class="prof-field">
                            <span class="prof-field-label">Nomor Rekening</span>
                            <span class="prof-field-value">{{ $user->nomor_rekening ?? '-' }}</span>
                        </div>
                    </div>
                    @endhasanyrole

                </div>{{-- /.prof-body --}}
            </div>{{-- /.prof-card --}}
        </div>{{-- /#profile-table --}}
    </div>

    <div class="modal fade" id="modalAction" tabindex="-1" aria-labelledby="largeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg"></div>
    </div>
</div>
@endsection

@push('js')
    <script src="{{ asset('') }}vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('') }}vendor/izitoast/dist/js/iziToast.min.js"></script>
    <script>
        $(function () {
            function bindStore() {
                $(document)
                    .off('submit.profStore', '#formAction')
                    .on('submit.profStore', '#formAction', function (e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        var url = this.getAttribute('action');
                        $.ajax({
                            method: 'POST',
                            url: url,
                            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (res) {
                                $('#modalAction').modal('hide');
                                iziToast.success({ title: 'Saved!', message: res.message, position: 'topRight' });
                                setTimeout(function () { location.reload(); }, 800);
                            },
                            error: function (res) {
                                var errors = (res.responseJSON || {}).errors || {};
                                $.each(errors, function (key, msg) {
                                    $('[name="' + key + '"]').parent().append('<span class="text-danger small d-block mt-1">' + msg + '</span>');
                                });
                            },
                        });
                    });
            }

            $('#btnEditProfile').on('click', function () {
                var url = $(this).data('edit-url');
                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function (response) {
                        $('#modalAction').find('.modal-dialog').html(response);
                        $('#modalAction').modal('show');
                        bindStore();
                    },
                    error: function (xhr) {
                        alert('Gagal memuat form edit. Status: ' + xhr.status);
                    },
                });
            });
        });
    </script>
@endpush
