<style>
    .label-sangat-tinggi { background-color: #047857; color: white; }
    .label-tinggi        { background-color: #34D399; color: white; }
    .label-sedang        { background-color: #FBBF24; color: black; }
    .label-rendah        { background-color: #D97706; color: white; }
    .label-sangat-rendah { background-color: #DC2626; color: white; }
</style>
<div>
    <div class="my-3 d-flex justify-content-start">
        <form action="{{ $url_export }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="pd" value="{{ $instansi_kode }}">
            <input type="hidden" name="bulan" value="{{ $bulan_index }}">
            <button type="submit" class="btn btn-sm btn-danger text-nowrap btn-export" style="width: 8rem" title="Export Laporan APBD Bulanan"><i class="fa-regular fa-file-pdf"></i> Export PDF</button>
        </form>
    </div>
    <div class="table-responsive" style="max-height: 35rem; overflow-y: auto">
        <table class="table table-sm table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center small">
                    <td rowspan="3" style="vertical-align: middle; min-width: 2rem;">NO</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 18rem;">SUB KEGIATAN</td>
                    <td colspan="4" style="vertical-align: middle; min-width: 16rem;">NILAI (SCORE)</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 6rem;">PREDIKAT KINERJA</td>
                </tr>
                <tr class="text-center small">
                    <td style="vertical-align: middle; min-width: 4rem;">Fisik</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Keuangan</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Pelaporan</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Total</td>
                </tr>
                <tr class="text-center small">
                    <td>(40%)</td>
                    <td>(40%)</td>
                    <td>(20%)</td>
                    <td>(100%)</td>
                </tr>
            </thead>
            <tbody class="text-small text-gray-500" style="line-height: 1;">
                @if ($subkegiatan)
                    @foreach ($subkegiatan as $item)
                    <tr class="text-12">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nomenklatur_nama }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['fisik_nilai'] }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['keuangan_nilai'] }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['pelaporan_nilai'] }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['total_nilai'] }}</td>
                        <td class="text-center {{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['status_nilai']['class'] }}" style="font-size: 9px">
                            {{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['status_nilai']['nama'] }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center"><em>Data Tidak Ditemukan</em></td>
                    </tr>
                @endif
            </tbody> 

        </table>
    </div>
</div>