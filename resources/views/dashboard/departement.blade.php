<div class="content-wrapper">
    @php
        $departementName = auth()->user()->subjects->departement ?? '-';
    @endphp
    <div class="row same-height">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Kuota dan Hasil Pemetaan Mahasiswa {{ $departementName }} pada PLP Tahun {{ $activeYear }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Sekolah</th>
                                    <th>Kuota</th>
                                    <th>Terisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departementSchoolSummaries as $map)
                                <tr>
                                    <td>{{ $map->schools->name ?? '' }}</td>
                                    <td>{{ $map->quota_count ?? '' }}</td>
                                    <td>{{ $map->filled_count ?? '' }}</td>
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Pemetaan DPL {{ $departementName }} pada PLP Tahun {{ $activeYear }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Sekolah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($departementLectureSummaries as $lectureSummary)
                                <tr>
                                    <td>{{ $lectureSummary->lecture->name ?? '' }}</td>
                                    <td>
                                        @forelse ($lectureSummary->schools as $map)
                                            <span class="badge bg-light rounded-pill text-dark">
                                                {{ $map->schools->name ?? '' }}
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $map->total ?? '' }}
                                                </span>
                                            </span>
                                        @empty
                                        <div class="alert alert-info">Belum diset</div>
                                        @endforelse
                                    </td>
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
