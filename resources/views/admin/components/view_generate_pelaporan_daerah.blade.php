<tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
    <tr style="line-height: 1;" class="alert-danger font-weight-bold">
        <td colspan="17">{{ $instansi->instansi_nama }}</td>
    </tr>
    <?php if(count($akun['akun_2']) == 0) { ?>
    <tr class="">
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

                            {{-- TARGET --}}
                            <td class="text-center">{{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}</td>
                            <td class="text-right">{{ str_replace(',','.', number_format($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])) }}</td>

                            <td class="text-center">{{ $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round(($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2) }}</td>

                            <td class="text-center">{{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? $fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0 }}</td>

                            {{-- REALISASI --}}
                            <td class="text-center">{{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}</td>
                            <td class="text-right">{{ str_replace(',','.', number_format($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])) }}</td>

                            <td class="text-center">{{ $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2)  }}</td>
                            
                            <td class="text-center">{{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0 }}</td>

                            {{-- DEVIASI --}}
                            <td class="text-center">
                                {{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0) }}
                            </td>
                            <td class="text-right">{{ str_replace(',','.', number_format($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]-$keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])) }}</td>

                            <td class="text-center">{{
                            ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2))
                            -
                            ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round(($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2))
                            }}</td>

                            <td class="text-center">{{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? $fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0) }}</td>

                            {{-- PERMASALAHAN --}}
                            <td>
                                <div style="max-height: 10rem; overflow-y:auto">
                                    {!! $permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index) ? json_decode(data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_deskripsi) : '<div class="text-gray-400"><em>-</em></div>' !!}
                                </div>
                            </td>
                            <td>
                                <div style="max-height: 10rem; overflow-y:auto">
                                    {!! $permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index) ? json_decode(data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_tindaklanjut) : '<div class="text-gray-400"><em>-</em></div>' !!}
                                </div>
                            </td>

                        </tr>                               
                    <?php } } ?>

                <?php } } ?>

            <?php } } ?>
    
    <?php  } } ?>
</tbody>  
