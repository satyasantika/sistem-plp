@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush


<div class="content-wrapper">
    <div class="row same-height">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h5>Data Mahasiswa Peserta PLP</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <tbody>
                                @php
                                    $maps = App\Models\Map::where('student_id',auth()->user()->id)
                                                            ->get();
                                @endphp
                                @forelse ($maps as $map)
                                <tr>
                                    <td>Nama</td>
                                    <td>{{ $map->students->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Bidang Studi</td>
                                    <td>{{ $map->subjects->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Tempat Praktik</td>
                                    <td>{{ $map->schools->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Guru Pamong</td>
                                    <td>
                                        @if (isset($map->teachers->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->teachers->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->teachers->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dosen Pembimbing Lapangan</td>
                                    <td>
                                        @if (isset($map->lectures->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->lectures->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->lectures->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">Informasi tambahan</td>
                                </tr>
                                <tr>
                                    <td>Kepala Sekolah</td>
                                    <td>
                                        @if (isset($map->schools->headmasters->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->schools->headmasters->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->schools->headmasters->name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Koordinator Guru Pamong</td>
                                    <td>
                                        @if (isset($map->schools->coordinators->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->schools->coordinators->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->schools->coordinators->name ?? '-' }}
                                    </td>
                                </tr>

                                @empty
                                <div class="alert alert-info">Anda belum diplot oleh Jurusan</div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="content-wrapper">
    <div class="row same-height">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h5>Data Mahasiswa Peserta PLP</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <tbody>
                                @php
                                    $student = App\Models\Map::where('student_id',auth()->user()->id)->first();
                                    $maps = App\Models\Map::where('school_id',$student->school_id)->get();
                                @endphp
                                @forelse ($maps as $map)
                                <tr>
                                    <td>
                                        @if (isset($map->students->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->students->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->students->name ?? '-' }} <span class="badge bg-light text-dark">{{ $map->students->subjects->name ?? '-' }}</span>
                                    </td>
                                </tr>
                                @empty
                                <div class="alert alert-info">Kelompok belum ada</div>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
