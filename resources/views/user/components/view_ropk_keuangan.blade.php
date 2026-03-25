<div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
    </svg>
    <span>ROPK KEUANGAN</span>
</div>
<br>
<div class="table-responsive" style="max-height: 38rem; overflow-y: auto">
    <table class="table table-sm table-hover table-bordered">
        <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
            <tr class="text-center font-weight-bold small">
                <td colspan="2" style="vertical-align: middle; width: 20rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                <td style="vertical-align: middle; width: 8rem;">PAGU</td>
                @foreach ($bulan as $index => $item)
                    <td class="text-center" style="vertical-align: middle; width:8rem">{{ strtoupper($item) }}</td>
                @endforeach
                {{-- <td style="vertical-align: middle;">AKSI</td> --}}
            </tr>
        </thead>
        <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
            <tr style="line-height: 1;" class="alert-warning font-weight-bold small">
                <td colspan="2">{{ $instansi->instansi_nama }}</td>
                <td class="text-right" style="vertical-align: middle;">
                    @php
                        $keuangan_total = collect($keuangan->toArray())->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($keuangan_total ));
                    @endphp
                </td>
                @foreach ($bulan as $index => $item)
                <td class="text-right" style="vertical-align: middle;">
                    @php 
                        ${'total_'.$index} = collect($keuangan->toArray())->pluck('keuangan_'.$index)->sum(); echo str_replace(',','.', number_format(${'total_'.$index})); 
                    @endphp
                </td>
                @endforeach
                {{-- <td></td> --}}
            </tr>  

<?php if(count($akun['akun_2']) == 0) { ?>
<tr class="small">
    <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
</tr>
<?php } else {
    foreach ($akun['akun_2'] as $val_2) { ?>
        <tr style="line-height: 1;" class="alert-success font-weight-bold small">
            <td><?= $val_2['akun_kode'] ?></td>
            <td><strong><?= $val_2['akun_nama'] ?></strong></td>
            <td class="text-right" style="vertical-align: middle;">
                @php
                    $keuangan_2 = array_filter($keuangan->toArray(), function ($var) use ($val_2) {
                        return $var['keuangan_bidang_urusan_kode'] == $val_2['akun_kode'];
                    });
                    
                    $bidang_urusan = collect($keuangan_2)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($bidang_urusan));

                @endphp
            </td>
            @foreach ($bulan as $index => $item)
                <td class="text-right" style="vertical-align: middle;">
                    @php 
                        ${'bidang_urusan_'.$index} = collect($keuangan_2)->pluck('keuangan_'.$index)->sum(); echo str_replace(',','.', number_format(${'bidang_urusan_'.$index})); 
                    @endphp
                </td>
            @endforeach
            {{-- <td></td> --}}
        </tr>
        <?php 
            $akun_3 = array_filter($akun['akun_3'], function ($var) use ($val_2) {
                return $var['index_parent'] == $val_2['index_id'];
            });
        ?>

        <?php if(count($akun_3) > 0) {
            foreach ($akun_3 as $val_3) { ?>
            <tr style="line-height: 1;" class="font-weight-bold small">
                <td><?= $val_3['akun_kode'] ?></td>
                <td><div><?= $val_3['akun_nama'] ?></div></td>
                <td class="text-right">
                    @php
                        $keuangan_3 = array_filter($keuangan->toArray(), function ($var) use ($val_3) {
                            return $var['keuangan_program_kode'] == $val_3['akun_kode'];
                        });
                        
                        $program = collect($keuangan_3)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($program));

                    @endphp
                </td>
                @foreach ($bulan as $index => $item)
                    <td class="text-right" style="vertical-align: middle;">
                        @php 
                            ${'program_'.$index} = collect($keuangan_3)->pluck('keuangan_'.$index)->sum(); echo str_replace(',','.', number_format(${'program_'.$index})); 
                        @endphp
                    </td>
                @endforeach
                {{-- <td></td> --}}
            </tr>
            <?php 
                $akun_5 = array_filter($akun['akun_5'], function ($var) use ($val_3) {
                    return $var['index_parent'] == $val_3['index_id'];
                });
            ?>

            <?php if(count($akun_5) > 0) {
                foreach ($akun_5 as $val_5) { ?> 
                <tr style="line-height: 1;" class="text-primary small">
                    <td><?= $val_5['akun_kode'] ?></td>
                    <td><div><?= $val_5['akun_nama'] ?></div></td>
                    <td class="text-right">
                        @php
                            $keuangan_5 = array_filter($keuangan->toArray(), function ($var) use ($val_5) {
                                return $var['keuangan_kegiatan_kode'] == $val_5['akun_kode'];
                            });
                            
                            $kegiatan = collect($keuangan_5)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($kegiatan));
                        
                        @endphp
                    </td>
                    @foreach ($bulan as $index => $item)
                        <td class="text-right" style="vertical-align: middle;">
                            @php 
                                ${'kegiatan_'.$index} = collect($keuangan_5)->pluck('keuangan_'.$index)->sum(); echo str_replace(',','.', number_format(${'kegiatan_'.$index})); 
                            @endphp
                        </td>
                    @endforeach
                    {{-- <td></td> --}}
                </tr>
                <?php 
                    $akun_6 = array_filter($akun['akun_6'], function ($var) use ($val_5) {
                        return $var['index_parent'] == $val_5['index_id'];
                    });
                ?>
                <?php if(count($akun_6) > 0) {
                    foreach ($akun_6 as $val_6) { ?>
                    <tr style="line-height: 1;" class="small">
                        <td><?= $val_6['akun_kode'] ?></td>
                        <td><div><?= $val_6['akun_nama'] ?></div></td>
                        <td class="text-right">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}</td>
                        @foreach ($bulan as $index => $item)
                            <td class="text-right" style="vertical-align: middle;">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->{'keuangan_'.$index})) }}</td>
                        @endforeach
                        {{-- <td class="text-center">
                            <a href="" title="Ubah">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                        </td> --}}
                    </tr>

                
                <?php } } ?>

            <?php } } ?>

        <?php } } ?>
        
    <?php  ?>

<?php  } } ?>


        </tbody>
    </table>
</div>