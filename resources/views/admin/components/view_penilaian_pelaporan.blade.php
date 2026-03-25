<style>
.sticky-col {
    position: -webkit-sticky;
    position: sticky;
    background-color: white !important; /* Agar tidak transparan saat ditimpa */
    z-index: 2;
}

/* Atur posisi horizontal masing-masing kolom */
/* Sesuaikan nilai 'left' dengan lebar kolom Anda */

.first-col {
    left: 0;
    z-index: 3; /* Lebih tinggi agar tidak tertutup kolom 2 */
}

.second-col {
    left: 7.5rem; /* Contoh: jika lebar kolom pertama 100px */
}

.third-col {
    left: 22.5rem; /* Contoh: jika lebar kolom pertama + kedua = 200px */
}

/* Khusus Header agar tetap di atas saat scroll vertikal (opsional) */
thead th.sticky-col {
    z-index: 10;
    top: 0;
}
</style>

<div class="bg-white rounded my-3 shadow-sm">
    <div class="table-responsive" style="max-height: 35rem; overflow-y: auto">
        <table class="table table-sm table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center small">
                    <th class="sticky-col first-col" rowspan="3" style="vertical-align: middle; min-width: 7.5rem">KODE</th>
                    <th class="sticky-col second-col" rowspan="3" style="vertical-align: middle; min-width: 15rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</th>
                    <th class="sticky-col third-col" rowspan="3" style="vertical-align: middle; min-width: 5rem;">PAGU</th>
                    <th colspan="4" style="vertical-align: middle; min-width: 14rem;">TARGET</th>
                    <th colspan="4" style="vertical-align: middle; min-width: 14rem;">REALISASI</th>
                    <th colspan="4" style="vertical-align: middle; min-width: 14rem;">DEVIASI</th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 10rem;">PERMASALAHAN</th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 10rem;">TINDAK LANJUT</th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 8rem;">VERIFIKASI <div>({{ $jumlah_verifikasi }}/{{ $jumlah_subkegiatan }})</div></th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 10rem;">CATATAN</th>
                </tr>
                <tr class="text-center small">
                    <th rowspan="2" style="vertical-align: middle; min-width: 4rem;">Keluaran</th>
                    <th colspan="2" style="vertical-align: middle; min-width: 6rem;">Keuangan</th>
                    <th rowspan="2" style="vertical-align: middle; min-width: 4rem;">Fisik</th>
                    <th rowspan="2" style="vertical-align: middle; min-width: 4rem;">Keluaran</th>
                    <th colspan="2" style="vertical-align: middle; min-width: 6rem;">Keuangan</th>
                    <th rowspan="2" style="vertical-align: middle; min-width: 4rem;">Fisik</th>
                    <th rowspan="2" style="vertical-align: middle; min-width: 4rem;">Keluaran</th>
                    <th colspan="2" style="vertical-align: middle; min-width: 6rem;">Keuangan</th>
                    <th rowspan="2" style="vertical-align: middle; min-width: 4rem;">Fisik</th>
                </tr>
                <tr class="text-center small">
                    <th style="vertical-align: middle; min-width: 4rem;">Rp</th>
                    <th style="vertical-align: middle; min-width: 2rem;">%</th>
                    <th style="vertical-align: middle; min-width: 4rem;">Rp</th>
                    <th style="vertical-align: middle; min-width: 2rem;">%</th>
                    <th style="vertical-align: middle; min-width: 4rem;">Rp</th>
                    <th style="vertical-align: middle; min-width: 2rem;">%</th>
                </tr>
            </thead>
            <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
                
                <?php if(count($akun['akun_2']) == 0) { ?>
                <tr class="">
                    <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
                </tr>
                <?php } else {
                    foreach ($akun['akun_2'] as $val_2) { ?>
                        <tr style="line-height: 1;" class="alert-success font-weight-bold small">
                            <td class="sticky-col first-col"><?= $val_2['akun_kode'] ?></td>
                            <td class="sticky-col second-col"><strong><?= $val_2['akun_nama'] ?></strong></td>
                            <td class="text-right sticky-col third-col" style="vertical-align: middle;">
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
                                <td class="sticky-col first-col"><?= $val_3['akun_kode'] ?></td>
                                <td class="sticky-col second-col"><?= $val_3['akun_nama'] ?></td>
                                <td class="text-right sticky-col third-col">
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
                                    <td class="sticky-col first-col"><?= $val_5['akun_kode'] ?></td>
                                    <td class="sticky-col second-col"><?= $val_5['akun_nama'] ?></td>
                                    <td class="text-right sticky-col third-col">
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
                                        <td class="sticky-col first-col"><?= $val_6['akun_kode'] ?></td>
                                        <td class="sticky-col second-col"><div><?= $val_6['akun_nama'] ?></div></td>
                                        <td class="text-right sticky-col third-col">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}</td>

                                        {{-- TARGET --}}
                                        <td class="text-center">
                                            {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                        </td>

                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])) }}
                                        </td>

                                        <td class="text-center">{{ $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round(($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2) }}</td>

                                        <td class="text-center">{{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? $fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0 }}</td>

                                        {{-- REALISASI --}}
                                        <td class="text-center">
                                            {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                        </td>

                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0))) }}
                                        </td>

                                        <td class="text-center">
                                            {{ $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round((($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2)  }}
                                        </td>
                                        
                                        <td class="text-center">
                                            {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0 }}
                                        </td>

                                        {{-- DEVIASI --}}
                                        <td class="text-center">
                                            {{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0) }}
                                        </td>

                                        <td class="text-right">{{ str_replace(',','.', number_format(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)-($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0))) }}</td>

                                        <td class="text-center">
                                            {{ round((
                                            ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round((($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2))
                                            -
                                            ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round((($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2)) ),2)
                                            }}
                                        </td>

                                        <td class="text-center">
                                            {{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? $fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0) }}
                                        </td>

                                        {{-- PERMASALAHAN --}}
                                        <td>
                                            <div style="max-height: 10rem; overflow-y:auto"><em>
                                                {!! $permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index) ? json_decode(data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_deskripsi) : '<div class="text-gray-400"><em>Data Belum Lengkap</em></div>' !!}</em>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="max-height: 10rem; overflow-y:auto"><em>
                                                {!! $permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index) ? json_decode(data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_tindaklanjut) : '<div class="text-gray-400"><em>Data Belum Lengkap</em></div>' !!}</em>
                                            </div>
                                        </td>

                                        <input type="hidden" name="subkegiatan[]" class="form-control" value="{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}">

                                        @if ($permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index))
                                            <input type="hidden" name="kode_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control" value="1">
                                            <input type="hidden" name="id_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" value="{{ data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_id }}">
                                        @else
                                            <input type="hidden" name="kode_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control" value="0">
                                            <input type="hidden" name="instansi_kode_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control" value="{{ $instansi_kode }}">
                                            <input type="hidden" name="bulan_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control" value="{{ $bulan_index }}">
                                            <input type="hidden" name="subkegiatan_kode_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control" value="{{  $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode}}">
                                        @endif

                                        <td>
                                            @php
                                                $verifikasi_status = data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_verifikasi ?? 0;

                                                $deviasi_keluaran_nilai = (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0);

                                                $deviasi_keluaran = $deviasi_keluaran_nilai == 0 ? 1 : 0;

                                                $deviasi_keuangan_nilai = round((
                                                ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round((($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2))
                                                -
                                                ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round((($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2)) ),2);

                                                $deviasi_keuangan = $deviasi_keuangan_nilai > -10 ? 1 : 0;

                                                $deviasi_fisik_nilai = (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? $fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0);

                                                $deviasi_fisik = $deviasi_fisik_nilai == 0 ? 1 : 0;

                                            @endphp
                                            
                                            <div>
                                                @if ($permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index))

                                                    @if (data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_verifikasi == 0)
                                                        <select name="verifikasi_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm verifikasi text-center {{ $deviasi_keluaran && $deviasi_keuangan && $deviasi_fisik ? 'alert-success' : 'alert-danger' }}">
                                                            @if ($deviasi_keluaran && $deviasi_keuangan && $deviasi_fisik)
                                                                <option value="1" selected>Tepat</option>
                                                                <option value="2">Tidak Tepat</option>
                                                            @else
                                                                <option value="1">Tepat</option>
                                                                <option value="2" selected>Tidak Tepat</option>
                                                            @endif
                                                            
                                                        </select>
                                                    @else
                                                        <select name="verifikasi_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm verifikasi text-center {{ data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_verifikasi == 1 ? 'alert-success' : 'alert-danger' }}">
                                                            <option value="1" {{ data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_verifikasi == 1 ? 'selected' : '' }}>Tepat</option>
                                                            <option value="2" {{ data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_verifikasi == 2 ? 'selected' : '' }}>Tidak Tepat</option>
                                                        </select>
                                                    @endif
                                                @else
                                                    <select name="verifikasi_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm verifikasi text-center alert-danger">
                                                        <option value="1">Tepat</option>
                                                        <option value="2" selected>Tidak Tepat</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </td>

                                        <td>
                                            @if ($verifikasi_status == 0)
                                                @if ($permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index))
                                                    @if ($deviasi_keluaran && $deviasi_keuangan && $deviasi_fisik)
                                                        <div style="max-height: 10rem; overflow-y:auto">
                                                            <textarea name="catatan_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm" placeholder="Catatan..." style="font-style: italic; line-height:1">Terima Kasih Telah Melaporkan dengan Tepat</textarea>
                                                        </div>
                                                    @else
                                                        <div style="max-height: 10rem; overflow-y:auto">
                                                            <textarea name="catatan_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm" placeholder="Catatan..." style="font-style: italic; line-height:1">
                                                                {{ 'Terdapat Deviasi '.($deviasi_keluaran ? '' : 'Keluaran '.$deviasi_keluaran_nilai.';').($deviasi_keuangan ? '' : 'Keuangan '.$deviasi_keuangan_nilai.';').($deviasi_fisik ? '' : 'Fisik '.$deviasi_fisik_nilai.';') }}
                                                            </textarea>
                                                        </div>
                                                    @endif
                                                @else
                                                    <div style="max-height: 10rem; overflow-y:auto">
                                                        <textarea name="catatan_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm" placeholder="Catatan..." style="font-style: italic; line-height:1">Permasalahan dan Tindak Lanjut Tidak Diisi</textarea>
                                                    </div>
                                                @endif
                                            @else
                                                <div style="max-height: 10rem; overflow-y:auto">
                                                    <textarea name="catatan_{{ str_replace('.', '', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) }}" class="form-control form-control-sm" placeholder="Catatan...">{{ data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_catatan }}</textarea>
                                                </div>
                                            @endif

                                        </td>
                                    </tr>                               
                                <?php } } ?>

                            <?php } } ?>

                        <?php } } ?>
                
                <?php  } } ?>
            </tbody>  

        </table>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        autosize($('textarea'))
        $(".verifikasi").on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue == 0) {
                $(this).addClass('alert-danger').removeClass('alert-success');
            } else {
                $(this).removeClass('alert-danger').addClass('alert-success');
            }
            $(this).blur();
        });
    })
</script>