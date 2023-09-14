<div class="content-wrapper">
    <div class="row same-height">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Kuota dan Hasil Pemetaan Mahasiswa {{ auth()->user()->subjects->departement }} pada PLP Tahun 2023</h5>
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
                                @php
                                    $maps = App\Models\Map::select('school_id')->where('year',2023)->where('subject_id',auth()->user()->subject_id)->groupBy('school_id')->get();
                                @endphp
                                @forelse ($maps as $map)
                                <tr>
                                    <td>{{ $map->schools->name ?? '' }}</td>
                                    <td>{{ App\Models\Map::where('subject_id',auth()->user()->subject_id)
                                                        ->where('school_id',$map->school_id)
                                                        ->where('year',2023)
                                                        ->count() ?? '' }}</td>
                                    <td>{{ App\Models\Map::where('subject_id',auth()->user()->subject_id)
                                                        ->where('school_id',$map->school_id)
                                                        ->where('year',2023)
                                                        ->whereNotNull('student_id')
                                                        ->count() ?? '' }}</td>
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
                    <h5>Pemetaan DPL {{ auth()->user()->subjects->departement }} pada PLP Tahun 2023</h5>
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
                                @php
                                    $lectures = App\Models\Map::select('lecture_id')
                                                        ->where('subject_id',auth()->user()->subject_id)
                                                        ->where('year',2023)
                                                        ->groupBy('lecture_id')->get();
                                @endphp
                                @forelse ($lectures as $lecture)
                                <tr>
                                    <td>{{ $lecture->lectures->name ?? '' }}</td>
                                    @php
                                        $maps = App\Models\Map::select('school_id', DB::raw('count(student_id) as total'))
                                                        ->where('lecture_id',$lecture->lecture_id)
                                                        ->where('year',2023)
                                                        ->groupBy('school_id')->get();
                                    @endphp
                                    <td>
                                        @forelse ($maps as $map)
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
