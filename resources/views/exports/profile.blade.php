<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Peran</th>
        <th>Sekolah</th>
        <th>Mapel</th>
        <th>NIP</th>
        <th>Status PNS</th>
        <th>Golongan</th>
        <th>NPWP</th>
        <th>No. Rekening</th>
        <th>Bank</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    {{-- LIST GURU PAMONG --}}
    @php $no = 1; @endphp
    @foreach($teacher_maps as $index => $map)
        <tr>
            <td>{{ $index + $no }}</td>
            <td>{{ $map->teachers->name ?? '' }}</td>
            <td>Guru Pamong</td>
            <td>{{ $map->schools->name ?? '' }}</td>
            <td>{{ $map->teachers->subjects->name ?? '' }}</td>
            <td>{{ $map->teachers->username ?? ''}}</td>
            <td>{{ $map->teachers->is_pns ?? ''}}</td>
            <td>{{ $map->teachers->golongan ?? ''}}</td>
            <td>{{ $map->teachers->npwp ?? '' }}</td>
            <td>{{ $map->teachers->nomor_rekening ?? '' }}</td>
            <td>{{ $map->teachers->bank ?? '' }}</td>
            <td>{{ \App\Models\Map::where('teacher_id',$map->teacher_id)->where('plp2',1)->count() ?? '' }}</td>
        </tr>
    @endforeach
    {{-- LIST KEPALA SEKOLAH --}}
    @php $no = $teacher_maps->count(); @endphp
    @foreach($headmaster_maps as $index => $map)
        <tr>
            <td>{{ $index + $no }}</td>
            <td>{{ $map->schools->headmasters->name ?? '' }}</td>
            <td>Kepala</td>
            <td>{{ $map->schools->name ?? '' }}</td>
            <td>-</td>
            <td>{{ $map->schools->headmasters->username ?? ''}}</td>
            <td>{{ $map->schools->headmasters->is_pns ?? ''}}</td>
            <td>{{ $map->schools->headmasters->golongan ?? ''}}</td>
            <td>{{ $map->schools->headmasters->npwp ?? '' }}</td>
            <td>{{ $map->schools->headmasters->nomor_rekening ?? '' }}</td>
            <td>{{ $map->schools->headmasters->bank ?? '' }}</td>
            <td></td>
        </tr>
    @endforeach
    {{-- LIST KOORDINATOR GURU PAMONG --}}
    @php $no += $headmaster_maps->count(); @endphp
    @foreach($coordinator_maps as $index => $map)
        <tr>
            <td>{{ $index + $no }}</td>
            <td>{{ $map->schools->coordinators->name ?? '' }}</td>
            <td>Koordinator Guru Pamong</td>
            <td>{{ $map->schools->name ?? '' }}</td>
            <td>-</td>
            <td>{{ $map->schools->coordinators->username ?? ''}}</td>
            <td>{{ $map->schools->coordinators->is_pns ?? ''}}</td>
            <td>{{ $map->schools->coordinators->golongan ?? ''}}</td>
            <td>{{ $map->schools->coordinators->npwp ?? '' }}</td>
            <td>{{ $map->schools->coordinators->nomor_rekening ?? '' }}</td>
            <td>{{ $map->schools->coordinators->bank ?? '' }}</td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
