<div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
    <table class="table table-sm table-borderless">
        <tbody class="text-small text-gray-500" style="line-height: 1rem;">
            @foreach ($ref_kode as $item)
            <tr class="text-nowrap">
                <td style="vertical-align: middle; width:10rem">{{ $item['kode_nama'] }}</td>
                <td style="vertical-align: middle; width:10rem">{{ $item['kode_nomenklatur'] }}</td>
                <td style="vertical-align: middle">{{ $nomenklatur[$item['kode_nomenklatur']]->nomenklatur_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<hr>
<div class="my-2 text-gray-500">
    <div class="d-flex flex-row align-items-center justify-content-between">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
            </svg>
            <span>ROPK FISIK</span>
        </div>
    </div>
</div>
<div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
    <table class="table table-bordered">
            <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center">
                    <td rowspan="2" style="vertical-align: middle; width: 5rem;">KODE</td>
                    <td rowspan="2" style="vertical-align: middle;">AKTIVITAS KEGIATAN</td>
                    <td rowspan="2" style="vertical-align: middle; width: 5rem;">ACUAN</td>
                    <td colspan="24" style="vertical-align: middle; width: 5rem;">BULAN</td>
                </tr>
                <tr class="text-center">
                    @foreach ($bulan as $index =>$item)
                        <td colspan="2" style="vertical-align: middle; width: 5rem;">{{ $index }}</td>
                    @endforeach
                </tr>
        </thead>
        <tbody class="text-small text-gray-500" style="line-height: 1;">
            @foreach ($tahapan as $key => $value)
            <tr class="text-center font-weight-bold alert-warning">
                <td class="text-left" style="vertical-align: middle; width: 5rem;">{{ $key }}</td>
                <td class="text-left" style="vertical-align: middle;">{{ $value }}</td>
                <td colspan="25" style="vertical-align: middle;"></td>
            </tr>
                @foreach ($fisik->get($key, collect([])) as $item)
                <tr class="text-center">
                    <td class="text-left" style="vertical-align: middle; width: 5rem;">{{ $key }}.{{ $item->fisik_nomor }}</td>
                    <td class="text-left" style="vertical-align: middle;">{{ $item->fisik_aktivitas }}</td>
                    <td style="vertical-align: middle;">{{ str_replace(".", ",", sprintf('%.2f', $item->fisik_acuan)) }}</td>
                    @foreach ($bulan as $index => $val)
                        <td style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $item->{'fisik_'.$index})) }}</td>
                        <td style="vertical-align: middle; width: 5rem;"><a href=""><i class="fa-regular fa-file"></i></a></td>
                    @endforeach
                </tr>
                @endforeach
            @endforeach
        </tbody>
        <tfoot class="text-small text-gray-500" style="line-height: 1;">
            <tr>
                <td colspan="27"></td>
            </tr>
            <tr class="text-center">
                <td colspan="3"class="text-left" style="vertical-align: middle;">JUMLAH</td>
                @foreach ($bulan as $index => $item)
                    <td colspan="2" style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', collect($fisik->collapse())->sum('fisik_'.$index))) }}</td>
                @endforeach
            </tr>
            <tr class="text-center">
                <td colspan="3"class="text-left" style="vertical-align: middle;">Target Kumulatif Fisik</td>
                @foreach ($bulan as $index => $item)
                    <td colspan="2" class="font-weight-bold" style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $fisik_kumulatif[$index])) }}</td>
                @endforeach
            </tr>
            <tr class="text-center">
                <td colspan="3"class="text-left" style="vertical-align: middle;">Realisasi Kumulatif Fisik</td>
                @foreach ($bulan as $index => $item)
                    <td colspan="2" class="font-weight-bold" style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $fisik_kumulatif_realisasi[$index])) }}</td>
                @endforeach
            </tr>
            <tr class="text-center">
                <td colspan="3"class="text-left" style="vertical-align: middle;">Deviasi Fisik</td>
                @foreach ($bulan as $index => $item)
                    <td colspan="2" style="vertical-align: middle; width: 5rem;" class="{{ $fisik_kumulatif_realisasi[$index]-$fisik_kumulatif[$index] < -1 ? 'alert-danger' : '' }}">{{ str_replace(".", ",", sprintf('%.2f', $fisik_kumulatif_realisasi[$index]-$fisik_kumulatif[$index])) }}</td>
                @endforeach
            </tr>
            {{-- <tr class="text-center">
                <td colspan="2"class="text-left" style="vertical-align: middle;">% Angkas Komulatif</td>
                <td></td>
                @foreach ($bulan as $index => $item)
                    <td style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $keuangan_kumulatif[$index])) }}</td>
                @endforeach
                <td></td>
            </tr>
            <tr class="text-center">
                <td colspan="2"class="text-left" style="vertical-align: middle;">Perbandingan Fisik - Keuangan</td>
                <td></td>
                @foreach ($bulan as $index => $item)
                    <td style="vertical-align: middle; width: 5rem;" class="{{ $fisik_kumulatif[$index]-$keuangan_kumulatif[$index] < -1 ? 'alert-danger' : '' }}">{{ str_replace(".", ",", sprintf('%.2f', $fisik_kumulatif[$index]-$keuangan_kumulatif[$index])) }}</td>
                @endforeach
            </tr> --}}
        </tfoot>
    </table>
</div>