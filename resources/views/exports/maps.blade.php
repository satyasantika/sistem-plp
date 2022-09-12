<table>
    <thead>
    <tr>
        <th>Mahasiswa</th>
        <th>DPL</th>
        <th>Sekolah</th>
    </tr>
    </thead>
    <tbody>
    @foreach($maps as $map)
        <tr>
            <td>{{ $map->students->name ?? '' }}</td>
            <td>{{ $map->lectures->name ?? '' }}</td>
            <td>{{ $map->schools->name ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
