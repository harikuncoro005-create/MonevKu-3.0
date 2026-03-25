<style>
.light-swap {
    position: relative;
    overflow: hidden; /* Wajib agar kilatan tidak keluar dari box */
}

.light-swap::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -100%;
    width: 50%;
    height: 200%;
    background: linear-gradient(
        to right, 
        rgba(255, 255, 255, 0) 0%, 
        rgba(255, 255, 255, 0.6) 50%, 
        rgba(255, 255, 255, 0) 100%
    );
    transform: rotate(30deg);
    animation: light-swap 3s infinite; /* Mengulang setiap 3 detik */
}

@keyframes light-swap {
    0% { left: -100%; }
    20% { left: 150%; } /* Kilatan lewat dengan cepat */
    100% { left: 150%; } /* Jeda waktu sebelum muncul lagi */
}
</style>

<div class="table-responsive" style="max-height: 35rem; overflow-y: auto">
    <table class="table table-sm table-hover table-bordered">
        <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
            <tr class="text-center small">
                <td colspan="2" rowspan="3" style="vertical-align: middle; min-width: 20rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                <td rowspan="3" style="vertical-align: middle; width: 8rem;">PAGU</td>
                <td colspan="4" style="vertical-align: middle; width: 12rem;">TARGET</td>
                <td colspan="4" style="vertical-align: middle; width: 12rem;">REALISASI</td>
                <td colspan="4" style="vertical-align: middle; width: 12rem;">DEVIASI</td>
                <td rowspan="3" style="vertical-align: middle; width: 12rem;">PENILAIAN</td>
                <td rowspan="3" style="vertical-align: middle;">PROGRESS</td>
            </tr>
            <tr class="text-center small">
                <td rowspan="2" style="vertical-align: middle; width: 2rem;">Keluaran</td>
                <td colspan="2" style="vertical-align: middle; width: 10rem;">Keuangan</td>
                <td rowspan="2" style="vertical-align: middle; width: 2rem;">Fisik</td>
                <td rowspan="2" style="vertical-align: middle; width: 2rem;">Keluaran</td>
                <td colspan="2" style="vertical-align: middle; width: 10rem;">Keuangan</td>
                <td rowspan="2" style="vertical-align: middle; width: 2rem;">Fisik</td>
                <td rowspan="2" style="vertical-align: middle; width: 2rem;">Keluaran</td>
                <td colspan="2" style="vertical-align: middle; width: 10rem;">Keuangan</td>
                <td rowspan="2" style="vertical-align: middle; width: 2rem;">Fisik</td>
            </tr>
            <tr class="text-center small">
                <td style="vertical-align: middle; width: 8rem;"><em>Rp</em></td>
                <td style="vertical-align: middle; width: 2rem;"><em>%</em></td>
                <td style="vertical-align: middle; width: 8rem;"><em>Rp</em></td>
                <td style="vertical-align: middle; width: 2rem;"><em>%</em></td>
                <td style="vertical-align: middle; width: 8rem;"><em>Rp</em></td>
                <td style="vertical-align: middle; width: 2rem;"><em>%</em></td>
            </tr>
            <tr style="line-height: 1;" class="alert-warning font-weight-bold small">
                <td colspan="2">{{ $instansi->instansi_nama }}</td>
                <td class="text-right" style="vertical-align: middle;">
                    @php
                        $keuangan_total = collect($keuangan->toArray())->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($keuangan_total ));
                    @endphp
                </td>
                <td colspan="14"></td>
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
                        <td colspan="14"></td>
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
                        <tr style="line-height: 1;" class="font-weight-bold text-success small bg-light">
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
                            <td colspan="14"></td>
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
                                <td><div><?= $val_5['akun_nama'] ?></div></td>
                                <td class="text-right">
                                    @php
                                        $keuangan_5 = array_filter($keuangan->toArray(), function ($var) use ($val_5) {
                                            return $var['keuangan_kegiatan_kode'] == $val_5['akun_kode'];
                                        });
                                        
                                        $kegiatan = collect($keuangan_5)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($kegiatan));
                                    
                                    @endphp
                                </td>
                                <td colspan="14"></td>
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
                                <tr style="line-height: 1;" class="text-gray-500 small">
                                    <td><?= $val_6['akun_kode'] ?></td>
                                    <td><div><?= $val_6['akun_nama'] ?></div></td>
                                    <td class="text-right">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}</td>
                                    {{-- TARGET --}}
                                    <td class="text-center">{{ array_key_exists($val_6['akun_kode'], $keluaran_target_bulan) ? $keluaran_target_bulan[$val_6['akun_kode']] : 0 }}</td>
                                    <td class="text-right">{{ str_replace(',','.', number_format(array_key_exists($val_6['akun_kode'], $keuangan_target_bulan) ? $keuangan_target_bulan[$val_6['akun_kode']] : 0)) }}</td>
                                    <td class="text-center">
                                        {{ number_format(round(($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (((array_key_exists($val_6['akun_kode'], $keuangan_target_bulan) ? $keuangan_target_bulan[$val_6['akun_kode']] : 0)*100)/($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) : 0),2),2)  }}
                                    </td>
                                    <td class="text-center">
                                        {{ str_replace(',','.', number_format((array_key_exists($val_6['akun_kode'], $fisik_target_bulan) ? $fisik_target_bulan[$val_6['akun_kode']] : 0),2)) }}
                                    </td>
                                    {{-- REALISASI --}}
                                    <td class="text-center">{{ array_key_exists($val_6['akun_kode'], $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$val_6['akun_kode']] : 0 }}</td>
                                    <td class="text-right">{{ str_replace(',','.', number_format(array_key_exists($val_6['akun_kode'], $keuangan_realisasi_bulan) ? $keuangan_realisasi_bulan[$val_6['akun_kode']] : 0)) }}</td>
                                    <td class="text-center">
                                        {{ number_format(round(($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (((array_key_exists($val_6['akun_kode'], $keuangan_realisasi_bulan) ? $keuangan_realisasi_bulan[$val_6['akun_kode']] : 0)*100)/($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) : 0),2),2)  }}
                                    </td>
                                    <td class="text-center">
                                        {{ str_replace(',','.', number_format((array_key_exists($val_6['akun_kode'], $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$val_6['akun_kode']] : 0),2)) }}
                                    </td>
                                    {{-- DEVIASI --}}
                                    <td class="text-center {{ (array_key_exists($val_6['akun_kode'], $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$val_6['akun_kode']] : 0)-(array_key_exists($val_6['akun_kode'], $keluaran_target_bulan) ? $keluaran_target_bulan[$val_6['akun_kode']] : 0) >= 0 ? 'alert-success' : 'alert-danger' }}">
                                        {{ (array_key_exists($val_6['akun_kode'], $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$val_6['akun_kode']] : 0)-(array_key_exists($val_6['akun_kode'], $keluaran_target_bulan) ? $keluaran_target_bulan[$val_6['akun_kode']] : 0) }}
                                    </td>

                                    <td class="text-right">{{ str_replace(',','.', number_format((array_key_exists($val_6['akun_kode'], $keuangan_realisasi_bulan) ? $keuangan_realisasi_bulan[$val_6['akun_kode']] : 0)-(array_key_exists($val_6['akun_kode'], $keuangan_target_bulan) ? $keuangan_target_bulan[$val_6['akun_kode']] : 0)))  }}</td>

                                    <td class="text-center {{ number_format(round(($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (((array_key_exists($val_6['akun_kode'], $keuangan_realisasi_bulan) ? $keuangan_realisasi_bulan[$val_6['akun_kode']] : 0)*100)/($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) : 0)-($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (((array_key_exists($val_6['akun_kode'], $keuangan_target_bulan) ? $keuangan_target_bulan[$val_6['akun_kode']] : 0)*100)/($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) : 0),2),2) > -10 ? 'alert-success' : 'alert-danger' }}">
                                        {{ number_format(round(($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (((array_key_exists($val_6['akun_kode'], $keuangan_realisasi_bulan) ? $keuangan_realisasi_bulan[$val_6['akun_kode']] : 0)*100)/($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) : 0)-($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (((array_key_exists($val_6['akun_kode'], $keuangan_target_bulan) ? $keuangan_target_bulan[$val_6['akun_kode']] : 0)*100)/($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) : 0),2),2) }}
                                    </td>

                                    <td class="text-center {{ str_replace(',','.', number_format(((array_key_exists($val_6['akun_kode'], $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$val_6['akun_kode']] : 0)-(array_key_exists($val_6['akun_kode'], $fisik_target_bulan) ? $fisik_target_bulan[$val_6['akun_kode']] : 0)),2)) == 0 ? 'alert-success' : 'alert-danger' }}">
                                        {{ str_replace(',','.', number_format(((array_key_exists($val_6['akun_kode'], $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$val_6['akun_kode']] : 0)-(array_key_exists($val_6['akun_kode'], $fisik_target_bulan) ? $fisik_target_bulan[$val_6['akun_kode']] : 0)),2)) }}
                                    </td>

                                    <td class="text-center">
                                        @if (isset($permasalahan[$val_6['akun_kode']]))
                                            @if ($permasalahan[$val_6['akun_kode']]->permasalahan_verifikasi == 1)
                                                <div class="w-100 py-1 text-white text-nowrap light-swap" style="background-color: #34D399; font-size: 0.5rem">TEPAT</div> 
                                            @elseif ($permasalahan[$val_6['akun_kode']]->permasalahan_verifikasi == 2)
                                                <div class="w-100 py-1 text-white text-nowrap light-swap" style="background-color: #F87171; font-size: 0.5rem">TIDAK TEPAT</div>
                                            @else
                                                <div class="w-100 py-1 text-white text-nowrap" style="background-color: #E5E7EB ; font-size: 0.5rem">Belum Dinilai</div>
                                            @endif
                                        @else
                                            <div class="w-100 py-1 text-gray-400 text-nowrap" style="background-color: #E5E7EB ; font-size: 0.5rem">Belum Dinilai</div> 
                                        @endif
                                    </td>
                        
                                    <td class="text-center">
                                        <a class="text-decoration-none text-small" href="/monev/detail?ref={{ $val_6['akun_id'] }}" title="Input Progress">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="16" width="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>


                                           

                                            {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                            </svg> --}}
                                        </a>
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
