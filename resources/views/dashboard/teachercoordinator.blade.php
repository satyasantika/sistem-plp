@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush

@php
    $schools = App\Models\School::where('coordinator_id',auth()->user()->id)->get();
@endphp
@foreach ($schools as $school)
<div class="content-wrapper">
    <div class="row same-height">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h5>Data Mahasiswa PLP {{ $school->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Bidang Studi</th>
                                    <th>Nama</th>
                                    <th>Guru Pamong</th>
                                    <th>DPL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maps = App\Models\Map::where('school_id',$school->id)->where('year',2023)->get();
                                @endphp
                                @forelse ($maps as $map)
                                <tr>
                                    <td>{{ $map->subjects->name }}</td>
                                    <td>
                                        @if (isset($map->students->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->students->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->students->name ?? '' }}
                                    </td>
                                    <td>
                                        @if (isset($map->teachers->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->teachers->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->teachers->name ?? '' }}
                                    </td>
                                    <td>
                                        @if (isset($map->lectures->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->lectures->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                        {{ $map->lectures->name ?? '' }}
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
@endforeach
