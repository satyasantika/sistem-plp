<table>
    <thead>
    <tr>
        <th>No</th>
        <th>NPM</th>
        <th>Mahasiswa</th>
        <th>Jurusan</th>
        <th>Tempat Praktik</th>
        <th>DPL</th>
        <th>Nilai Angka</th>
        <th>Nilai Huruf</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($maps as $index => $map)
        @php
            $letter = '';
            $grade = App\Models\Assessment::where([
                    'plp_order' => 1,
                    'map_id' => $map->id,
                ])->sum('grade')/2;

            if ($grade >= 86) {
                $letter = 'A';
            } else if ($grade >= 76) {
                $letter = 'B';
            } else if ($grade >= 66) {
                $letter = 'C';
            } else if ($grade >= 56) {
                $letter = 'D';
            } else {
                $letter = 'E';
            }

            if ($grade < 76) {
                $description = 'TIDAK LULUS';
            } else {
                $description = 'LULUS';
            }


        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $map->students->username ?? '' }}</td>
            <td>{{ $map->students->name ?? '' }}</td>
            <td>{{ $map->subjects->departement ?? '' }}</td>
            <td>{{ $map->schools->name ?? '' }}</td>
            <td>{{ $map->lectures->name ?? '' }}</td>
            <td>{{ $grade }}</td>
            <td>{{ $letter }}</td>
            <td>{{ $description }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
