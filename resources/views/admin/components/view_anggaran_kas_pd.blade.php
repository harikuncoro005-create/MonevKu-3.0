<div class="bg-white rounded my-3 shadow-sm">
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center">
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 5rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                    {{-- <td rowspan="2" style="vertical-align: middle; min-width: 20rem;">NAMA</td> --}}
                    <td rowspan="2" style="vertical-align: middle; min-width: 12rem;">PAGU</td>
                    <td colspan="3" style="vertical-align: middle;">TRIWULAN I</td>
                    <td colspan="3" style="vertical-align: middle;">TRIWULAN II</td>
                    <td colspan="3" style="vertical-align: middle;">TRIWULAN III</td>
                    <td colspan="3" style="vertical-align: middle;">TRIWULAN IV</td>
                </tr>
                <tr class="text-center">
                    @foreach ($bulan as $index => $item)
                        <td class="text-center" style="vertical-align: middle; min-width:8rem">{{ $item }}</td>
                    @endforeach
                </tr>
            </thead>
            <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
                <tr style="line-height: 1;" class="alert-warning font-weight-bold">
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
                </tr>  

<?php if(count($akun['akun_2']) == 0) { ?>
    <tr>
        <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
    <?php } else {
        foreach ($akun['akun_2'] as $val_2) { ?>
            <tr style="line-height: 1;" class="alert-success font-weight-bold">
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
            </tr>
            <?php 
                $akun_3 = array_filter($akun['akun_3'], function ($var) use ($val_2) {
                    return $var['index_parent'] == $val_2['index_id'];
                });
            ?>

            <?php if(count($akun_3) > 0) {
                foreach ($akun_3 as $val_3) { ?>
                <tr style="line-height: 1;" class="font-weight-bold">
                    <td><?= $val_3['akun_kode'] ?></td>
                    <td><div style="padding-left:10px"><?= $val_3['akun_nama'] ?></div></td>
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
                </tr>
                <?php 
                    $akun_5 = array_filter($akun['akun_5'], function ($var) use ($val_3) {
                        return $var['index_parent'] == $val_3['index_id'];
                    });
                ?>

                <?php if(count($akun_5) > 0) {
                    foreach ($akun_5 as $val_5) { ?> 
                    <tr style="line-height: 1;" class="text-primary">
                        <td><?= $val_5['akun_kode'] ?></td>
                        <td><div style="padding-left:20px"><?= $val_5['akun_nama'] ?></div></td>
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
                    </tr>
                    <?php 
                        $akun_6 = array_filter($akun['akun_6'], function ($var) use ($val_5) {
                            return $var['index_parent'] == $val_5['index_id'];
                        });
                    ?>
                    <?php if(count($akun_6) > 0) {
                        foreach ($akun_6 as $val_6) { ?>
                        <tr style="line-height: 1;" class="text-success">
                            <td><?= $val_6['akun_kode'] ?></td>
                            <td><div style="padding-left:30px"><?= $val_6['akun_nama'] ?></div></td>
                            <td class="text-right">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}</td>
                            {{-- <td class="text-right" style="vertical-align: middle;">@php $val_4_debit = collect($val_4->jurnals)->pluck('jurnal_debit')->sum(); echo str_replace(',','.', number_format($val_4_debit)) @endphp</td>
                            <td class="text-right" style="vertical-align: middle;">@php $val_4_kredit = collect($val_4->jurnals)->pluck('jurnal_kredit')->sum(); echo str_replace(',','.', number_format($val_4_kredit)) @endphp</td>
                            <td class="text-right" style="vertical-align: middle;">{{ str_replace(',','.', number_format($val_4_debit - $val_4_kredit)) }}</td>
                            <td class="text-right" style="vertical-align: middle;">{{ $val_4->laporan_keuangan->laporan_keuangan_nama }}</td> --}}
                            @foreach ($bulan as $index => $item)
                                <td class="text-right" style="vertical-align: middle;">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->{'keuangan_'.$index})) }}</td>
                            @endforeach
                        </tr>

                    
                    <?php } } ?>

                <?php } } ?>

            <?php } } ?>
            
        <?php  ?>
    
    <?php  } } ?>


            </tbody>
        </table>
    </div>
</div>