<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> --}}

{{-- <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    /* Penting untuk PDF: cegah tabel terpotong */
    table, tr, td, th {
        page-break-inside: avoid;
    }
</style> --}}

{{-- <style>
        /* Pengaturan Dasar PDF */
        @page {
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        /* Style Tabel APBD */
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Menjaga kolom tetap rapi */
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            border: 1px solid #000;
            padding: 5px;
        }
        td {
            border: 1px solid #000;
            padding: 5px;
            word-wrap: break-word; /* Mencegah teks meluap keluar kolom */
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        
        /* Baris Total */
        .row-total {
            background-color: #eee;
            font-weight: bold;
        }
    </style> --}}

    <style>

    @page {
        margin: 0.5cm;
    }
    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        font-size: 5.5pt; 
        line-height: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th, td {
        border: 0.5pt solid #000;
        padding: 2px;
        vertical-align: middle;
        /* Teknik pembungkusan teks per kata */
        word-wrap: break-word; 
        overflow-wrap: break-word;
        white-space: normal !important;
    }

    /* table, th, td {
        border: 1px solid black;
    } */
    
    /* Header Tabel Lebih Kecil */
    thead th {
        border: 0.5pt solid #000;
        padding: 1px 2px; /* Padding sangat minim */
        font-size: 6pt;    /* Font header lebih kecil dari body */
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
        /* word-wrap: break-word; */
    }

    /* thead th {
        background-color: #f2f2f2;
        text-align: center;
        font-weight: bold;
    } */

    /* td {
        border: 0.5pt solid #000;
        padding: 2px;
        vertical-align: top;
        word-wrap: normal;           
        overflow-wrap: break-word;    
        white-space: normal;       
        vertical-align: top;
    } */

    

    .text-center { text-align: center; }
    .text-right { text-align: right; }
</style>


<body>
    <div style="line-height: 1.2">
        <div style="text-align: center; font-size: 16px"><strong>{{ $title }}</strong></div>
        <div style="text-align: center; font-size: 16px">{{ $instansi->instansi_nama }}</div>
        <div style="text-align: center; font-size: 14px">Sampai Dengan Bulan {{ $bulan[$bulan_index] }}</div>
    </div>
    <br>
    <br>
    <br>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered">
            <thead>
                <tr class="text-center small">
                    <th rowspan="3" style="vertical-align: middle; width: 6%;">KODE</th>
                    <th rowspan="3" style="vertical-align: middle; width: 12%;">PROGRAM / KEGIATAN / SUB KEGIATAN</th>
                    <th rowspan="3" style="vertical-align: middle; width: 8%">INDIKATOR KINERJA</th>
                    <th rowspan="3" style="vertical-align: middle; width: 4%">TARGET</th>
                    <th rowspan="3" style="vertical-align: middle; width: 4%">SATUAN</th>
                    <th rowspan="3" style="vertical-align: middle; width: 5%">PAGU</th>
                    <th colspan="4" style="vertical-align: middle; width: 15%;">TARGET</th>
                    <th colspan="4" style="vertical-align: middle; width: 15%;">REALISASI</th>
                    <th colspan="4" style="vertical-align: middle; width: 15%;">DEVIASI</th>
                    <th rowspan="3" style="vertical-align: middle; width: 8%;">PERMASALAHAN</th>
                    <th rowspan="3" style="vertical-align: middle; width: 8%;">TINDAK LANJUT</th>
                </tr>
                <tr class="text-center small">
                    <th rowspan="2" style="vertical-align: middle; width: 2%">Keluaran</th>
                    <th colspan="2" style="vertical-align: middle; width: 9%">Keuangan</th>
                    <th rowspan="2" style="vertical-align: middle; width: 4%">Fisik</th>
                    <th rowspan="2" style="vertical-align: middle; width: 2%">Keluaran</th>
                    <th colspan="2" style="vertical-align: middle; width: 9%">Keuangan</th>
                    <th rowspan="2" style="vertical-align: middle; width: 4%">Fisik</th>
                    <th rowspan="2" style="vertical-align: middle; width: 2%">Keluaran</th>
                    <th colspan="2" style="vertical-align: middle; width: 9%">Keuangan</th>
                    <th rowspan="2" style="vertical-align: middle; width: 4%">Fisik</th>
                </tr>
                <tr class="text-center small">
                    <th style="vertical-align: middle; width: 6%;">Rp</th>
                    <th style="vertical-align: middle; width: 3%;">%</th>
                    <th style="vertical-align: middle; width: 6%;">Rp</th>
                    <th style="vertical-align: middle; width: 3%;">%</th>
                    <th style="vertical-align: middle; width: 6%;">Rp</th>
                    <th style="vertical-align: middle; width: 3%;">%</th>
                </tr>
            </thead>
            <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
                
                <?php if(count($akun['akun_2']) == 0) { ?>
                <tr class="">
                    <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
                </tr>
                <?php } else {
                    foreach ($akun['akun_2'] as $val_2) { ?>
                        <tr style="line-height: 1; background-color: #D1E7DD">
                            <td><strong><?= $val_2['akun_kode'] ?></strong></td>
                            <td style="text-align: justify; text-justify: inter-word;"><strong><?= $val_2['akun_nama'] ?></strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right" style="vertical-align: middle;"><strong>
                                @php
                                    $keuangan_2 = array_filter($keuangan->toArray(), function ($var) use ($val_2) {
                                        return $var['keuangan_bidang_urusan_kode'] == $val_2['akun_kode'];
                                    });
                                    
                                    $bidang_urusan = collect($keuangan_2)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($bidang_urusan));

                                @endphp
                                </strong>
                            </td>
                            <td colspan="14"></td>
                        </tr>
                        <?php 
                            $akun_3 = array_filter($akun['akun_3'], function ($var) use ($val_2) {
                                return $var['index_parent'] == $val_2['index_id'];
                            });
                        ?>

                        <?php if(count($akun_3) > 0) {
                            foreach ($akun_3 as $val_3) { ?>
                            <tr style="line-height: 1; background-color: #FFF3CD">
                                <td><strong><?= $val_3['akun_kode'] ?></strong></td>
                                <td><strong><?= $val_3['akun_nama'] ?></strong></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right"><strong>
                                    @php
                                        $keuangan_3 = array_filter($keuangan->toArray(), function ($var) use ($val_3) {
                                            return $var['keuangan_program_kode'] == $val_3['akun_kode'];
                                        });
                                        
                                        $program = collect($keuangan_3)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($program));
                
                                    @endphp
                                    </strong>
                                </td>
                                <td colspan="14"></td>
                            </tr>
                            <?php 
                                $akun_5 = array_filter($akun['akun_5'], function ($var) use ($val_3) {
                                    return $var['index_parent'] == $val_3['index_id'];
                                });
                            ?>

                            <?php if(count($akun_5) > 0) {
                                foreach ($akun_5 as $val_5) { ?> 
                                <tr style="line-height: 1; background-color: #CFE2FF">
                                    <td><?= $val_5['akun_kode'] ?></td>
                                    <td><div><?= $val_5['akun_nama'] ?></div></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right"><strong>
                                        @php
                                            $keuangan_5 = array_filter($keuangan->toArray(), function ($var) use ($val_5) {
                                                return $var['keuangan_kegiatan_kode'] == $val_5['akun_kode'];
                                            });
                                            
                                            $kegiatan = collect($keuangan_5)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($kegiatan));
                                        
                                        @endphp
                                        </strong>
                                    </td>
                                    <td colspan="14"></td>
                                </tr>
                                <?php 
                                    $akun_6 = array_filter($akun['akun_6'], function ($var) use ($val_5) {
                                        return $var['index_parent'] == $val_5['index_id'];
                                    });
                                ?>
                                <?php if(count($akun_6) > 0) {
                                    foreach ($akun_6 as $val_6) { ?>
                                    <tr style="line-height: 1;">
                                        <td rowspan="{{ $keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) ? count($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])+1 : '' }}" style="white-space: nowrap;">
                                            <?= $val_6['akun_kode'] ?>
                                        </td>

                                        <td rowspan="{{ $keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) ? count($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])+1 : '' }}">
                                            <em><?= $val_6['akun_nama'] ?></em>
                                        </td>

                                        <td>
                                            {{ $keluaran[$val_6['akun_kode']]->keluaran_nama ?? '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $keluaran[$val_6['akun_kode']]->keluaran_target ?? '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $keluaran[$val_6['akun_kode']]->keluaran_satuan ?? '' }}
                                        </td>

                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}
                                        </td>

                                        {{-- TARGET --}}
                                        <td class="text-center">
                                            {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                        </td>

                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])) }}
                                        </td>

                                        <td class="text-center">
                                            {{ str_replace('.',',',$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round(($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2)) }}
                                        </td>

                                        <td class="text-center">
                                            {{ str_replace(".", ",", sprintf('%.2f', array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? $fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0)) }}
                                        </td>

                                        {{-- REALISASI --}}
                                        <td class="text-center">
                                            {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                        </td>

                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0))) }}
                                        </td>

                                        <td class="text-center">
                                            {{ str_replace('.',',', $keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round((($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2))  }}
                                        </td>
                                        
                                        <td class="text-center">
                                            {{ str_replace(".", ",", sprintf('%.2f', array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] : 0)) }}
                                        </td>

                                        {{-- DEVIASI --}}
                                        <td class="text-center" style="{{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0) ? 'background-color: #FECACA' : '' }}">
                                            {{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_target_bulan) ? $keluaran_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0) }}
                                        </td>

                                        <td class="text-right" style="{{ str_replace(',','.', number_format(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)-($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)))  < 0 ? 'background-color: #FECACA' : '' }}">
                                            {{ str_replace(',','.', number_format(($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)-($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0))) }}
                                        </td>

                                        <td class="text-center" style="{{ (($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round((($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2))
                                        -
                                        ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round((($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2))) < 0 ? 'background-color: #FECACA' : '' }}">
                                        
                                        {{
                                        str_replace('.',',', ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0  ? number_format(round((($keuangan_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu,2),2) : number_format(round(0,2),2))
                                        -
                                        ($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu != 0 ? (number_format(round((($keuangan_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0)*100)/$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu, 2), 2)) : number_format(round(0,2),2)))
                                        }}</td>

                                        <td class="text-center" style="{{ (array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? ($fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0) : 0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? ($fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0) : 0) < 0 ? 'background-color: #FECACA' : '' }}">
                                            {{ str_replace(".", ",", sprintf('%.2f',(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_realisasi_bulan) ? ($fisik_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0) : 0)-(array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $fisik_target_bulan) ? ($fisik_target_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]??0) : 0))) }}
                                        </td>

                                        {{-- PERMASALAHAN --}}
                                        <td rowspan="{{ $keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) ? count($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])+1 : '' }}"><div style="line-height:1;">
                                            {!! $permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index) ? json_decode(data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_deskripsi) : '<div class="text-gray-400"><em>-</em></div>' !!}</div>
                                        </td>
                                        <td rowspan="{{ $keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) ? count($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode])+1 : '' }}"><div style="line-height:1;">
                                            {!! $permasalahan->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode) && data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index) ? json_decode(data_get($permasalahan->get($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode), $bulan_index)->permasalahan_tindaklanjut) : '<div class="text-gray-400"><em>-</em></div>' !!}</div>
                                        </td>

                                    </tr>
                                    @if($keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode))
                                        @foreach ($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] as $item)
                                            <tr style="color: grey">
                                                <td><em>{{ $item->keluaran_nama??'' }}</em></td>
                                                <td class="text-center"><em>{{ $item->keluaran_target??'' }}</em></td>
                                                <td class="text-center"><em>{{ $item->keluaran_satuan??'' }}</em></td>
                                                <td colspan="13"></td>
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







</body>
</html>
