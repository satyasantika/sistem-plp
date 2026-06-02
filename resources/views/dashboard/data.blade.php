<div class="content-wrapper">
    <div class="row g-3 mb-3">
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Mahasiswa PLP</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="fs-4 fw-bold">{{ number_format($dataOverview['students'] ?? 0) }}</div>
                        <span class="badge bg-primary">Tahun {{ $activeYear }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5>DPL Terplot</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="fs-4 fw-bold">{{ number_format($dataOverview['dpl'] ?? 0) }}</div>
                        <span class="badge bg-light text-dark">Jurusan: {{ $dataOverview['subjects'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5>GP Terplot</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="fs-4 fw-bold">{{ number_format($dataOverview['gp'] ?? 0) }}</div>
                        <span class="badge bg-light text-dark">Sekolah: {{ $dataOverview['schools'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Slot Terisi</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end justify-content-between">
                        <div class="fs-4 fw-bold">{{ $dataFillRate ?? 0 }}%</div>
                        <span class="badge bg-light text-dark">Kuota map</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Akses Cepat Operasional</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @can('report/summary/plp-read')
                            <a href="{{ url('report/summary/plp') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti-book"></i> Summary PLP
                            </a>
                        @endcan
                        @canany(['data/progress/plp-read', 'data/progress/plp1-read', 'data/progress/plp2-read'])
                            <a href="{{ url('data/progress/plp') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti-dashboard"></i> Progress PLP
                            </a>
                        @endcanany
                        @can('data/progress/profile-read')
                            <a href="{{ url('data/progress/profile') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti-id-badge"></i> Progress Profil
                            </a>
                        @endcan
                        @can('data/cleaningassessments-read')
                            <a href="{{ url('data/cleaningassessments') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ti-alert"></i> Pembersihan Data
                            </a>
                        @endcan
                        @role('data')
                            <a href="{{ route('report.summary.plp.export') }}" class="btn btn-sm btn-outline-success">
                                <i class="ti-download"></i> Export Excel
                            </a>
                        @endrole
                    </div>

                    @if (!empty($dataActiveBuckets))
                        <div class="mt-3">
                            <div class="small text-muted mb-2">Periode penilaian aktif tahun {{ $activeYear }}</div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($dataActiveBuckets as $bucket)
                                    <a href="{{ $bucket['url'] }}" class="badge bg-light text-dark text-decoration-none">
                                        {{ $bucket['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border mt-3 mb-0 py-2 small">
                            Belum ada periode penilaian aktif untuk tahun {{ $activeYear }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between gap-2">
                    <h5>Sekolah dengan Mahasiswa Terbanyak</h5>
                    @can('report/summary/plp-read')
                        <a href="{{ url('report/summary/plp') }}" class="small">Lihat detail</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Sekolah</th>
                                    <th class="text-end">Mahasiswa</th>
                                    <th class="text-end">GP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataTopSchools as $school)
                                <tr>
                                    <td>{{ $school['name'] ?? '-' }}</td>
                                    <td class="text-end">{{ number_format($school['student_total'] ?? 0) }}</td>
                                    <td class="text-end">{{ number_format($school['gp_total'] ?? 0) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data sekolah untuk tahun {{ $activeYear }}.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between gap-2">
                    <h5>Rekap PLP per Jurusan</h5>
                    @can('report/summary/plp-read')
                        <a href="{{ url('report/summary/plp') }}" class="small">Buka summary lengkap</a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Jurusan</th>
                                    <th class="text-end">Mahasiswa</th>
                                    <th class="text-end">DPL</th>
                                    <th class="text-end">GP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataSubjectRecap as $row)
                                <tr>
                                    <td>
                                        <div>{{ $row['name'] ?? '-' }}</div>
                                        <small class="text-muted">{{ $row['department'] ?? '-' }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($row['student_count'] ?? 0) }}</td>
                                    <td class="text-end">{{ number_format($row['dpl_count'] ?? 0) }}</td>
                                    <td class="text-end">{{ number_format($row['gp_count'] ?? 0) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada data rekap jurusan untuk tahun {{ $activeYear }}.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
