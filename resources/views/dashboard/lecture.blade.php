@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .lecture-identity {
            border: 1px solid rgba(93, 124, 167, 0.22);
            border-radius: 12px;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(245, 250, 255, 0.95));
            padding: 12px 14px;
            margin-bottom: 12px;
        }

        .lecture-identity-title {
            margin: 0;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #5f7394;
            font-weight: 700;
        }

        .lecture-identity-name {
            margin: 3px 0 1px;
            font-size: 1rem;
            font-weight: 800;
            color: #213551;
        }

        .lecture-identity-meta {
            margin: 0;
            color: #6b7f9f;
            font-size: 0.82rem;
        }

        .lecture-identity-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .lecture-identity-badges .badge {
            border-radius: 999px;
            font-size: 0.72rem;
            letter-spacing: 0.2px;
            padding: 0.34rem 0.62rem;
        }

        body.dark .lecture-identity {
            border-color: rgba(150, 184, 229, 0.24);
            background: linear-gradient(145deg, rgba(24, 37, 57, 0.95), rgba(17, 30, 48, 0.95));
        }

        body.dark .lecture-identity-title,
        body.dark .lecture-identity-meta {
            color: #abc0e0;
        }

        body.dark .lecture-identity-name {
            color: #e2ecff;
        }
    </style>
@endpush


<div class="content-wrapper">
    <div class="row same-height">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h5>Sebaran Mahasiswa Bimbingan</h5>
                </div>
                <div class="card-body">
                    <div class="lecture-identity">
                        <p class="lecture-identity-title">Identitas Dosen Pembimbing Lapangan</p>
                        <p class="lecture-identity-name">{{ auth()->user()->name ?? '-' }}</p>
                        <p class="lecture-identity-meta">{{ auth()->user()->username ?? '-' }}</p>
                        <div class="lecture-identity-badges">
                            {{-- <span class="badge bg-light text-dark">{{ auth()->user()->email ?? '-' }}</span>
                            <span class="badge bg-light text-dark">{{ auth()->user()->phone ?? '-' }}</span> --}}
                            <span class="badge bg-primary">{{ auth()->user()->subjects->name ?? 'Prodi belum diatur' }}</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <a href="{{ route('schoolassessments.only.index') }}" class="btn btn-primary">Mulai Menilai</a>
                        <a href="{{ route('diaryverifications.only.index') }}" class="btn btn-primary">Verifikasi Logbook</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Sekolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lectureMaps as $map)
                                <tr>
                                    <td>
                                        @if (isset($map->students->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->students->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->students->name ?? '' }}
                                    </td>
                                    <td>{{ $map->schools->name ?? '' }}</td>
                                </tr>
                                @empty
                                <div class="alert alert-info">Belum ada data</div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
