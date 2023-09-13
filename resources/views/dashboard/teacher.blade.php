@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endpush


<div class="content-wrapper">
    <div class="row same-height">
        <div class="col-md">
            <div class="card">
                <div class="card-header">
                    <h5>Data Mahasiswa Pamongan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table small-font table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maps = App\Models\Map::where('subject_id',auth()->user()->subject_id)
                                                            ->where('teacher_id',auth()->user()->id)
                                                            ->where('year',2023)
                                                            ->get()
                                @endphp
                                @forelse ($maps as $map)
                                <tr>
                                    <td class="text-end">
                                        @if (isset($map->students->phone))
                                            <a href="{{ 'http://wa.me/62'.$map->students->phone }}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-whatsapp"></i></a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $map->students->name ?? '' }}
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
