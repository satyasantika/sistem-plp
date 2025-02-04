<div class="page-break">
    <p class="font-14 text-center">
        LAPORAN KELOMPOK<br>
        FKIP EDU (EKSPLORASI EDUKASI)<br>
    </p>
    <p class="font-20 text-center vertical-space-paragraph">
        {{ $my_map->schools->name ?? '' }}
    </p>
    <p class="text-center vertical-space-paragraph">
        Laporan ini disusun sebagai salah satu syarat untuk menyelesaikan kegiatan<br>
        FKIP EDU (Eksplorasi Edukasi)<br>
        di Fakultas Keguruan dan Ilmu Pendidikan Universitas Siliwangi
    </p>
    <p class="text-center">
        Tim Penyusun,
    </p>
    <table class="table-center table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>NPM</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($maps as $key => $map)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $map->students->name }}</td>
                <td>{{ $map->students->username }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
