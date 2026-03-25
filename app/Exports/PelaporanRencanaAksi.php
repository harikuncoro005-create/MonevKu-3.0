<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\Models\NomenklaturPerencanaan;
use App\Models\RefKode;
use App\Models\Keluaran;
use App\Models\Keuangan;
use App\Models\Fisik;
use App\Models\Instansi;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PelaporanRencanaAksi implements FromView, WithColumnWidths, WithColumnFormatting, WithStyles
{
    protected $instansi_kode;
    private $bulan;

    function __construct($instansi_kode)
    {
        $this->instansi_kode = $instansi_kode;
        
        $bulan = [
            '1' => 'Januari',
            '2' => 'Februari',
            '3' => 'Maret',
            '4' => 'April',
            '5' => 'Mei',
            '6' => 'Juni',
            '7' => 'Juli',
            '8' => 'Agustus',
            '9' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];

        $this->bulan = $bulan; 
    }

    public function columnWidths(): array
    {
        $widths = [
            'A' => 18,
            'B' => 36,
            'C' => 36,
            'D' => 8,
            'E' => 12,
        ];

        $lebar10 = explode(',', 'F,G,I,J,L,M,O,P,R,S,U,V,X,Y,AA,AB,AD,AE,AG,AH,AJ,AK,AM,AN,AP,AQ');
        foreach ($lebar10 as $col) {
            $widths[$col] = 10;
        }

        $lebar16 = explode(',', 'H,K,N,Q,T,W,Z,AC,AF,AI,AL,AO,AR');
        foreach ($lebar16 as $col) {
            $widths[$col] = 16;
        }

        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $range = 'A1:' . $highestColumn . $highestRow;

        $centerColumns = explode(',', 'D,E,F,G,I,J,L,M,O,P,R,S,U,V,X,Y,AA,AB,AD,AE,AG,AH,AJ,AK,AM,AN,AP,AQ,H,K,N,Q,T,W,Z,AC,AF,AI,AL,AO,AR');

        foreach ($centerColumns as $col) {
            $sheet->getStyle($col)->getAlignment()->applyFromArray([
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => false,
            ]);
        }

        $rightColumns = explode(',', 'H,K,N,Q,T,W,Z,AC,AF,AI,AL,AO,AR');

        foreach ($rightColumns as $col) {
            $sheet->getStyle($col)->getAlignment()->applyFromArray([
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => false,
            ]);
        }

        return [
            
            $range => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'alignment' => [
                    'wrapText' => true,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],

            '1:2' => [
                'font' => [
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'EFEFEF'],
                ],
            ],
            
            '1:5' => [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];

    }

    public function columnFormats(): array
    {
        $formats = [
            'A:E' => NumberFormat::FORMAT_TEXT,
        ];

        $kolomAngka = explode(',', 'H,K,N,Q,T,W,Z,AC,AF,AI,AL,AO,AR');

        foreach ($kolomAngka as $col) {
            $formats[$col] = '#,##0.00';
        }

        return $formats;
        
    }
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $instansi = Instansi::where('instansi_kode', $this->instansi_kode)->first();
        $sesi_kode = session('session_kode')->sesi_kode;
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi->instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->where('keluaran_jenis', 0)->get();
        $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi->instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->where('keuangan_jenis', 0)->get();
        $fisik = Fisik::where('fisik_instansi_kode', $instansi->instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_jenis', 0)->get();
        $keluaran_riil = Keluaran::where('keluaran_instansi_kode', $instansi->instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 2)->get()->groupBy('keluaran_subkegiatan_kode');
        

        if ($keluaran && $keuangan && $fisik) {
        
            $akun = [];

            $bidang_urusan = $keuangan->unique('keuangan_bidang_urusan_kode')->pluck('keuangan_bidang_urusan_kode');
            foreach ($bidang_urusan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $program = $keuangan->unique('keuangan_program_kode')->pluck('keuangan_program_kode');
            foreach ($program as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $kegiatan = $keuangan->unique('keuangan_kegiatan_kode')->pluck('keuangan_kegiatan_kode');
            foreach ($kegiatan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }
            
            $sub_kegiatan = $keuangan->unique('keuangan_subkegiatan_kode')->pluck('keuangan_subkegiatan_kode');
            foreach ($sub_kegiatan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $indexed = $ref_kode->pluck('kode_index');
            
            foreach ($akun as $key => $value) {
                $index = explode('.', $value['akun_kode']);
                
                foreach ($indexed as $idx => $i) {
                    if (count($index) == $i ) {
                        ${'akun_' . $i}[$value['akun_id']] = $value;
                        ${'akun_' . $i}[$value['akun_id']]['index'] = $index;
                        ${'akun_' . $i}[$value['akun_id']]['index_id'] = str_replace('.', '', $value['akun_kode']);
                        ${'akun_' . $i}[$value['akun_id']]['index_parent'] = implode('', array_slice($index, 0, $i == 5 ? 3 : (count($index)-1)));
                        ${'akun_' . $i}[$value['akun_id']]['last_id'] = end($index);
                    }
                }
                
            }

            foreach ($indexed as $i) {
                usort(${'akun_'.$i}, function($a, $b) {
                    return $a['last_id'] <=> $b['last_id'];
                });

                $akun_all['akun_'.$i] = ${'akun_'.$i};
            }
            
            // FISIK
            $fisik_target_bulan = [];
            $fisik = $fisik->groupBy('fisik_subkegiatan_kode');
            if ($fisik->isNotEmpty()) {
                foreach ($fisik as $key => $value) {
                    for ($i=1; $i <= 12; $i++) { 
                        $fisik_target_bulan[$key][$i] = array_sum($value->pluck('fisik_'.$i)->toArray());
                    }
                }
            }
            

            $data = [
                'keuangan' => $keuangan->keyBy('keuangan_subkegiatan_kode'),
                'akun' => $akun_all,
                'bulan' => $this->bulan,
                'instansi' => $instansi,
                'keluaran' => $keluaran->keyBy('keluaran_subkegiatan_kode'),
                'fisik' => $fisik_target_bulan,
                'keluaran_riil' => $keluaran_riil
            ];

            // $records =  Pembayaran::with('partisipan')->where('pembayaran_iuran_id', $this->id)->orderBy('pembayaran_tanggal','asc')->get();

            // $total = collect($records)->sum('pembayaran_jumlah');

            // $data['records'] = $records;
            // $data['total'] = $total;

            return view('user.components.export_laporan_rencana_aksi', $data);
        }

    }
    
    // /**
    // * @return \Illuminate\Support\Collection
    // */
    // public function collection()
    // {
    //     //
    // }
}
