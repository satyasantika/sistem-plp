<div class="page-break">
    <p class="text-center bold">
        HALAMAN PENGESAHAN<br>
        KEGIATAN FKIP EDU (EKSPLORASI EDUKASI)
        {{ $my_map->schools->name }}
    </p>
    <p class="text-center vertical-space-paragraph">
        Laporan ini telah diperiksa dan disetujui pada:<br>
        Hari ………, Tanggal ……, Bulan…….............., Tahun 2025
    </p>
    <p class="text-center">
        Menyetujui:
    </p>
    <table class="table-center" style="padding: 5px">
        <tbody>
            <tr>
                <td>
                    <div class="text-center vertical-space-sign">Dosen Pembimbing Lapangan,</div>
                </td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <div class="text-center vertical-space-sign">Guru Pamong</div>
                </td>
            </tr>
            <tr>
                <td class="text-center">{{ $my_map->lectures->name ?? '-' }}
                    <br>NIDN {{ $my_map->lectures->username ?? '-' }}</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td class="text-center">{{ $my_map->teachers->name ?? '-' }}
                    <br>NIP {{ $my_map->teachers->username ?? '-' }}</td>
            </tr>
        </tbody>
    </table>
    <p class="text-center vertical-space-sign">
        Kepala {{ $my_map->schools->name ?? '-' }}
        <br><br><br><br><br><br>
        {{ $my_map->schools->headmasters->name ?? '-' }}
        <br>NIP {{ $my_map->schools->headmasters->username ?? '-' }}
    </p>
</div>
