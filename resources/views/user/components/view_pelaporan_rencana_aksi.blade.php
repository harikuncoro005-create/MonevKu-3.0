<div>
    <div class="my-3 d-flex justify-content-start">
        <form action="{{ $url_export }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="pd" value="{{ $instansi->instansi_kode }}">
            <button type="submit" class="btn btn-sm btn-success text-nowrap btn-export" style="width: 8rem" title="Export Laporan APBD Bulanan"><i class="fa-regular fa-file-excel"></i> Export Excel</button>
        </form>
    </div>
    
    <div class="table-responsive" style="max-height: 35rem; overflow-y: auto">
        <table class="table table-sm table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center small">
                    <td rowspan="2" style="vertical-align: middle; min-width: 8rem;">KODE</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 18rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 18rem;">INDIKATOR KINERJA</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">TARGET</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 6rem;">SATUAN</td>
                    @foreach ($bulan as $index => $value)
                        <td colspan="3" style="vertical-align: middle; min-width: 12rem;">{{ strtoupper($value) }}</td>
                    @endforeach
                    <td colspan="3" style="vertical-align: middle; min-width: 12rem;">JUMLAH AKHIR TAHUN</td>
                </tr>
                <tr class="text-center small">
                    @foreach ($bulan as $index => $value)
                        <td style="vertical-align: middle; min-width: 3rem;">kinerja</td>
                        <td style="vertical-align: middle; min-width: 3rem;">fisik</td>
                        <td style="vertical-align: middle; min-width: 6rem;">keuangan</td>
                    @endforeach
                    <td style="vertical-align: middle; min-width: 3rem;">kinerja</td>
                    <td style="vertical-align: middle; min-width: 3rem;">fisik</td>
                    <td style="vertical-align: middle; min-width: 6rem;">keuangan</td>
                </tr>
            </thead>
            <tbody class="text-small text-gray-500" style="line-height: 1;">
                
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
                                <td class="text-center text-gray-300">
                                    <em>On Progress</em>
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
                                        <td rowspan="{{ $keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) ? count($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])+1 : '' }}" style="white-space: nowrap;">
                                            <?= $val_6['akun_kode'] ?>
                                        </td>
                                        <td rowspan="{{ $keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) ? count($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])+1 : '' }}">
                                            <em><?= $val_6['akun_nama'] ?></em>
                                        </td>

                                        <td>
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_nama : '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_target : '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_satuan : '' }}
                                        </td>

                                        @foreach ($bulan as $index => $value)
                                            <td class="text-center" style="vertical-align:">{{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->{'keluaran_'.$index} : 0 }}</td>
                                            <td class="text-center" style="vertical-align:">{{ str_replace(".", ",", sprintf('%.2f', $fisik[$val_6['akun_kode']][$index] ?? 0)) }}</td>
                                            <td class="text-right" style="vertical-align:">{{ str_replace(',','.', number_format($keuangan->has($val_6['akun_kode']) ? $keuangan->get($val_6['akun_kode'])->{'keuangan_'.$index} : 0)) }}</td>
                                        @endforeach

                                        <td class="text-center font-weight-bold" style="vertical-align: middle;">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_target : 0 }}
                                        </td>

                                        <td class="text-center font-weight-bold" style="vertical-align: middle;">
                                            {{ str_replace(".", ",", sprintf('%.2f', isset($fisik[$val_6['akun_kode']]) ? collect($fisik[$val_6['akun_kode']])->sum() : 0)) }}
                                        </td>

                                        <td class="text-right font-weight-bold" style="vertical-align: middle;">
                                            {{ str_replace(',','.', number_format($keuangan->has($val_6['akun_kode']) ? $keuangan->get($val_6['akun_kode'])->keuangan_pagu : 0)) }}
                                        </td>
                                        
                                    </tr>
                                    @if($keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode))
                                        @foreach ($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] as $item)
                                            <tr style="line-height: 1;" class="small">
                                                <td><em>{{ $item->keluaran_nama??'' }}</em></td>
                                                <td class="text-center"><em>{{ $item->keluaran_target??'' }}</em></td>
                                                <td class="text-center"><em>{{ $item->keluaran_satuan??'' }}</em></td>
                                                <td colspan="39"></td>
                                            </tr>
                                        @endforeach                              
                                    @endif                              
                                <?php } } ?>

                            <?php } } ?>

                        <?php } } ?>
                
                <?php  } } ?>
            </tbody> 

        </table>
    </div>
</div>