
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta  http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>
    @page {
        margin: 0.5cm;
    }

    body {
        font-family: 'Helvetica', 'Arial', sans-serif;
        line-height: 1;
    }
    
    .bg-light { background-color: #e9e9e9; }
    .text-bold { font-weight: bold; }
    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .text-10 { font-size: 10px; }
    .text-11 { font-size: 11px; }
    .text-12 { font-size: 12px; }

    table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    th, td {
        border: 0.5pt solid #000;
        padding: 4px 2px;
        vertical-align: middle;
        
        word-wrap: break-word; 
        overflow-wrap: break-word;
        white-space: normal !important;
    }
    
</style>

<style>
    .footer-wrapper {
        width: 100%;
        margin-top: 20px;
        /* Menghindari tabel terpotong antar halaman */
        page-break-inside: avoid; 
    }

    .footer-section {
        margin-top: 30px;
        width: 100%;
        position: relative;
    }

    .container-referensi {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 300px;

        /* float: right;
        width: 300px;
        margin-top: 30px; */
    }

    .table-referensi {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .table-referensi tbody td {
        border: 1px solid #000000 !important;
        padding: 4px 8px !important;
        vertical-align: middle;
        font-size: 9pt !important; 
        line-height: 1;
    }

    .table-referensi thead th {
        border: 1px solid #000000 !important;
        padding: 4px 8px !important;
        font-size: 9pt !important;
        font-weight: bold;
        background-color: #e9e9e9;
    }
    
    .label-sangat-tinggi { background-color: #047857; color: white; }
    .label-tinggi        { background-color: #34D399; color: white; }
    .label-sedang        { background-color: #FBBF24; color: black; }
    .label-rendah        { background-color: #D97706; color: white; }
    .label-sangat-rendah { background-color: #DC2626; color: white; }
    
</style>

<body>
    <div class="text-center">
        <div style="margin-bottom: 10px">
            <img src="{{ public_path('assets/img/pemkab_logo.png') }}" style="width: 50px;">
        </div>
        <div class="text-bold">{{ $title }}</div>
        <div class="text-bold">{{ strtoupper($instansi->instansi_nama) }}</div>
        <div>Sampai Dengan Bulan {{ $bulan }} Tahun Anggaran {{ $tahun }}</div>
    </div>
    <br>
    <div>
        <table>
            <thead>
                <tr class="text-center text-12 text-bold" style="background-color: #e9e9e9;">
                    <td rowspan="3" style="width: 5%">NO</td>
                    <td rowspan="3" style="width: 45%">SUB KEGIATAN</td>
                    <td colspan="4" style="width: 32%">NILAI (SCORE)</td>
                    <td rowspan="3" style="width: 18%">PREDIKAT KINERJA</td>
                </tr>
                <tr class="text-center text-12" style="background-color: #e9e9e9;">
                    <td>Fisik</td>
                    <td>Keuangan</td>
                    <td>Pelaporan</td>
                    <td>Total</td>
                </tr>
                <tr class="text-center text-12" style="background-color: #e9e9e9;">
                    <td>(40%)</td>
                    <td>(40%)</td>
                    <td>(20%)</td>
                    <td>(100%)</td>
                </tr>
            </thead>
            <tbody>
                @if ($subkegiatan)
                    @foreach ($subkegiatan as $item)
                    <tr class="text-12">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nomenklatur_nama }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['fisik_nilai'] }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['keuangan_nilai'] }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['pelaporan_nilai'] }}</td>
                        <td class="text-center">{{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['total_nilai'] }}</td>
                        <td class="text-center text-10 {{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['status_nilai']['class'] }}">
                            {{ $nilai_subkegiatan[str_replace('.','',$item->nomenklatur_kode)]['status_nilai']['nama'] }}
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="7" class="text-center"><em>Data Tidak Ditemukan</em></td>
                    </tr>
                @endif
                    <tr class="text-12">
                        <td colspan="2" style="background-color: #e9e9e9;"><strong>Nilai Rata - Rata</strong></td>
                        <td class="text-center" style="background-color: #e9e9e9;"><strong>{{ $nilai_rata['fisik'] }}</strong></td>
                        <td class="text-center" style="background-color: #e9e9e9;"><strong>{{ $nilai_rata['keuangan'] }}</strong></td>
                        <td class="text-center" style="background-color: #e9e9e9;"><strong>{{ $nilai_rata['pelaporan'] }}</strong></td>
                        <td class="text-center" style="background-color: #e9e9e9;"><strong>{{ $nilai_rata['total'] }}</strong></td>
                        <td class="text-center text-10 {{ $nilai_rata['status']['class'] }}"><strong>{{ $nilai_rata['status']['nama'] }}</strong></td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="footer-wrapper">
        <div class="container-referensi">
            <table class="table-referensi">
                <thead>
                    <tr>
                        <th colspan="2" class="bg-light">KET. PREDIKAT KINERJA</th>
                    </tr>
                    <tr class="bg-light">
                        <th style="width: 45%">Rentang Nilai</th>
                        <th style="width: 55%">Predikat</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">91 - 100</td>
                        <td class="text-center label-sangat-tinggi">Sangat Tinggi</td>
                    </tr>
                    <tr>
                        <td class="text-center">76 - 90</td>
                        <td class="text-center label-tinggi">Tinggi</td>
                    </tr>
                    <tr>
                        <td class="text-center">66 - 75</td>
                        <td class="text-center label-sedang">Sedang</td>
                    </tr>
                    <tr>
                        <td class="text-center">51 - 65</td>
                        <td class="text-center label-rendah">Rendah</td>
                    </tr>
                    <tr>
                        <td class="text-center">0 - 50</td>
                        <td class="text-center label-sangat-rendah">Sangat Rendah</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

