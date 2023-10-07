<table>
    <thead>
    <tr>
        <th>No</th>
        <th>NPM</th>
        <th>Mahasiswa</th>
        <th>Jurusan</th>
        <th>Tempat Praktik</th>
        <th>DPL</th>
        @if (request()->segment(2) == 'plp2')
            <th>GP</th>
            <th>Nilai DPL</th>
            <th>Nilai GP</th>
        @endif
        <th>Nilai Angka</th>
        <th>Nilai Huruf</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($maps as $index => $map)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $map->students->username ?? '' }}</td>
            <td>{{ $map->students->name ?? '' }}</td>
            <td>{{ $map->subjects->departement ?? '' }}</td>
            <td>{{ $map->schools->name ?? '' }}</td>
            <td>{{ $map->lectures->name ?? '' }}</td>
            @if (request()->segment(2) == 'plp2')
                <td>{{ $map->teachers->name ?? '' }}</td>
                <td>{{ $lecture_grade ?? '' }}</td>
                <td>{{ $teacher_grade ?? '' }}</td>
            @endif
            <td>{{ $map->grade1 }}</td>
            <td>{{ $map->letter1 }}</td>
            <td>{{ $map->grade1< 61 ? 'TIDAK LULUS' : 'LULUS' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
