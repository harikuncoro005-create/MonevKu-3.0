<div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
    <table class="table table-bordered">
        <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
            <tr class="text-center">
                <td>NO</td>
                <td>PERANGKAT DAERAH</td>
                <td style="vertical-align: middle; width: 15rem">ASPEK PERENCANAAN<div>(MAX 100)</div></td>
                <td style="vertical-align: middle; width: 15rem">ASPEK REALISASI FISIK<div>(MAX 100)</div></td>
                <td style="vertical-align: middle; width: 15rem">ASPEK PELAPORAN <div>(MAX 50)</div></td>
                <td style="vertical-align: middle; width: 10rem">TOTAL SKOR</td>
            </tr>
        </thead>
        <tbody style="font-weight: 500; font-size: 0.9rem; line-height: 1rem;">
            @foreach ($instansi as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->instansi_nama }}</td>
                    <td class="text-center">
                        {{ $penilaian_perencanaan_bulan && $penilaian_perencanaan_bulan->has($item->instansi_kode) ? $penilaian_perencanaan_bulan->get($item->instansi_kode)->penilaian_rencana_item_nilai : 0 }}
                    </td>
                    <td class="text-center">
                        {{ array_key_exists($item->instansi_kode, $persentase_fisik) ? $persentase_fisik[$item->instansi_kode] : number_format(round(0,2),2) }}
                    </td>
                    <td class="text-center">
                        {{ $penilaian_pelaporan[$item->instansi_kode] }}
                    </td>
                    <td class="text-center">
                        {{ ($penilaian_perencanaan_bulan && $penilaian_perencanaan_bulan->has($item->instansi_kode) ? $penilaian_perencanaan_bulan->get($item->instansi_kode)->penilaian_rencana_item_nilai : 0)+(array_key_exists($item->instansi_kode, $persentase_fisik) ? $persentase_fisik[$item->instansi_kode] : number_format(round(0,2),2))+$penilaian_pelaporan[$item->instansi_kode] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>