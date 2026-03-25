<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

use App\Models\Fisik;
use App\Models\Instansi;
use App\Models\Keluaran;
use App\Models\Keuangan;
use App\Models\NomenklaturPerencanaan;
use App\Models\Permasalahan;
use App\Models\RefKode;

use App\Exports\PelaporanRencanaAksi;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    private $bulan;

    public function __construct()
    {
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

    public function laporan_apbd_bulanan(Request $request)
    {
        $instansi_kode = $request->pd;
        $sesi_kode = session('session_kode')->sesi_kode;
        $bulan = $request->bulan;
        $tahun = session('session_kode')->sesi_tahun;

        // return response("<script>window.close();</script>");

        // $data = [
        //     'title' => 'SELAMAT DATANG',
        //     'date' => date('m/d/Y'),
        //     'instansi_kode' => $instansi_kode
        // ]; 
              
        // $pdf = PDF::loadView('user/components/export_laporan_apbd_bulanan', $data);
        // return $pdf->download('itsolutionstuff.pdf');
        
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
        $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
        $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();
        $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->get();
        $keluaran_riil = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 2)->get()->groupBy('keluaran_subkegiatan_kode');
        

        if ($keluaran && $keuangan && $fisik && $permasalahan) {
        
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
            
            // KELUARAN
            $keluaran_target = $keluaran->filter(function ($item) {
                return $item->keluaran_jenis == 0;
            });
            
            $keluaran_target_bulan = [];

            if ($keluaran_target->isNotEmpty()) {
                foreach ($keluaran_target as $key => $value) {
                    ${'total_'.$value->keluaran_id} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                    }
                    $keluaran_target_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
                }
            }

            $keluaran_realisasi = $keluaran->filter(function ($item) {
                return $item->keluaran_jenis == 1;
            });
            
            $keluaran_realisasi_bulan = [];

            if ($keluaran_realisasi->isNotEmpty()) {
                foreach ($keluaran_realisasi as $key => $value) {
                    ${'total_'.$value->keluaran_id} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                    }
                    $keluaran_realisasi_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
                }
            }

            // KEUANGAN

            $keuangan_target = $keuangan->filter(function ($item) {
                return $item->keuangan_jenis == 0;
            });

            $keuangan_target_bulan = [];

            foreach ($keuangan_target as $key => $value) {
                ${'total_'.$value->keuangan_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                }
                $keuangan_target_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
            }

            $keuangan_realisasi = $keuangan->filter(function ($item) {
                return $item->keuangan_jenis == 1;
            });

            $keuangan_realisasi_bulan = [];

            if ($keuangan_realisasi->isNotEmpty()) {
                foreach ($keuangan_realisasi as $key => $value) {
                    ${'total_'.$value->keuangan_id} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                    }
                    $keuangan_realisasi_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
                }
            }

            // FISIK

            $fisik_target = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 0;
            });

            $fisik_target_bulan = [];
            $fisik_target = $fisik_target->groupBy('fisik_subkegiatan_kode');

            if ($fisik_target->isNotEmpty()) {
                foreach ($fisik_target as $key => $value) {
                    ${'total_'.str_replace('.', '', $key)} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                    }
                    $fisik_target_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
                }
            }

            $fisik_realisasi = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 1;
            });

            $fisik_realisasi_bulan = [];
            $fisik_realisasi = $fisik_realisasi->groupBy('fisik_subkegiatan_kode');
            
            if ($fisik_realisasi->isNotEmpty()) {
                foreach ($fisik_realisasi as $key => $value) {
                    ${'total_'.str_replace('.', '', $key)} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                    }
                    $fisik_realisasi_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
                }
            }

            // PERMASALAHAN

            $permasalahan = $permasalahan->groupBy('permasalahan_subkegiatan_kode')->map(function ($item) {
                return $item->keyBy('permasalahan_bulan');
            });

            $data = [
                'title' => 'LAPORAN REALISASI PELAKSANAAN PROGRAM DAN KEGIATAN APBD TAHUN '.$tahun,
                'url_export' => '/export-laporan-apbd-bulanan',
                'keluaran' => $keluaran_target->keyBy('keluaran_subkegiatan_kode'),
                'keuangan' => $keuangan_target,
                'akun' => $akun_all,
                'bulan' => $this->bulan,
                'bulan_index' => $bulan,
                'instansi' => $instansi,
                'instansi_kode' => $instansi_kode,
                'subkegiatan_kode' => $keuangan_target->pluck('keuangan_subkegiatan_kode'),
                'jumlah_subkegiatan' => $keuangan_target->count(),
                'keluaran_target_bulan' => $keluaran_target_bulan,
                'keluaran_realisasi_bulan' => $keluaran_realisasi_bulan,
                'keuangan_target_bulan' =>  $keuangan_target_bulan,
                'keuangan_realisasi_bulan' =>  $keuangan_realisasi_bulan,
                'fisik_target_bulan' => $fisik_target_bulan,
                'fisik_realisasi_bulan' => $fisik_realisasi_bulan,
                'permasalahan' => $permasalahan,
                'tahun' => $tahun,
                'keluaran_riil' => $keluaran_riil
            ];

            $filename = 'MONEVKU - Laporan APBD Bulan '.$this->bulan[$bulan].' '.$tahun.' - '.$instansi->instansi_nama.'.pdf';

            $pdf = PDF::loadView('user/components/export_laporan_apbd_bulanan', $data);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->stream($filename);
        } else {
            return response("<script>window.close();</script>");
        }

        

        

        // echo json_encode(['html' => $html]);

        // $data = [
        //     'title' => 'SELAMAT DATANG',
        //     'date' => date('m/d/Y'),
        // ]; 
              
        // $pdf = PDF::loadView('user/components/export_laporan_apbd_bulanan', $data);
        
    }

    public function laporan_rencana_aksi(Request $request)
    {
        $instansi = Instansi::where('instansi_kode', $request->pd)->first();
        $tahun = session('session_tahun');
        $tanggal = \Carbon\Carbon::now()->format('d m Y');
        
        $filename = 'MONEVKU - Laporan Rencana Aksi APBD Tahun '.$tahun.' - '.$instansi->instansi_nama;
        return Excel::download(new PelaporanRencanaAksi($instansi->instansi_kode), $filename . '.xlsx');
    }

    public function laporan_lampiran_kinerja(Request $request)
    {
        $instansi = Instansi::where('instansi_kode', $request->pd)->first();
        $sesi_kode = session('session_kode')->sesi_kode;
        $tahun = session('session_tahun');
        $bulan = $request->bulan;
        $tanggal = \Carbon\Carbon::now()->format('d m Y');

        // KEUANGAN

        $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi->instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();

        $subkegiatan = $keuangan->pluck('keuangan_subkegiatan_kode')->unique()->values()->toArray();
        $nomenklatur = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $subkegiatan)->get()->keyBy('nomenklatur_kode');

        $penilaian_keuangan_bulan_target = [];

        $penilaian_keuangan_target = $keuangan->filter(function($item){
            return $item->keuangan_jenis == 0;
        });

        $penilaian_keuangan_target = $penilaian_keuangan_target->groupBy('keuangan_subkegiatan_kode');

        foreach ($penilaian_keuangan_target as $key => $value) {
            if ($value) {
                $value_sum = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    $value_sum += array_sum($value->pluck('keuangan_'.$i)->toArray());
                }

                $penilaian_keuangan_bulan_target[$key] = $value_sum;
            }
        }

        $penilaian_keuangan_bulan_realisasi = [];

        $penilaian_keuangan_realisasi = $keuangan->filter(function($item){
            return $item->keuangan_jenis == 1;
        });

        $penilaian_keuangan_realisasi = $penilaian_keuangan_realisasi->groupBy('keuangan_subkegiatan_kode');

        if ($penilaian_keuangan_realisasi->isNotEmpty()) {
            foreach ($penilaian_keuangan_realisasi as $key => $value) {
                if ($value) {
                    $value_sum = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        $value_sum += array_sum($value->pluck('keuangan_'.$i)->toArray());
                    }

                    $penilaian_keuangan_bulan_realisasi[$key] = $value_sum;
                }
            }
        }

        // FISIK

        $fisik = Fisik::where('fisik_instansi_kode', $instansi->instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();

        $penilaian_fisik_bulan_target = [];

        $penilaian_fisik_target = $fisik->filter(function($item){
            return $item->fisik_jenis == 0;
        });

        $penilaian_fisik_target = $penilaian_fisik_target->groupBy('fisik_subkegiatan_kode');

        if ($penilaian_fisik_target->isNotEmpty()) {
            foreach ($penilaian_fisik_target as $key => $value) {
                if ($value) {
                    $value_sum = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        $value_sum += array_sum($value->pluck('fisik_'.$i)->toArray());
                    }

                    $penilaian_fisik_bulan_target[$key] = $value_sum;
                }
            }
        }

        $penilaian_fisik_bulan_realisasi = [];

        $penilaian_fisik_realisasi = $fisik->filter(function($item){
            return $item->fisik_jenis == 1;
        });

        $penilaian_fisik_realisasi = $penilaian_fisik_realisasi->groupBy('fisik_subkegiatan_kode');

        if ($penilaian_fisik_realisasi->isNotEmpty()) {
            foreach ($penilaian_fisik_realisasi as $key => $value) {
                if ($value) {
                    $value_sum = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        $value_sum += array_sum($value->pluck('fisik_'.$i)->toArray());
                    }

                    $penilaian_fisik_bulan_realisasi[$key] = $value_sum;
                }
            }
        }

        // PERMASALAHAN
        $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi->instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->where('permasalahan_bulan', $bulan)->get();
        $permasalahan = $permasalahan->filter(function($item){
            return $item->permasalahan_verifikasi != 0;
        });
        $permasalahan =  $permasalahan->keyBy('permasalahan_subkegiatan_kode');

        $result = [];

        foreach ($subkegiatan as $item) {
            $data = [
                'subkegiatan_kode' => $item,
                'subkegiatan_nama' => $nomenklatur[$item]->nomenklatur_nama
            ];

            $fisik_target = $penilaian_fisik_bulan_target[$item] ?? 0;
            $fisik_realisasi = $penilaian_fisik_bulan_realisasi[$item] ?? 0;
            $fisik_nilai = $fisik_target == 0 ? 1 : number_format($fisik_realisasi/$fisik_target, 2);
            $total_nilai_fisik = ceil($fisik_nilai > 1 ? 40 : $fisik_nilai*40);
            $data['fisik_nilai'] = $total_nilai_fisik;

            $keuangan_target = $penilaian_keuangan_bulan_target[$item] ?? 0;
            $keuangan_realisasi = $penilaian_keuangan_bulan_realisasi[$item] ?? 0;
            $keuangan_nilai = $keuangan_target == 0 ? 1 : number_format($keuangan_realisasi/$keuangan_target, 2);
            $total_nilai_keuangan = ceil($keuangan_nilai > 1 ? 40 : $keuangan_nilai*40);
            $data['keuangan_nilai'] = $total_nilai_keuangan;

            $total_nilai_pelaporan = isset($permasalahan[$item]) ? ($permasalahan[$item]->permasalahan_verifikasi == 1 ? 20 : 0) : 0;
            // $total_nilai_pelaporan = 20;
            $data['pelaporan_nilai'] = $total_nilai_pelaporan;

            $total_nilai_subkegiatan = (float) $total_nilai_fisik + (float) $total_nilai_keuangan + (float)$total_nilai_pelaporan;
            $data['total_nilai'] = $total_nilai_subkegiatan;

            $status_nilai = $this->get_status_kinerja($total_nilai_subkegiatan);
            $data['status_nilai'] = $status_nilai;
            
            $result[str_replace('.','', $item)] = $data;
        } 

        $rata_rata_fisik = ceil(collect($result)->avg('fisik_nilai'));
        $rata_rata_keuangan = ceil(collect($result)->avg('keuangan_nilai'));
        $rata_rata_pelaporan = ceil(collect($result)->avg('pelaporan_nilai'));
        $rata_rata_total = $rata_rata_fisik + $rata_rata_keuangan + $rata_rata_pelaporan;
        $rata_rata_status = $this->get_status_kinerja($rata_rata_total);

        $nilai_rata = [
            'fisik' => $rata_rata_fisik,
            'keuangan' => $rata_rata_keuangan,
            'pelaporan' => $rata_rata_pelaporan,
            'total' => $rata_rata_total,
            'status' => $rata_rata_status
        ];

        $data = [
            'title' => 'TABEL KINERJA SUB KEGIATAN',
            'instansi' => $instansi,
            'bulan' => $this->bulan[$request->bulan],
            'tahun' => $tahun,
            'subkegiatan' => $nomenklatur,
            'nilai_subkegiatan' => $result,
            'nilai_rata' => $nilai_rata
        ];

        // dd($result);
        
        $filename = 'MONEVKU - Lampiran Kinerja APBD Tahun '.$tahun.' - '.$instansi->instansi_nama.'.pdf';

        $pdf = PDF::loadView('user/components/export_laporan_lampiran_kinerja', $data);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream($filename);
    }

    function get_status_kinerja($jumlah) {
        if ($jumlah <= 50) return ['nama' => 'SANGAT RENDAH', 'color' => '#DC2626', 'class' => 'label-sangat-rendah' ];
        if ($jumlah <= 65) return ['nama' => 'RENDAH', 'color' => '#D97706', 'class' => 'label-rendah'];
        if ($jumlah <= 75) return ['nama' => 'SEDANG', 'color' => '#FBBF24', 'class' => 'label-sedang'];
        if ($jumlah <= 90) return ['nama' => 'TINGGI', 'color' => '#34D399', 'class' => 'label-tinggi'];
        if ($jumlah > 90)  return ['nama' => 'SANGAT TINGGI', 'color' => '#047857', 'class' => 'label-sangat-tinggi'];
    }


}
