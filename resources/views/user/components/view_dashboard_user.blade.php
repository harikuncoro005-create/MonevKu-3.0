<div class="table-responsive">
    <table class="table table-sm table-hover table-bordered">
        <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
            <tr class="text-center font-weight-bold small">
                <td colspan="2" style="vertical-align: middle; min-width: 5rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                <td style="vertical-align: middle; min-width: 8rem;">PAGU</td>
                <td style="vertical-align: middle; min-width: 8rem;">KELUARAN</td>
                <td style="vertical-align: middle; min-width: 8rem;">KEUANGAN</td>
                <td style="vertical-align: middle; min-width: 8rem;">FISIK</td>
            </tr>
            <tr style="line-height: 1;" class="alert-warning font-weight-bold small">
                <td colspan="2">{{ $instansi->instansi_nama }}</td>
                <td class="text-right" style="vertical-align: middle;">
                    @php
                        $keuangan_total = collect($keuangan->toArray())->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($keuangan_total ));
                    @endphp
                </td>
                <td colspan="4"></td>
                {{-- <td></td>
                <td></td>
                <td></td>
                <td></td> --}}
            </tr>  
        </thead>
        <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
            
            <?php if(count($akun['akun_2']) == 0) { ?>
            <tr class="small">
                <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
            </tr>
            <?php } else {
                foreach ($akun['akun_2'] as $val_2) { ?>
                    <tr style="line-height: 1;" class="alert-success font-weight-bold small bg-light">
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
                        <td colspan="4"></td>
                        {{-- <td></td>
                        <td></td>
                        <td></td> --}}
                    </tr>
                    <?php 
                        $akun_3 = array_filter($akun['akun_3'], function ($var) use ($val_2) {
                            return $var['index_parent'] == $val_2['index_id'];
                        });
                    ?>

                    <?php if(count($akun_3) > 0) {
                        foreach ($akun_3 as $val_3) { ?>
                        <tr style="line-height: 1;" class="font-weight-bold small bg-light">
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
                            <td colspan="4"></td>
                            {{-- <td></td>
                            <td></td>
                            <td></td> --}}
                        </tr>
                        <?php 
                            $akun_5 = array_filter($akun['akun_5'], function ($var) use ($val_3) {
                                return $var['index_parent'] == $val_3['index_id'];
                            });
                        ?>

                        <?php if(count($akun_5) > 0) {
                            foreach ($akun_5 as $val_5) { ?> 
                            <tr style="line-height: 1;" class="text-primary small bg-light">
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
                                <td colspan="4"></td>
                                {{-- <td></td>
                                <td></td>
                                <td></td> --}}
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
                                    <td><div style="padding-left:30px"><?= $val_6['akun_nama'] ?></div></td>
                                    <td class="text-right">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}</td>
                                    <td class="text-center">
                                        <div class="w-100 py-1 text-white text-nowrap" style="background-color: {{ $status_kinerja_keluaran[$val_6['akun_kode']]['color'] }}; font-size: 0.5rem">
                                            {{ $status_kinerja_keluaran[$val_6['akun_kode']]['nama'] }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="w-100 py-1 text-white text-nowrap" style="background-color: {{ $status_kinerja_keuangan[$val_6['akun_kode']]['color'] }}; font-size: 0.5rem">
                                            {{ $status_kinerja_keuangan[$val_6['akun_kode']]['nama'] }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="w-100 py-1 text-white text-nowrap" style="background-color: {{ $status_kinerja_fisik[$val_6['akun_kode']]['color'] }}; font-size: 0.5rem">
                                            {{ $status_kinerja_fisik[$val_6['akun_kode']]['nama'] }}
                                        </div>
                                    </td>
                                </tr>

                            
                            <?php } } ?>

                        <?php } } ?>

                    <?php } } ?>
                    
                <?php  ?>

            <?php  } } ?>


        </tbody>
    </table>
</div>
