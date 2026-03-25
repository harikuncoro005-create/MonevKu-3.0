<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Bulan;
use App\Models\Fisik;
use App\Models\Instansi;
use App\Models\Iuran;
use App\Models\Keluaran;
use App\Models\Keuangan;
use App\Models\NomenklaturPerencanaan;
use App\Models\Partisipan;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use App\Models\PenilaianRencana;
use App\Models\Penyewa;
use App\Models\Permasalahan;
use App\Models\RefKode;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Warga;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class RenderController extends Controller
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

    public function view_dashboard_user(Request $request)
    {
        $parameter = $request->parameter;
        $instansi_kode = session('session_instansi');
        $tahun = session('session_tahun');
        $sesi_kode = session('session_kode')->sesi_kode;
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
        $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
        $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();
        // $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->get();

        if (array_key_exists('bulan', $parameter)) {
            $bulan = $parameter['bulan'];
        } else {
            $bulan = date('m');
        }

        if ($keuangan) {

            // KEUANGAN

            $keuangan_target = $keuangan->filter(function ($item) {
                return $item->keuangan_jenis == 0;
            });
            
            $akun = [];

            $bidang_urusan = $keuangan_target->unique('keuangan_bidang_urusan_kode')->pluck('keuangan_bidang_urusan_kode');
            foreach ($bidang_urusan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $program = $keuangan_target->unique('keuangan_program_kode')->pluck('keuangan_program_kode');
            foreach ($program as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $kegiatan = $keuangan_target->unique('keuangan_kegiatan_kode')->pluck('keuangan_kegiatan_kode');
            foreach ($kegiatan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }
            
            $sub_kegiatan = $keuangan_target->unique('keuangan_subkegiatan_kode')->pluck('keuangan_subkegiatan_kode');
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

            

            $keuangan_target_bulan = [];

            foreach ($keuangan_target as $key => $value) {
                ${'total_'.$value->keuangan_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                }
                $keuangan_target_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
            }

            $diagram_keuangan_target_bulan = [];
            $jumlah_keuangan_target = 0;
            for ($i=1; $i <= count($this->bulan); $i++) { 
                $jumlah_keuangan_target += array_sum($keuangan_target->pluck('keuangan_'.$i)->toArray());
                $diagram_keuangan_target_bulan[] = $jumlah_keuangan_target;
            }

            $keuangan_realisasi = $keuangan->filter(function ($item) {
                return $item->keuangan_jenis == 1;
            });

            $keuangan_realisasi_bulan = [];
            $diagram_keuangan_realisasi_bulan = [];
            $jumlah_keuangan_realisasi = 0;

            if (count($keuangan_realisasi) != 0) {
                foreach ($keuangan_realisasi as $key => $value) {
                    ${'total_'.$value->keuangan_id} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                    }
                    $keuangan_realisasi_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
                }

                for ($i=1; $i <= count($this->bulan); $i++) { 
                    $jumlah_keuangan_realisasi += array_sum($keuangan_realisasi->pluck('keuangan_'.$i)->toArray());
                    $diagram_keuangan_realisasi_bulan[] =  $jumlah_keuangan_realisasi;
                }
            }

            // KELUARAN

            $keluaran_target = $keluaran->filter(function ($item) {
                return $item->keluaran_jenis == 0;
            });
            
            $keluaran_target_bulan = [];

            if (count($keluaran_target) != 0) {
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

            if (count($keluaran_realisasi) != 0) {
                foreach ($keluaran_realisasi as $key => $value) {
                    ${'total_'.$value->keluaran_id} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                    }
                    $keluaran_realisasi_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
                }
            }
            

            // FISIK

            $fisik_target = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 0;
            });

            $fisik_target_bulan = [];
            $fisik_target = $fisik_target->groupBy('fisik_subkegiatan_kode');

            if ($fisik_target) {
                foreach ($fisik_target as $key => $value) {
                    ${'total_'.str_replace('.', '', $key)} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                    }
                    $fisik_target_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
                }
            }
            

            $diagram_fisik_target_bulan = [];
            $jumlah_fisik_target = 0;

            if (count($fisik_target) != 0) {
                for ($i=1; $i <= count($this->bulan); $i++) { 
                    $jumlah_fisik_target += array_sum($fisik_target->collapse()->pluck('fisik_'.$i)->toArray());
                    $diagram_fisik_target_bulan[] = round($jumlah_fisik_target/count($fisik_target),2);
                }
            }

            $fisik_realisasi = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 1;
            });

            $fisik_realisasi_bulan = [];
            $fisik_realisasi = $fisik_realisasi->groupBy('fisik_subkegiatan_kode');
            
            if (count($fisik_realisasi) != 0) {
                foreach ($fisik_realisasi as $key => $value) {
                    ${'total_'.str_replace('.', '', $key)} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                    }
                    $fisik_realisasi_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
                }
            }

            $diagram_fisik_realisasi_bulan = [];
            $jumlah_fisik_realisasi = 0;

            if (count($fisik_realisasi) != 0) {
                for ($i=1; $i <= count($this->bulan); $i++) { 
                    $jumlah_fisik_realisasi += array_sum($fisik_realisasi->collapse()->pluck('fisik_'.$i)->toArray());
                    $diagram_fisik_realisasi_bulan[] = round($jumlah_fisik_realisasi/count($fisik_target),2);
                }
            }

            $status_kinerja_keluaran = [];
            $status_kinerja_keuangan = [];
            $status_kinerja_fisik = [];

            foreach ($sub_kegiatan as $item) {

                if (array_key_exists($item, $keluaran_target_bulan) && $keluaran_target_bulan[$item] != 0) {
                    $persentase_keluaran = ((array_key_exists($item, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$item] : 0)*100)/$keluaran_target_bulan[$item];
                } else {
                    $persentase_keluaran = 100;
                }
            
                $status_kinerja_keluaran[$item] = $this->get_status_kinerja(number_format(round($persentase_keluaran, 2),2));

                if (array_key_exists($item, $keuangan_target_bulan) && $keuangan_target_bulan[$item] != 0) {
                    $persentase_keuangan = ((array_key_exists($item, $keuangan_realisasi_bulan) ? $keuangan_realisasi_bulan[$item] : 0)*100)/$keuangan_target_bulan[$item];
                } else {
                    $persentase_keuangan = 100;
                }
            
                $status_kinerja_keuangan[$item] = $this->get_status_kinerja(number_format(round($persentase_keuangan, 2),2));

                if (array_key_exists($item, $fisik_target_bulan) && $fisik_target_bulan[$item] != 0) {
                    $persentase_fisik = ((array_key_exists($item, $fisik_realisasi_bulan) ? $fisik_realisasi_bulan[$item] : 0)*100)/$fisik_target_bulan[$item];
                } else {
                    $persentase_fisik = 100;
                }
            
                $status_kinerja_fisik[$item] = $this->get_status_kinerja(number_format(round($persentase_fisik, 2),2));


            }

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'instansi' => $instansi,
                'status_kinerja_keluaran' => $status_kinerja_keluaran,
                'status_kinerja_keuangan' => $status_kinerja_keuangan,
                'status_kinerja_fisik' => $status_kinerja_fisik,
            ];


            $data_diagram = [
                'bulan' => array_values($this->bulan),
                'keuangan_title' => 'REALISASI KEUANGAN',
                'keuangan_target' => $diagram_keuangan_target_bulan,
                'keuangan_realisasi' => $diagram_keuangan_realisasi_bulan,
                'fisik_title' => 'REALISASI FISIK',
                'fisik_target' => $diagram_fisik_target_bulan,
                'fisik_realisasi' => $diagram_fisik_realisasi_bulan,
            ];

            $html = view('user/components/view_dashboard_user', $data)->render();
            $diagram = view('user/components/view_diagram_dashboard_user', $data_diagram)->render();
            // $html = 'ok';
            
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
            $diagram = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html, 'diagram' => $diagram]);

    }

    public function view_nomenklatur_perencanaan(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined' || $value == 'kode' ) {
                    continue;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = 10;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $result = NomenklaturPerencanaan::orderBy('nomenklatur_kode','asc')->filter($param)->get();

        if (array_key_exists('kode', $parameter)) {

            $query = array_filter($result->toArray(), function ($var) use ($parameter) {
                return count(explode('.', $var['nomenklatur_kode'])) == $parameter['kode'];
            });

            $result = collect($query)->values();
        }
        
        
        $data = $this->pagination($parameter, $limit, $result, '/view-nomenklatur-perencanaan');

        $data['url_form'] = '/form-nomenklatur';
        $data['url_delete'] = '/delete-nomenklatur';
        $data['url_view'] = '/view-nomenklatur-perencanaan';

        $html = view('admin/components/view_nomenklatur_perencanaan', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_anggaran_kas(Request $request)
    {
        $parameter = $request->parameter;
        
        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined' ) {
                    continue;
                } else if ($key == 'pd') {
                    $param['instansi_kode'] = $value;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = $limit;
        }

        if (!array_key_exists('sesi', $param)) {
            $param['sesi'] = session('session_kode')->sesi_kode;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $param['tahun'] = session('session_tahun');

        $result = Keuangan::orderBy('keuangan_instansi_kode','asc')->filter($param)->get();

        $data = $this->pagination($parameter, $limit, $result, '/view-anggaran-kas');

        $data['url_view'] = '/view-anggaran-kas';

        $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_tahun', '2025')->get()->keyBy('nomenklatur_kode');
        $instansi = Instansi::get()->keyBy('instansi_kode');
        $sesi = Sesi::get()->keyBy('sesi_kode');

        $data['nomenklatur'] = $nomenklatur;
        $data['bulan'] = $this->bulan;
        $data['instansi'] = $instansi;
        $data['sesi'] = $sesi;
        $data['url_delete'] = '/delete-keuangan';

        $html = view('admin/components/view_anggaran_kas', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_fisik_admin(Request $request)
    {
        $parameter = $request->parameter;

        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined' ) {
                    continue;
                } else if ($key == 'pd') {
                    $param['instansi_kode'] = $value;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = $limit;
        }

        if (!array_key_exists('sesi', $param)) {
            $param['sesi'] = session('session_kode')->sesi_kode;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $param['tahun'] = session('session_tahun');

        $result = Fisik::orderBy('fisik_instansi_kode', 'asc')->filter($param)->get();

        $data = $this->pagination($parameter, $limit, $result, '/view-fisik-admin');

        $data['url_view'] = '/view-fisik-admin';

        $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_tahun', '2025')->get()->keyBy('nomenklatur_kode');
        $instansi = Instansi::get()->keyBy('instansi_kode');
        $sesi = Sesi::get()->keyBy('sesi_kode');

        $data['nomenklatur'] = $nomenklatur;
        $data['bulan'] = $this->bulan;
        $data['instansi'] = $instansi;
        $data['sesi'] = $sesi;
        // $data['url_delete'] = '/delete-keuangan';

        $tahapan = [
                1 => ['nama' => 'Persiapan'],
                2 => ['nama' => 'Pelaksanaan'],
                3 => ['nama' => 'Pelaporan'],
            ];
        
        $data['tahapan'] = $tahapan;

        $html = view('admin/components/view_fisik', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_fisik_detail_admin(Request $request)
    {
        $parameter = $request->parameter;
        $sesi_kode = session('session_kode')->sesi_kode;
        $html = '<div class="card-body text-center text-gray-400 small border rounded">Pilih Perangkat Daerah dan Sub Kegiatan</div>';

        if ($parameter) {

            $instansi_kode = $parameter['pd'];
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $parameter['id'])->first();

            $nomenklatur_kode_all = [];
            $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
            foreach ($ref_kode as $idx => $item) {
                $index = explode('.', $nomenklatur['nomenklatur_kode']);
                $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                array_push($nomenklatur_kode_all, $nomenklatur_kode);
                $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
            }

            $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

            $tahapan = [
                        1 => 'Persiapan',
                        2 => 'Pelaksanaan',
                        3 => 'Pelaporan'
                    ];

            $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->orderByRaw('CAST(fisik_nomor AS UNSIGNED) ASC')->get();

            $fisik_target = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 0;
            });

            $total_kumulatif_fisik = 0;
            $fisik_kumulatif = [];
            foreach ($this->bulan as $key => $value) {
                $total_kumulatif_fisik += collect($fisik_target)->sum('fisik_'.$key);
                $fisik_kumulatif[$key] = $total_kumulatif_fisik;
            }

            $fisik_realisasi = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 1;
            });

            $total_kumulatif_fisik_realisasi = 0;
            $fisik_kumulatif_realisasi = [];
            foreach ($this->bulan as $key => $value) {
                $total_kumulatif_fisik_realisasi += collect($fisik_realisasi)->sum('fisik_'.$key);
                $fisik_kumulatif_realisasi[$key] = $total_kumulatif_fisik_realisasi;
            }

            $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->where('keuangan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->get();

            $keuangan_target = $keuangan->first(function ($item) {
                return $item->keuangan_jenis == 0;
            });

            $total_kumulatif_keuangan = 0;
            $keuangan_kumulatif = [];
            foreach ($this->bulan as $key => $value) {
                $total_kumulatif_keuangan += $keuangan_target->{'keuangan_'.$key};
                $presentase_keuangan_kumulatif = ($total_kumulatif_keuangan*100)/$keuangan_target->keuangan_pagu;
                $keuangan_kumulatif[$key] = number_format(round($presentase_keuangan_kumulatif, 2), 2);
            }

            // $fisik_target_bulan = [];
            // $fisik_target = $fisik_target->groupBy('fisik_subkegiatan_kode');

            // foreach ($fisik_target as $key => $value) {
            //     ${'total_'.str_replace('.', '', $key)} = 0;
            //     for ($i=1; $i <= $bulan ; $i++) { 
            //         ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
            //     }
            //     $fisik_target_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
            // }

            // $diagram_fisik_target_bulan = [];
            // $jumlah_fisik_target = 0;
            // for ($i=1; $i <= count($this->bulan); $i++) { 
            //     $jumlah_fisik_target += array_sum($fisik_target->collapse()->pluck('fisik_'.$i)->toArray());
            //     $diagram_fisik_target_bulan[] = round($jumlah_fisik_target/count($fisik_target),2);
            // }

            // $fisik_realisasi = $fisik->filter(function ($item) {
            //     return $item->fisik_jenis == 1;
            // });

            // $fisik_realisasi_bulan = [];
            // $fisik_realisasi = $fisik_realisasi->groupBy('fisik_subkegiatan_kode');
            
            // foreach ($fisik_realisasi as $key => $value) {
            //     ${'total_'.str_replace('.', '', $key)} = 0;
            //     for ($i=1; $i <= $bulan ; $i++) { 
            //         ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
            //     }
            //     $fisik_realisasi_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
            // }



            // $target_fisik = $fisik->groupBy('fisik_tahapan');

            // $result = $fisik->groupBy('fisik_tahapan')->map(function ($item) {
            //     return $item->groupBy('fisik_kode');
            // });

            

            $data = [
                // 'titlePage' => 'Detail ROPK Fisik',
                // 'url_form' => '/form-ropk-fisik',
                // 'url_view' => '/view-ropk-fisik-detail',
                'bulan' => $this->bulan,
                'ref_kode' => $ref_kode,
                'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                'nomenklatur_subkegiatan' => $nomenklatur,
                'tahapan' => $tahapan
            ];

            // echo json_encode(['html' => $fisik_target->groupBy('fisik_tahapan')]);

            if ($parameter['jenis'] == 0) {
                $data['fisik'] = $fisik_target->groupBy('fisik_tahapan');
                $data['fisik_kumulatif'] = $fisik_kumulatif;
                $data['keuangan_kumulatif'] = $keuangan_kumulatif;
                $html = view('admin/components/view_fisik_target_detail', $data)->render();
            }

            if ($parameter['jenis'] == 1) {
                $data['fisik_target'] = $fisik_target->groupBy('fisik_tahapan');
                $data['fisik'] = $fisik_realisasi->groupBy('fisik_tahapan');
                $data['fisik_kumulatif'] = $fisik_kumulatif;
                $data['fisik_kumulatif_realisasi'] = $fisik_kumulatif_realisasi;
                $html = view('admin/components/view_fisik_realisasi_detail', $data)->render();
            }

            echo json_encode(['html' => $html]);
            

            // $parameter = $request->parameter;
            // $sesi_kode = 'LIRS6N';
            // $instansi_kode = auth()->user()->admin_kode;
            // if (array_key_exists('ref', $parameter)) {
            //     $data = [];
            //     $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $parameter['ref'])->first();

            //     $data = [
            //         'url_form' => '/form-ropk-fisik',
            //         'url_view' => '/view-ropk-fisik-detail',
            //         'bulan' => $this->bulan,
            //     ];

            //     $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            //     ->where('keluaran_instansi_kode', $instansi_kode)
            //     ->where('keluaran_sesi_kode', $sesi_kode)
            //     ->where('keluaran_tipe', 1)->first();

            //     $data['keluaran_kepmen'] = $keluaran_kepmen;

            //     $count = [];

            //     if ($keluaran_kepmen) {

            //         $ropk_fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->where('fisik_jenis', 0)->orderBy('fisik_nomor','asc')->get();
                    
            //         $tahapan = [
            //             1 => ['nama' => 'Persiapan', 'data' => []],
            //             2 => ['nama' => 'Pelaksanaan', 'data' => []],
            //             3 => ['nama' => 'Pelaporan', 'data' => []],
            //         ];
                    
            //         foreach ($ropk_fisik as $key => $value) {
            //             if ($value['fisik_tahapan'] == 1) {
            //                 $tahapan[1]['data'][] =  $value->toArray();
            //             } else if ($value['fisik_tahapan'] == 2) {
            //                 $tahapan[2]['data'][] =  $value->toArray();
            //             } else {
            //                 $tahapan[3]['data'][] =  $value->toArray();
            //             }
            //         }

            //         $data['tahapan'] = $tahapan;
            //         $data['ropk_fisik'] = $ropk_fisik;

            //         $total_kumulatif_fisik = 0;
            //         $fisik_komulatif = [];
            //         foreach ($this->bulan as $key => $value) {
            //             $total_kumulatif_fisik += collect($ropk_fisik)->sum('fisik_'.$key);
            //             $fisik_komulatif[$key] = $total_kumulatif_fisik;
            //         }

            //         $data['fisik_komulatif'] = $fisik_komulatif;

            //         $keuangan = Keuangan::where('keuangan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            //         ->where('keuangan_instansi_kode', $instansi_kode)
            //         ->where('keuangan_sesi_kode', 'LIRS6N')->first();

            //         $total_kumulatif_keuangan = 0;
            //         $keuangan_komulatif = [];
            //         foreach ($this->bulan as $key => $value) {
            //             $total_kumulatif_keuangan += $keuangan->{'keuangan_'.$key};
            //             $presentase_keuangan_komulatif = ($total_kumulatif_keuangan*100)/$keuangan->keuangan_pagu;
            //             $keuangan_komulatif[$key] = number_format(round($presentase_keuangan_komulatif, 2), 2);
            //         }

            //         $data['keuangan_komulatif'] = $keuangan_komulatif;
                    
            //     } else {
            //         $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
            //     }

            //     $html = view('user/components/view_ropk_fisik_detail', $data)->render();
            //     $chart = view('user/components/view_chart_ropk_fisik_detail', ['fisik_komulatif' => $fisik_komulatif, 'keuangan_komulatif' => $keuangan_komulatif])->render();

            //     // $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu</div></div>';
            // } else {
            //     $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
            // }
            
            // $param = [];
            // $limit = 10;
            // if ($parameter) {
            //     if (array_key_exists('limit', $parameter)) {
            //         $limit = $parameter['limit'];
            //     }

            //     foreach ($parameter as $key => $value) {
            //         if ($key == 'page' || $key == 'limit' || $value == 'undefined' ) {
            //             continue;
            //         } else if ($key == 'pd') {
            //             $param['instansi_kode'] = $value;
            //         } else {
            //             $param[$key] = $value;
            //         }
            //     }
            // } else {
            //     $parameter['limit'] = $limit;
            // }

            // if (!array_key_exists('sesi', $param)) {
            //     $param['sesi'] = session('session_kode')->sesi_kode;
            // }

            // if (array_key_exists('q', $parameter)) {
            //     $param['q'] = $parameter['q'];
            // } else {
            //     $param['q'] = '';
            // }

            // $param['tahun'] = session('session_tahun');

            // $result = Fisik::orderBy('fisik_instansi_kode', 'asc')->filter($param)->get();

            // $data = $this->pagination($parameter, $limit, $result, '/view-fisik-admin');

            // $data['url_view'] = '/view-fisik-admin';

            // $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_tahun', '2025')->get()->keyBy('nomenklatur_kode');
            // $instansi = Instansi::get()->keyBy('instansi_kode');
            // $sesi = Sesi::get()->keyBy('sesi_kode');

            // $data['nomenklatur'] = $nomenklatur;
            // $data['bulan'] = $this->bulan;
            // $data['instansi'] = $instansi;
            // $data['sesi'] = $sesi;
            // // $data['url_delete'] = '/delete-keuangan';

            // $tahapan = [
            //         1 => ['nama' => 'Persiapan'],
            //         2 => ['nama' => 'Pelaksanaan'],
            //         3 => ['nama' => 'Pelaporan'],
            //     ];
            
            // $data['tahapan'] = $tahapan;

            // $html = view('admin/components/view_fisik_target_detail', $data)->render();
            

            // echo json_encode(['html' => $html]);
        } else {
            echo json_encode(['html' => $html]);
        }

    }

    public function view_anggaran_kas_pd(Request $request)
    {
        $parameter = $request->parameter;
        if (array_key_exists('pd', $parameter)) {
            $keuangan = Keuangan::filter(['instansi_kode' => $parameter['pd'], 'tahun' => session('session_tahun')])->get();
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_tahun', session('session_tahun'))->get()->keyBy('nomenklatur_kode');
            $instansi = Instansi::where('instansi_kode', $parameter['pd'])->first();
            $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();
            // $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_tahun', '2025')->select('nomenklatur_kode','nomenklatur_nama')->pluck('nomenklatur_nama', 'nomenklatur_kode');
            
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

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'bulan' => $this->bulan,
                'instansi' => $instansi
            ];

            $html = view('admin/components/view_anggaran_kas_pd', $data)->render();

            // $html = $indexed;
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Silahkan Pilih Nama Perangkat Daerah</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_sesi(Request $request)
    {
        $parameter = $request->parameter;
        $limit = 10;

        $records =  Sesi::all();
        $data = $this->pagination($parameter, $limit, $records, '/view-sesi');

        $data['url_view'] = '/view-sesi';
        $data['url_form'] = '/form-sesi';
        $data['url_delete'] = '/delete-sesi';

        $html = view('admin/components/view_sesi', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);
        // echo json_encode(['html' => 'ok']);
    }

    public function view_renja(Request $request)
    {
        $instansi_kode = session('session_instansi');
        $sesi_kode = session('session_kode')->sesi_kode;
        $tahun = session('session_tahun');
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $keuangan = Keuangan::where('keuangan_jenis', 0)->filter(['instansi_kode' => $instansi_kode, 'sesi' => $sesi_kode])->get();
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        if ($keuangan) {
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

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'instansi' => $instansi
            ];

            $html = view('user/components/view_renja', $data)->render();
            // $html = $keuangan;
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_rencana_keluaran(Request $request)
    {
        $instansi_kode = session('session_instansi');
        $sesi_kode = session('session_kode')->sesi_kode;
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $keuangan = Keuangan::filter(['instansi_kode' => $instansi_kode, 'sesi' => $sesi_kode])->get();
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        if ($keuangan) {
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

            $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->where('keluaran_jenis', 0)->where('keluaran_status', 1)->get()->keyBy('keluaran_subkegiatan_kode');

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'instansi' => $instansi,
                'keluaran' => $keluaran
            ];

            $html = view('user/components/view_rencana_keluaran', $data)->render();
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_ropk_fisik(Request $request)
    {
        $instansi_kode = session('session_instansi');
        $sesi_kode = session('session_kode')->sesi_kode;
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $keuangan = Keuangan::filter(['instansi_kode' => $instansi_kode, 'sesi' => $sesi_kode])->get();
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        if ($keuangan) {
            $akun = [];
            $fisik_status = [];
            $fisik_total = [];

            $ropk_fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_jenis', 0)->get()->groupBy('fisik_subkegiatan_kode')->toArray();

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

                if (count($ropk_fisik) != 0 && isset($ropk_fisik[$item])) {
                    $fisik_status[$item] = count($ropk_fisik[$item]) == array_sum(array_column($ropk_fisik[$item], 'fisik_status')) ? true : false;
                    $fisik_total[$item] = sprintf('%.2f', isset($ropk_fisik[$item]) ? array_sum(array_column($ropk_fisik[$item], 'fisik_acuan')) : 0);
                } else {
                    $fisik_status[$item] = false;
                    $fisik_total[$item] = sprintf('%.2f', 0);
                }
                

                
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

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'instansi' => $instansi,
                'fisik_status' => $fisik_status,
                'fisik_total' => $fisik_total
            ];

            $html = view('user/components/view_ropk_fisik', $data)->render();
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_ropk_fisik_detail(Request $request)
    {
        $parameter = $request->parameter;
        $sesi_kode = session('session_kode')->sesi_kode;
        $instansi_kode = session('session_instansi');
        if (array_key_exists('ref', $parameter)) {
            $data = [];
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $parameter['ref'])->first();

            $data = [
                'url_form' => '/form-ropk-fisik',
                'url_view' => '/view-ropk-fisik-detail',
                'url_approve' => '/approve-admin',
                'bulan' => $this->bulan,
                'ref' => $parameter['ref']
            ];

            $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', $sesi_kode)
            ->where('keluaran_jenis', 0)
            ->where('keluaran_tipe', 1)->first();

            $data['keluaran_kepmen'] = $keluaran_kepmen;

            $count = [];

            if ($keluaran_kepmen) {

                $ropk_fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->where('fisik_jenis', 0)->orderByRaw('CAST(fisik_nomor AS UNSIGNED) ASC')->get();

                if (count($ropk_fisik) != 0) {
                    $ropk_fisik_status = count($ropk_fisik) == $ropk_fisik->sum('fisik_status') ? true : false;
                    $ropk_fisik_total = sprintf('%.2f',$ropk_fisik->sum('fisik_acuan'));
                } else {
                    $ropk_fisik_status = false;
                    $ropk_fisik_total = sprintf('%.2f', 0);
                }
                
                $data['ropk_fisik_status'] = $ropk_fisik_status;
                $data['ropk_fisik_total'] = $ropk_fisik_total;
                
                $tahapan = [
                    1 => ['nama' => 'Persiapan', 'data' => []],
                    2 => ['nama' => 'Pelaksanaan', 'data' => []],
                    3 => ['nama' => 'Pelaporan', 'data' => []],
                ];
                
                foreach ($ropk_fisik as $key => $value) {
                    if ($value['fisik_tahapan'] == 1) {
                        $tahapan[1]['data'][] =  $value->toArray();
                    } else if ($value['fisik_tahapan'] == 2) {
                        $tahapan[2]['data'][] =  $value->toArray();
                    } else {
                        $tahapan[3]['data'][] =  $value->toArray();
                    }
                }

                $data['tahapan'] = $tahapan;
                $data['ropk_fisik'] = $ropk_fisik;

                $total_kumulatif_fisik = 0;
                $fisik_komulatif = [];
                $fisik_target = [];
                foreach ($this->bulan as $key => $value) {
                    $total_kumulatif_fisik += collect($ropk_fisik)->sum('fisik_'.$key);
                    $fisik_komulatif[$key] = $total_kumulatif_fisik;
                    $fisik_target[] = $total_kumulatif_fisik;
                }

                $data['fisik_komulatif'] = $fisik_komulatif;
                $data['fisik_target'] = $fisik_target;

                $keuangan = Keuangan::where('keuangan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                ->where('keuangan_instansi_kode', $instansi_kode)
                ->where('keuangan_jenis', 0)
                ->where('keuangan_sesi_kode', $sesi_kode)->first();

                $total_kumulatif_keuangan = 0;
                $keuangan_komulatif = [];
                $keuangan_target = [];
                foreach ($this->bulan as $key => $value) {
                    $total_kumulatif_keuangan += $keuangan->{'keuangan_'.$key};
                    $presentase_keuangan_komulatif = $keuangan->keuangan_pagu > 0 ? ($total_kumulatif_keuangan*100)/$keuangan->keuangan_pagu : 0;
                    $keuangan_komulatif[$key] = number_format(round($presentase_keuangan_komulatif, 2), 2);
                    $keuangan_target[] = number_format(round($presentase_keuangan_komulatif, 2), 2);
                }

                $data['keuangan_komulatif'] = $keuangan_komulatif;
                $data['keuangan_target'] = $keuangan_target;

                $html = view('user/components/view_ropk_fisik_detail', $data)->render();
                
            } else {
                $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
            }

            $chart = view('user/components/view_chart_ropk_fisik_detail', ['bulan' => array_values($this->bulan), 'fisik_target' => $fisik_target, 'keuangan_target' => $keuangan_target])->render();
            // $chart = '';

        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html, 'chart' =>  $chart]);
    }

    public function view_ropk_keuangan(Request $request)
    {
        $sesi_kode = session('session_kode')->sesi_kode;
        $instansi_kode = session('session_instansi');
        $tahun = session('session_tahun');
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $keuangan = Keuangan::where('keuangan_jenis', 0)->filter(['instansi_kode' => $instansi_kode, 'sesi' => $sesi_kode])->get();
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        if ($keuangan) {
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

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'instansi' => $instansi,
                'bulan' => $this->bulan,
            ];

            $html = view('user/components/view_ropk_keuangan', $data)->render();
            
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_monev(Request $request)
    {
        $parameter = $request->parameter;
        $sesi_kode = session('session_kode')->sesi_kode;
        $instansi_kode = session('session_instansi');
        $tahun = session('session_tahun');
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
        $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
        $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();
        

        if (array_key_exists('bulan', $parameter)) {
            $bulan = $parameter['bulan'];
        } else {
            $bulan = Carbon::createFromFormat('m', date('m'))->subMonth()->format('n');
            // $bulan = date('m');
        }

        $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->where('permasalahan_bulan', $bulan)->get();

        if ($keuangan) {

            // KEUANGAN

            $keuangan_target = $keuangan->filter(function ($item) {
                return $item->keuangan_jenis == 0;
            });
            
            $akun = [];

            $bidang_urusan = $keuangan_target->unique('keuangan_bidang_urusan_kode')->pluck('keuangan_bidang_urusan_kode');
            foreach ($bidang_urusan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $program = $keuangan_target->unique('keuangan_program_kode')->pluck('keuangan_program_kode');
            foreach ($program as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }

            $kegiatan = $keuangan_target->unique('keuangan_kegiatan_kode')->pluck('keuangan_kegiatan_kode');
            foreach ($kegiatan as $item) {
                $data = [
                    'akun_id' => $nomenklatur[$item]->nomenklatur_id,
                    'akun_kode' => $nomenklatur[$item]->nomenklatur_kode,
                    'akun_nama' => $nomenklatur[$item]->nomenklatur_nama
                ];
                $akun[] = $data;
            }
            
            $sub_kegiatan = $keuangan_target->unique('keuangan_subkegiatan_kode')->pluck('keuangan_subkegiatan_kode');
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

            foreach ($keuangan_realisasi as $key => $value) {
                ${'total_'.$value->keuangan_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                }
                $keuangan_realisasi_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
            }

            // KELUARAN

            $keluaran_target = $keluaran->filter(function ($item) {
                return $item->keluaran_jenis == 0;
            });
            
            $keluaran_target_bulan = [];

            foreach ($keluaran_target as $key => $value) {
                ${'total_'.$value->keluaran_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                }
                $keluaran_target_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
            }

            $keluaran_realisasi = $keluaran->filter(function ($item) {
                return $item->keluaran_jenis == 1;
            });
            
            $keluaran_realisasi_bulan = [];

            foreach ($keluaran_realisasi as $key => $value) {
                ${'total_'.$value->keluaran_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                }
                $keluaran_realisasi_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
            }

            // FISIK

            $fisik_target = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 0;
            });

            $fisik_target_bulan = [];
            $fisik_target = $fisik_target->groupBy('fisik_subkegiatan_kode');

            foreach ($fisik_target as $key => $value) {
                ${'total_'.str_replace('.', '', $key)} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                }
                $fisik_target_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
            }

            $fisik_realisasi = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 1;
            });

            $fisik_realisasi_bulan = [];
            $fisik_realisasi = $fisik_realisasi->groupBy('fisik_subkegiatan_kode');
            
            foreach ($fisik_realisasi as $key => $value) {
                ${'total_'.str_replace('.', '', $key)} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                }
                $fisik_realisasi_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
            }

            // PERMASALAHAN

            $data = [
                'keuangan' => $keuangan,
                'akun' => $akun_all,
                'instansi' => $instansi,
                'keluaran_target_bulan' => $keluaran_target_bulan,
                'keluaran_realisasi_bulan' => $keluaran_realisasi_bulan,
                'keuangan_target_bulan' => $keuangan_target_bulan,
                'keuangan_realisasi_bulan' => $keuangan_realisasi_bulan,
                'fisik_target_bulan' => $fisik_target_bulan,
                'fisik_realisasi_bulan' => $fisik_realisasi_bulan,
                'permasalahan' => $permasalahan->keyBy('permasalahan_subkegiatan_kode')
            ];

            $html = view('user/components/view_monev', $data)->render();
            
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_pelaporan(Request $request)
    {
        $parameter = $request->parameter;
        $instansi_kode = $parameter['pd'];
        $sesi_kode = session('session_kode')->sesi_kode;
        $bulan = $parameter['bulan'] ?? '';
        $triwulan = isset($parameter['triwulan']) ? json_decode($parameter['triwulan'], true) : '';
        $data_tw = [[1,2,3],[4,5,6],[7,8,9],[10,11,12]];
        $html = '<div class="card-body text-center text-gray-400 small border rounded">Data Tidak Ditemukan</div>';

        if ($parameter['jenis'] == 1) {
            $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
            $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
            $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

            $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
            $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
            $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();
            $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->get();
            

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

                $jumlah_verifikasi = $permasalahan->filter(function ($items) use($bulan) {
                    return $items->first(function ($item) use ($bulan) {
                        return $item->permasalahan_bulan == $bulan && $item->permasalahan_verifikasi != null;
                    });
                });

                $data = [
                    'url_export' => '/export-laporan-apbd-bulanan',
                    'keuangan' => $keuangan_target,
                    'akun' => $akun_all,
                    'bulan' => $this->bulan,
                    'bulan_index' => $bulan,
                    'instansi' => $instansi,
                    'instansi_kode' => $instansi_kode,
                    'subkegiatan_kode' => $keuangan_target->pluck('keuangan_subkegiatan_kode'),
                    'jumlah_subkegiatan' => $keuangan_target->count(),
                    'jumlah_verifikasi' => $jumlah_verifikasi->count(),
                    'keluaran_target_bulan' => $keluaran_target_bulan,
                    'keluaran_realisasi_bulan' => $keluaran_realisasi_bulan,
                    'keuangan_target_bulan' =>  $keuangan_target_bulan,
                    'keuangan_realisasi_bulan' =>  $keuangan_realisasi_bulan,
                    'fisik_target_bulan' => $fisik_target_bulan,
                    'fisik_realisasi_bulan' => $fisik_realisasi_bulan,
                    'permasalahan' => $permasalahan,
                ];

                $html = view('user/components/view_pelaporan_apbd_bulanan', $data)->render();

                // $html = 'ok';
            
            } else {
                $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan';
            }

        }

        if ($parameter['jenis'] == 2) {
            $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
            $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
            $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

            $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->where('keluaran_jenis', 0)->get();
            $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->where('keuangan_jenis', 0)->get();
            $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_jenis', 0)->get();
            $keluaran_riil = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 2)->get()->groupBy('keluaran_subkegiatan_kode');
            

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
                    'url_export' => '/export-laporan-rencana-aksi',
                    'keuangan' => $keuangan->keyBy('keuangan_subkegiatan_kode'),
                    'akun' => $akun_all,
                    'bulan' => $this->bulan,
                    'instansi' => $instansi,
                    'keluaran' => $keluaran->keyBy('keluaran_subkegiatan_kode'),
                    'fisik' => $fisik_target_bulan,
                    'keluaran_riil' => $keluaran_riil
                ];

                $html = view('user/components/view_pelaporan_rencana_aksi', $data)->render();

                // $html =  $fisik;
            
            } else {
                $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan';
            }
        }

        if ($parameter['jenis'] == 3) {
            $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
            $tahun = session('session_tahun');
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
                $data['pelaporan_nilai'] = $total_nilai_pelaporan;

                $total_nilai_subkegiatan = (float) $total_nilai_fisik + (float) $total_nilai_keuangan + (float)$total_nilai_pelaporan;
                $data['total_nilai'] = $total_nilai_subkegiatan;

                $status_nilai = $this->get_status_kinerja($total_nilai_subkegiatan);
                $data['status_nilai'] = $status_nilai;
                
                $result[str_replace('.','', $item)] = $data;
            }

            $data = [
                'url_export' => '/export-laporan-lampiran-kinerja',
                'bulan' => $this->bulan,
                'bulan_index' => $bulan,
                'instansi' => $instansi,
                'instansi_kode' => $instansi_kode,
                'subkegiatan' => $nomenklatur,
                'nilai_subkegiatan' => $result
            ];

            $html = view('user/components/view_pelaporan_lampiran_kinerja', $data)->render();
            
        }

        if ($parameter['jenis'] == 4) {
            $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
            $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
            $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

            $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
            $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
            $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_jenis', 0)->get();
            $keluaran_riil = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 2)->get()->groupBy('keluaran_subkegiatan_kode');
            

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

                // KELUARAN
                $keluaran_target = $keluaran->filter(function ($item) {
                    return $item->keluaran_jenis == 0;
                });
                
                $keluaran_realisasi = $keluaran->filter(function ($item) {
                    return $item->keluaran_jenis == 1;
                });
                
                $keluaran_realisasi_bulan = [];

                if ($keluaran_realisasi->isNotEmpty()) {
                    foreach ($keluaran_realisasi as $key => $value) {
                        ${'total_'.$value->keluaran_id} = 0;
                        foreach ($triwulan['bulan'] as $bulan) {
                            ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$bulan};
                        }
                        $keluaran_realisasi_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
                    }
                }

                // KEUANGAN

                $keuangan_target = $keuangan->filter(function ($item) {
                    return $item->keuangan_jenis == 0;
                });

                $keuangan_realisasi = $keuangan->filter(function ($item) {
                    return $item->keuangan_jenis == 1;
                });

                $keuangan_realisasi_bulan = [];

                for ($i=1; $i <= count($data_tw) ; $i++) { 
                    ${'keuangan_realisasi_bulan_'.$i} = [];
                    ${'keuangan_realisasi_bulan_kegiatan_'.$i} = [];
                    ${'keuangan_realisasi_bulan_program_'.$i} = [];
                }

                if ($keuangan_realisasi->isNotEmpty()) {
                    foreach ($keuangan_realisasi as $key => $value) {
                        // ${'total_'.$value->keuangan_id} = 0;
                        // foreach ($triwulan['bulan'] as $bulan) {
                        //     ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$bulan};
                        // }
                        // $keuangan_realisasi_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
                        // $keuangan_realisasi_bulan_kegiatan[$value->keuangan_kegiatan_kode][] = ${'total_'.$value->keuangan_id};
                        // $keuangan_realisasi_bulan_program[$value->keuangan_program_kode][] = ${'total_'.$value->keuangan_id};

                        for ($i=1; $i <= count($data_tw) ; $i++) { 
                            ${'total_'.$i} = 0;
                            foreach ($data_tw[$i-1] as $bulan) {
                                ${'total_'.$i} += $value->{'keuangan_'.$bulan};
                            }
                            
                            ${'keuangan_realisasi_bulan_'.$i}[$value->keuangan_subkegiatan_kode] = ${'total_'.$i};
                            ${'keuangan_realisasi_bulan_kegiatan_'.$i}[$value->keuangan_kegiatan_kode][] = ${'total_'.$i};
                            ${'keuangan_realisasi_bulan_program_'.$i}[$value->keuangan_program_kode][] = ${'total_'.$i};
                        }
                    }
                }

                for ($i=1; $i <= count($data_tw) ; $i++) { 
                    $keuangan_realisasi_bulan['keuangan_realisasi_bulan_'.$i] = ${'keuangan_realisasi_bulan_'.$i};
                    $keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_'.$i] = ${'keuangan_realisasi_bulan_kegiatan_'.$i};
                    $keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_'.$i] = ${'keuangan_realisasi_bulan_program_'.$i};
                }

                $keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_program'] = [];
                $keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_kegiatan'] = [];
                $keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_subkegiatan'] = [];

                // if ($keuangan_realisasi->isNotEmpty()) {
                //     foreach ($keuangan_realisasi as $key => $value) {
                //         ${'total_'.$value->keuangan_id} = 0;
                //         for ($i=1; $i <= $bulan ; $i++) { 
                //             ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                //         }
                //         $keuangan_realisasi_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
                //     }
                // }

                if ($keuangan_realisasi->isNotEmpty()) {
                    foreach ($keuangan_realisasi->groupBy('keuangan_subkegiatan_kode') as $key => $value) {
                        $total_subkegiatan = 0;
                        foreach ($triwulan['bulan_kumulatif'] as $bulan) {
                            $total_subkegiatan += $value->sum('keuangan_'.$bulan);
                        }

                        $keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_subkegiatan'][$key] = $total_subkegiatan ;
                    }

                    foreach ($keuangan_realisasi->groupBy('keuangan_kegiatan_kode') as $key => $value) {
                        $total_kegiatan = 0;
                        foreach ($triwulan['bulan_kumulatif'] as $bulan) {
                            $total_kegiatan += $value->sum('keuangan_'.$bulan);
                        }

                        $keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_kegiatan'][$key] = $total_kegiatan ;
                    }

                    foreach ($keuangan_realisasi->groupBy('keuangan_program_kode') as $key => $value) {
                        $total_program = 0;
                        foreach ($triwulan['bulan_kumulatif'] as $bulan) {
                            $total_program += $value->sum('keuangan_'.$bulan);
                        }

                        $keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_program'][$key] = $total_program ;
                    }
                }

                // echo json_encode(['html' => $keuangan_realisasi_kumulatif_bulan]);
                // die();

                // FISIK
                // $fisik_target_bulan = [];
                // $fisik = $fisik->groupBy('fisik_subkegiatan_kode');

                // if ($fisik->isNotEmpty()) {
                //     foreach ($fisik as $key => $value) {
                //         for ($i=1; $i <= 12; $i++) { 
                //             $fisik_target_bulan[$key][$i] = array_sum($value->pluck('fisik_'.$i)->toArray());
                //         }
                //     }
                // }

                $data = [
                    'url_export' => '/export-laporan-rencana-aksi',
                    'keuangan' => $keuangan_target->keyBy('keuangan_subkegiatan_kode'),
                    'keuangan_realisasi_bulan' =>  $keuangan_realisasi_bulan,
                    'keuangan_realisasi_kumulatif_bulan' => $keuangan_realisasi_kumulatif_bulan,
                    'akun' => $akun_all,
                    'bulan' => $this->bulan,
                    'instansi' => $instansi,
                    'keluaran' => $keluaran_target->keyBy('keluaran_subkegiatan_kode'),
                    'keluaran_realisasi_bulan' => $keluaran_realisasi_bulan,
                    // 'fisik' => $fisik_target_bulan,
                    'keluaran_riil' => $keluaran_riil,
                    'triwulan' => $triwulan
                ];

                // echo json_encode(['html' => $data]);
                // die();

                $html = view('user/components/view_pelaporan_evaluasi_renja', $data)->render();

                // $html =  $triwulan;
            
            } else {
                $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan';
            }
        }

        echo json_encode(['html' => $html]);

    }

    public function view_pelaporan_daerah(Request $request)
    {
        $parameter = $request->parameter;
        $bulan = $parameter['bulan'];
        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();

        if ($parameter['jenis'] == 1) {
            $view = '';

            // foreach ($instansi as $key => $value) {
            //     $view .= $this->generate_pelaporan_daerah('523', $bulan);
            // }

            foreach ($instansi as $key => $value) {
                // $view .= $this->generate_pelaporan_daerah($value->instansi_kode, $bulan);
                $view .= $this->generate_pelaporan_daerah($value->instansi_kode, $bulan);
                // $view .= '<div>'.$value->instansi_kode.'</div>';
                // $view .= '<div>'.$bulan.'</div>';
            }

            $data = [
                'title' => 'Laporan ABPD Pemerintah Kabupaten Kulon progo',
                'url_export' => '/export-laporan-apbd-bulanan-daerah',
                'bulan_index' => $this->bulan[$bulan],
                'view' => $view
            ];

            $html = view('admin/components/view_pelaporan_apbd_bulanan_daerah', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function generate_pelaporan_daerah($instansi_kode, $bulan)
    {
        $sesi_kode = session('session_kode')->sesi_kode;
        $html = '<div class="card-body text-center text-gray-400 small border rounded">Data Tidak Ditemukan</div>';

        $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
        $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
        $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

        $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
        $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
        $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();
        $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->get();
        

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

            foreach ($keluaran_target as $key => $value) {
                ${'total_'.$value->keluaran_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                }
                $keluaran_target_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
            }

            $keluaran_realisasi = $keluaran->filter(function ($item) {
                return $item->keluaran_jenis == 1;
            });
            
            $keluaran_realisasi_bulan = [];

            foreach ($keluaran_realisasi as $key => $value) {
                ${'total_'.$value->keluaran_id} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.$value->keluaran_id} += $value->{'keluaran_'.$i};
                }
                $keluaran_realisasi_bulan[$value->keluaran_subkegiatan_kode] = ${'total_'.$value->keluaran_id};
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

            foreach ($keuangan_realisasi as $key => $value) {
                ${'total_'.$value->keuangan_id} = 0;
                for ($i=1; $i <= 1 ; $i++) { 
                    ${'total_'.$value->keuangan_id} += $value->{'keuangan_'.$i};
                }
                $keuangan_realisasi_bulan[$value->keuangan_subkegiatan_kode] = ${'total_'.$value->keuangan_id};
            }

            // FISIK

            $fisik_target = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 0;
            });

            $fisik_target_bulan = [];
            $fisik_target = $fisik_target->groupBy('fisik_subkegiatan_kode');

            foreach ($fisik_target as $key => $value) {
                ${'total_'.str_replace('.', '', $key)} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                }
                $fisik_target_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
            }

            $fisik_realisasi = $fisik->filter(function ($item) {
                return $item->fisik_jenis == 1;
            });

            $fisik_realisasi_bulan = [];
            $fisik_realisasi = $fisik_realisasi->groupBy('fisik_subkegiatan_kode');
            
            foreach ($fisik_realisasi as $key => $value) {
                ${'total_'.str_replace('.', '', $key)} = 0;
                for ($i=1; $i <= $bulan ; $i++) { 
                    ${'total_'.str_replace('.', '', $key)} += array_sum($value->pluck('fisik_'.$i)->toArray());
                }
                $fisik_realisasi_bulan[$key] = ${'total_'.str_replace('.', '', $key)};
            }

            // PERMASALAHAN

            $permasalahan = $permasalahan->groupBy('permasalahan_subkegiatan_kode')->map(function ($item) {
                return $item->keyBy('permasalahan_bulan');
            });

            $jumlah_verifikasi = $permasalahan->filter(function ($items) use($bulan) {
                return $items->first(function ($item) use ($bulan) {
                    return $item->permasalahan_bulan == $bulan && $item->permasalahan_verifikasi != null;
                });
            });

            $data = [
                'url_export' => '/export-laporan-apbd-bulanan',
                'keuangan' => $keuangan_target,
                'akun' => $akun_all,
                'bulan' => $this->bulan,
                'bulan_index' => $bulan,
                'instansi' => $instansi,
                'instansi_kode' => $instansi_kode,
                'subkegiatan_kode' => $keuangan_target->pluck('keuangan_subkegiatan_kode'),
                'jumlah_subkegiatan' => $keuangan_target->count(),
                'jumlah_verifikasi' => $jumlah_verifikasi->count(),
                'keluaran_target_bulan' => $keluaran_target_bulan,
                'keluaran_realisasi_bulan' => $keluaran_realisasi_bulan,
                'keuangan_target_bulan' =>  $keuangan_target_bulan,
                'keuangan_realisasi_bulan' =>  $keuangan_realisasi_bulan,
                'fisik_target_bulan' => $fisik_target_bulan,
                'fisik_realisasi_bulan' => $fisik_realisasi_bulan,
                'permasalahan' => $permasalahan
            ];

            $html = view('admin/components/view_generate_pelaporan_daerah', $data)->render();

            // $html = '<div>OK</div>';
        
        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan</div></div>';
        }

        return $html;

    }

    public function view_penilaian_pelaporan(Request $request)
    {
        $parameter = $request->parameter;
        $sesi_kode = session('session_kode')->sesi_kode;
        if (array_key_exists('pd', $parameter)) {
            $instansi_kode = $parameter['pd'];
            $nomenklatur = NomenklaturPerencanaan::get()->keyBy('nomenklatur_kode');
            $instansi = Instansi::where('instansi_kode', $instansi_kode)->first();
            $ref_kode = RefKode::whereNotIn('kode_index', ['1'])->get();

            if (array_key_exists('bulan', $parameter)) {
                $bulan = $parameter['bulan'];
            } else {
                $bulan = Carbon::createFromFormat('m', date('m'))->subMonth()->format('n');
                // $bulan = date('m');
            }

            $keluaran = Keluaran::where('keluaran_instansi_kode', $instansi_kode)->where('keluaran_sesi_kode', $sesi_kode)->where('keluaran_tipe', 1)->get();
            $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();
            $fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->get();
            $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->get();

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

                $jumlah_verifikasi = $permasalahan->filter(function ($items) use($bulan) {
                    return $items->first(function ($item) use ($bulan) {
                        return $item->permasalahan_bulan == $bulan && $item->permasalahan_verifikasi != null;
                    });
                });

                $data = [
                    'url_view' => 'view_penilaian_pelaporan',
                    'keuangan' => $keuangan_target,
                    'akun' => $akun_all,
                    'bulan' => $this->bulan,
                    'bulan_index' => $bulan,
                    'instansi' => $instansi,
                    'instansi_kode' => $instansi_kode,
                    'subkegiatan_kode' => $keuangan_target->pluck('keuangan_subkegiatan_kode'),
                    'jumlah_subkegiatan' => $keuangan_target->count(),
                    'jumlah_verifikasi' => $jumlah_verifikasi->count(),
                    'keluaran_target_bulan' => $keluaran_target_bulan,
                    'keluaran_realisasi_bulan' => $keluaran_realisasi_bulan,
                    'keuangan_target_bulan' =>  $keuangan_target_bulan,
                    'keuangan_realisasi_bulan' =>  $keuangan_realisasi_bulan,
                    'fisik_target_bulan' => $fisik_target_bulan,
                    'fisik_realisasi_bulan' => $fisik_realisasi_bulan,
                    'permasalahan' => $permasalahan
                ];

                // echo json_encode(['html' => $data]);
                // die();

                $html = view('admin/components/view_penilaian_pelaporan', $data)->render();
            
            } else {
                $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Data Tidak Ditemukan';
            }

        } else {
            $html = '<div class="card-body border"><div class="text-center text-gray-400 text-small">Silahkan Pilih Nama Perangkat Daerah</div></div>';
        }

        echo json_encode(['html' => $html]);

    }

    public function view_penilaian_rekap(Request $request)
    {
        $parameter = $request->parameter;
        $tahun = session('session_tahun');
        $sesi_kode = session('session_kode')->sesi_kode;

        if (array_key_exists('bulan', $parameter)) {
            $bulan = $parameter['bulan'];
        } else {
            $bulan = Carbon::createFromFormat('m', date('m'))->subMonth()->format('n');
        }

        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();

        //PERENCANAAN

        $penilaian_perencanaan_bulan = [];

        $penilaian_perencanaan = PenilaianRencana::with('item')->where('penilaian_rencana_tahun', $tahun)->where('penilaian_rencana_bulan', $bulan)->first();

        if ($penilaian_perencanaan) {
            $penilaian_perencanaan_bulan =  $penilaian_perencanaan->item->keyBy('penilaian_rencana_item_instansi_kode');
        }

        // FISIK

        $penilaian_fisik = Fisik::where('fisik_sesi_kode', $sesi_kode)->get();

        $penilaian_fisik_bulan_target = [];

        $penilaian_fisik_target = $penilaian_fisik->filter(function($item){
            return $item->fisik_jenis == 0;
        });

        $penilaian_fisik_target = $penilaian_fisik_target->groupBy('fisik_instansi_kode')->map(function($item) {
            return $item->groupBy('fisik_subkegiatan_kode');
        });

        foreach ($penilaian_fisik_target as $key => $value) {
            if ($value) {
                ${'fisik_target_bulan_'.$key} = [];
                foreach ($value as $x => $y) {
                    ${'total_'.str_replace('.', '', $x)} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.str_replace('.', '', $x)} += array_sum($y->pluck('fisik_'.$i)->toArray());
                    }
                    ${'fisik_target_bulan_'.$key}[str_replace('.', '', $x)] = ${'total_'.str_replace('.', '', $x)};
                }

                $penilaian_fisik_bulan_target[$key] = ${'fisik_target_bulan_'.$key};
            }
        }

        $penilaian_fisik_bulan_realisasi = [];

        $penilaian_fisik_realisasi = $penilaian_fisik->filter(function($item){
            return $item->fisik_jenis == 1;
        });
        
        $penilaian_fisik_realisasi = $penilaian_fisik_realisasi->groupBy('fisik_instansi_kode')->map(function($item) {
            return $item->groupBy('fisik_subkegiatan_kode');
        });

        foreach ($penilaian_fisik_realisasi as $key => $value) {
            if ($value) {
                ${'fisik_realisasi_bulan_'.$key} = [];
                foreach ($value as $x => $y) {
                    ${'total_'.str_replace('.', '', $x)} = 0;
                    for ($i=1; $i <= $bulan ; $i++) { 
                        ${'total_'.str_replace('.', '', $x)} += array_sum($y->pluck('fisik_'.$i)->toArray());
                    }
                    ${'fisik_realisasi_bulan_'.$key}[str_replace('.', '', $x)] = ${'total_'.str_replace('.', '', $x)};
                }

                $penilaian_fisik_bulan_realisasi[$key] = ${'fisik_realisasi_bulan_'.$key};
            }
        }

        $persentase_fisik = [];

        
        foreach ($penilaian_fisik_bulan_target as $key => $value) {
            if ($value) {
                ${'presentase_bulan_'.$key} = [];
                foreach ($value as $x => $y) {
                    if($y != 0) {
                        ${'total_'.str_replace('.', '', $x)} = data_get($penilaian_fisik_bulan_realisasi, $key.'.'.$x, 0) != 0 ? (data_get($penilaian_fisik_bulan_realisasi, $key.'.'.$x, 0)*100)/$y : 0;
                        ${'presentase_bulan_'.$key}[$x] = number_format(round(${'total_'.str_replace('.', '', $x)},2),2);
                    } else {
                        ${'presentase_bulan_'.$key}[$x] = number_format(round(0,2),2);
                    }
                    
                }
            }

            $persentase_fisik[$key] = ${'presentase_bulan_'.$key};
        }
        
        

        $persentase_fisik_opd = [];

        foreach ($persentase_fisik as $key => $value) {
            $persentase_fisik_opd[$key] = number_format(round(array_sum($value)/count($value),2),2) > 100 ? 100 : number_format(round(array_sum($value)/count($value),2),2);
        }

        // PELAPORAN

        $keuangan = Keuangan::where('keuangan_sesi_kode', $sesi_kode)->get();

        $subkegiatan = $keuangan->groupBy('keuangan_instansi_kode')->map(function($item) {
            return $item->groupBy('keuangan_subkegiatan_kode');
        });

        $jumlah_subkegiatan_opd = [];

        foreach ($subkegiatan as $key => $value) {
            $jumlah_subkegiatan_opd[$key] = count($value);
        }

        $pelaporan = Permasalahan::where('permasalahan_sesi_kode', $sesi_kode)->where('permasalahan_verifikasi', 1)->where('permasalahan_bulan', $bulan)->get();

        $penilaian_pelaporan = [];

        if ($pelaporan) {
            $pelaporan = $pelaporan->groupBy('permasalahan_instansi_kode')->map(function($item) {
                return $item->groupBy('permasalahan_subkegiatan_kode');
            });

            $jumlah_verifikasi_tepat = [];

            foreach ($pelaporan as $key => $value) {
                $jumlah_verifikasi_tepat[$key] = count($value);
            }

            foreach ($jumlah_subkegiatan_opd as $key => $value) {
                $nilai = ((array_key_exists($key, $jumlah_verifikasi_tepat) ? $jumlah_verifikasi_tepat[$key] : 0)*50)/$value;
                $penilaian_pelaporan[$key] = number_format(round($nilai,2),2);
            }
        } else {
            foreach ($jumlah_subkegiatan_opd as $key => $value) {
                $penilaian_pelaporan[$key] = 0;
            }
        }

        $data = [
            'instansi' =>  $instansi,
            'penilaian_perencanaan_bulan' => $penilaian_perencanaan_bulan,
            'persentase_fisik' => $persentase_fisik_opd,
            'penilaian_pelaporan' => $penilaian_pelaporan,
            'bulan' => $bulan
        ];


        $html = view('admin/components/view_penilaian_rekap', $data)->render();

        // $html = $penilaian_perencanaan_bulan;

        echo json_encode(['html' => $html]);

        

    }

    public function view_permission(Request $request)
    {
        $tahun = session('session_tahun');

        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();
        $auth = Permission::where('auth_tahun', $tahun)->get()->keyBy('auth_instansi_kode');

        $data_insert = [];

        foreach ($instansi as $key => $value) {
            if (!$auth->has($value->instansi_kode)) {
                $data = [
                    'auth_uid' => $tahun.$value->instansi_kode,
                    'auth_instansi_kode' => $value->instansi_kode,
                    'auth_tahun' => $tahun
                ];
                $data_insert[] = $data;
            }
        }

        if (!empty($data_insert)) {
            Permission::insert($data_insert);
        }

        $permission = Permission::where('auth_tahun', $tahun)->get()->keyBy('auth_instansi_kode');

        // Permasalahan::upsert(
        //         $data_update,
        //         uniqueBy: ['permasalahan_id'],
        //         update: ['permasalahan_verifikasi', 'permasalahan_catatan']
        //     );
        

        $data = [
            'instansi' =>  $instansi,
            'tahun' => $tahun,
            'bulan' => $this->bulan,
            'permission' => $permission
        ];


        $html = view('admin/components/view_permission', $data)->render();

        echo json_encode(['html' => $html]);

    }

    function get_status_kinerja($jumlah) {
        if ($jumlah <= 50) return ['nama' => 'SANGAT RENDAH', 'color' => '#DC2626', 'class' => 'label-sangat-rendah' ];
        if ($jumlah <= 65) return ['nama' => 'RENDAH', 'color' => '#D97706', 'class' => 'label-rendah'];
        if ($jumlah <= 75) return ['nama' => 'SEDANG', 'color' => '#FBBF24', 'class' => 'label-sedang'];
        if ($jumlah <= 90) return ['nama' => 'TINGGI', 'color' => '#34D399', 'class' => 'label-tinggi'];
        if ($jumlah > 90)  return ['nama' => 'SANGAT TINGGI', 'color' => '#047857', 'class' => 'label-sangat-tinggi'];
    }
    








    public function view_admin(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];
        $limit = 10;

        $role = [
            '1' => ['nama' => 'User', 'color' => '#b3ad06ff'],
            '2' => ['nama' => 'Admin', 'color' => '#60A5FA'],
            '7' => ['nama' => 'Administrator', 'color' => '#F87171'],
        ];

        $records =  Admin::all();
        $data = $this->pagination($parameter, $limit, $records, '/view-admin');

        $data['url'] = '/view-admin';
        $data['url_form'] = '/form-admin';
        $data['url_delete'] = '/delete-admin';
        $data['role'] = $role;

        $html = view('admin/components/view_admin', $data)->render();
        // $pagination = view('components/pagination', $data)->render();

        // echo json_encode(['html' => $html, 'pagination' => $pagination]);

        echo json_encode(['html' => $html]);
    }

    public function view_diagram_pembayaran(Request $request)
    {
        $bulan = Bulan::orderBy('bulan_id','asc')->get();
        $parameter = $request->parameter;
        $param = [];

        if (array_key_exists('tahun', $parameter)) {
            $param['tahun'] = $parameter['tahun'];
        } else {
            $param['tahun'] = Carbon::now()->year;
        }

        $iuran = Iuran::where('iuran_prioritas', 1)->first();

        if ($iuran) {
            $data['status'] = true;
            
            if (array_key_exists('id', $parameter)) {
                $iuran_id = $parameter['id'];
            } else {
                $iuran_id = $iuran->iuran_id;
            }

            $diagram = [];
            $nilai_max = 1000000;

            $records =  Pembayaran::where('pembayaran_iuran_id', $iuran_id)->filter($param)->get()->toArray();

            foreach ($bulan as $key => $value) {
                $array['x'] = $value->bulan_x_line;
                $array['label'] = $value->bulan_nama;

                $filter_key = [
                    'bulan' => $value->bulan_id
                ];

                $filter_month = array_filter( $records, function ($var) use ($filter_key) {
                    return (Carbon::createFromFormat('Y-m-d', $var['pembayaran_tanggal'])->month === $filter_key['bulan']);
                });

                $total = collect($filter_month)->sum('pembayaran_jumlah');
                $array['indexLabel'] = str_replace(',','.', number_format($total));
                $array['y'] = round((($total*100)/$nilai_max));

                $diagram[] = $array;
            }

            $data_iuran = Iuran::where('iuran_id', $iuran_id)->first();

            $data['title'] = $data_iuran->iuran_nama;
            $data['diagram'] = $diagram;
            $data['url'] = '/view-diagram-pembayaran';

            $html = view('dashboard/components/view_diagram_pembayaran', $data)->render();
        } else {
            $html = view('dashboard/components/view_diagram_pembayaran', ['status' => false])->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function view_diagram_main(Request $request)
    {
        $bulan = Bulan::orderBy('bulan_id','asc')->get();
        $parameter = $request->parameter;
        $param = [];

        if (array_key_exists('tahun', $parameter)) {
            $param['tahun'] = $parameter['tahun'];
        } else {
            $param['tahun'] = Carbon::now()->year;
        }

        $iuran = Iuran::where('iuran_prioritas', 1)->first();

        if ($iuran) {
            $data['status'] = true;
            
            if (array_key_exists('id', $parameter)) {
                $iuran_id = $parameter['id'];
            } else {
                $iuran_id = $iuran->iuran_id;
            }

            $diagram = [];
            $nilai_max = 1000000;

            $records =  Pembayaran::where('pembayaran_iuran_id', $iuran_id)->filter($param)->get()->toArray();

            foreach ($bulan as $key => $value) {
                $array['x'] = $value->bulan_x_line;
                $array['label'] = $value->bulan_nama;

                $filter_key = [
                    'bulan' => $value->bulan_id
                ];

                $filter_month = array_filter( $records, function ($var) use ($filter_key) {
                    return (Carbon::createFromFormat('Y-m-d', $var['pembayaran_tanggal'])->month === $filter_key['bulan']);
                });

                $total = collect($filter_month)->sum('pembayaran_jumlah');
                $array['indexLabel'] = str_replace(',','.', number_format($total));
                $array['y'] = round((($total*100)/$nilai_max));

                $diagram[] = $array;
            }

            $data_iuran = Iuran::where('iuran_id', $iuran_id)->first();

            $data['title'] = $data_iuran->iuran_nama;
            $data['diagram'] = $diagram;
            $data['url'] = '/view-diagram-pembayaran';

            $html = view('dashboard/components/view_diagram_pembayaran', $data)->render();
        } else {
            $html = view('dashboard/components/view_diagram_pembayaran', ['status' => false])->render();
        }

        echo json_encode(['html' => $html]);

    }

    // public function view_user(Request $request)
    // {
    //     $parameter = $request->parameter;
    //     $param = [];
    //     $limit = 10;
    //     if ($parameter) {
    //         if (array_key_exists('limit', $parameter)) {
    //             $limit = $parameter['limit'];
    //         }

    //         foreach ($parameter as $key => $value) {
    //             if ($key == 'page' || $key == 'limit' || $value == 'undefined') {
    //                 continue;
    //             } else {
    //                 $param[$key] = $value;
    //             }
    //         }
    //     } else {
    //         $parameter['limit'] = 10;
    //     }

    //     if (array_key_exists('q', $parameter)) {
    //         $param['q'] = $parameter['q'];
    //     } else {
    //         $param['q'] = '';
    //     }

    //     $status = [
    //         '1' => ['nama' => 'Ditinggali Sendiri', 'color' => '#34D399'],
    //         '2' => ['nama' => 'Disewakan', 'color' => '#818CF8'],
    //         '3' => ['nama' => 'Kosong', 'color' => '#9CA3AF'],
    //     ];

    //     $records =  Warga::orderBy('warga_no_rumah','ASC')->filter($param)->get();
    //     $data = $this->pagination($parameter, $limit, $records, '/view-user');

    //     $data['url'] = '/view-user';
    //     $data['url_form'] = '/form-user';
    //     $data['url_delete'] = '/delete-user';
    //     $data['status'] = $status;

    //     $html = view('user/components/view_user', $data)->render();
    //     $pagination = view('components/pagination', $data)->render();

    //     echo json_encode(['html' => $html, 'pagination' => $pagination]);

    // }

    public function view_penyewa(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];

        $kepemilikan = [
            '0' => ['nama' => 'Penyewa', 'color' => '#F59E0B'],
            '1' => ['nama' => 'Pemilik', 'color' => '#3B82F6'],
        ];

        $status = [
            '1' => ['nama' => 'Lajang', 'color' => '#FBBF24'],
            '2' => ['nama' => 'Menikah', 'color' => '#34D399'],
            '3' => ['nama' => 'Janda/Duda', 'color' => '#A78BFA'],
        ];

        $kondisi = [
            '0' => ['nama' => 'Tidak Aktif', 'color' => '#EF4444'],
            '1' => ['nama' => 'Aktif', 'color' => '#10B981'],
        ];

        $data['records'] =  Penyewa::with('penghuni')->where('penyewa_warga_id', $parameter['id'])->orderBy('penyewa_id','desc')->get();

        $data['url'] = '/view-penyewa';
        $data['url_form'] = '/form-penyewa';
        $data['url_delete'] = '/delete-penyewa';
        $data['kepemilikan'] = $kepemilikan;
        $data['status'] = $status;
        $data['kondisi'] = $kondisi;

        $html = view('user/components/view_penyewa', $data)->render();

        echo json_encode(['html' => $html]);

    }

    public function view_penghuni(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];

        $status = [
            '1' => ['nama' => 'Kepala Keluarga'],
            '2' => ['nama' => 'Istri'],
            '3' => ['nama' => 'Anak'],
            '4' => ['nama' => 'Orang Tua'],
            '5' => ['nama' => 'Saudara'],
            '6' => ['nama' => 'Teman'],
            '7' => ['nama' => 'Lainnya'],
        ];

        $kondisi = [
            '0' => ['nama' => 'Tidak Aktif', 'color' => '#EF4444'],
            '1' => ['nama' => 'Aktif', 'color' => '#10B981'],
        ];

        $data['records'] =  Penghuni::where('penghuni_penyewa_id', $parameter['id'])->orderBy('penghuni_status','asc')->get();

        $data['url'] = '/view-penghuni';
        $data['url_form'] = '/form-penghuni';
        $data['url_delete'] = '/delete-penghuni';
        $data['status'] = $status;
        $data['kondisi'] = $kondisi;

        $html = view('user/components/view_penghuni', $data)->render();

        echo json_encode(['html' => $html]);

    }

    public function view_iuran(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined') {
                    continue;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = 10;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $records =  Iuran::with('partisipan')->orderBy('iuran_id','DESC')->filter($param)->get();
        $data = $this->pagination($parameter, $limit, $records, '/view-iuran');

        $data['url'] = '/view-iuran';
        $data['url_form'] = '/form-iuran';
        $data['url_delete'] = '/delete-iuran';

        $html = view('iuran/components/view_iuran', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_partisipan(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined') {
                    continue;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = 10;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $kategori = [
            '1' => ['nama' => 'Pemilik', 'color' => '#34D399'],
            '2' => ['nama' => 'Penyewa', 'color' => '#A78BFA'],
            '3' => ['nama' => 'Umum', 'color' => '#F472B6'],
        ];

        $records =  Partisipan::with('pembayaran')->orderBy('partisipan_alamat','ASC')->filter($param)->get();
        $data = $this->pagination($parameter, $limit, $records, '/view-partisipan');

        $data['url'] = '/view-partisipan';
        $data['url_form'] = '/form-partisipan';
        $data['url_delete'] = '/delete-partisipan';
        $data['kategori'] = $kategori;

        $html = view('iuran/components/view_partisipan', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_pembayaran(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined' || $key == 'id') {
                    continue;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = 10;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $pembayaran = [
            '0' => ['nama' => 'CASH', 'color' => '#34D399'],
            '1' => ['nama' => 'TRANSFER', 'color' => '#A78BFA'],
        ];

        $records =  Pembayaran::with('admin')->where('pembayaran_partisipan_id', $parameter['id'])->orderBy('created_at','desc')->get();
        $data = $this->pagination($parameter, $limit, $records, '/view-pembayaran');

        $data['url'] = '/view-pembayaran';
        $data['url_form'] = '/form-partisipan-pembayaran';
        $data['url_delete'] = '/delete-pembayaran';
        $data['pembayaran'] = $pembayaran;

        $html = view('iuran/components/view_pembayaran', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_rekap_pembayaran(Request $request)
    {
        $parameter = $request->parameter;
        $param = [];
        $limit = 10;
        if ($parameter) {
            if (array_key_exists('limit', $parameter)) {
                $limit = $parameter['limit'];
            }

            foreach ($parameter as $key => $value) {
                if ($key == 'page' || $key == 'limit' || $value == 'undefined' || $key == 'id') {
                    continue;
                } else {
                    $param[$key] = $value;
                }
            }
        } else {
            $parameter['limit'] = 10;
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        if (array_key_exists('tahun', $parameter)) {
            $param['tahun'] = $parameter['tahun'];
        } else {
            $param['tahun'] = Carbon::now()->year;;
        }

        $pembayaran = [
            '0' => ['nama' => 'CASH', 'color' => '#34D399'],
            '1' => ['nama' => 'TRANSFER', 'color' => '#A78BFA'],
        ];

        $records =  Pembayaran::with('partisipan')->where('pembayaran_iuran_id', $parameter['id'])->filter($param)->orderBy('pembayaran_tanggal','desc')->get();
        $data = $this->pagination($parameter, $limit, $records, '/view-pembayaran');

        $total = collect($records)->sum('pembayaran_jumlah');

        $data['url'] = '/view-rekap-pembayaran';
        $data['pembayaran'] = $pembayaran;
        $data['total'] = $total;

        $html = view('iuran/components/view_rekap_pembayaran', $data)->render();
        $pagination = view('components/pagination', $data)->render();

        echo json_encode(['html' => $html, 'pagination' => $pagination]);

    }

    public function view_sumbangan(Request $request)
    {
        $parameter = $request->parameter;
        $param['id'] = 3;

        if ($parameter) {
            foreach ($parameter as $key => $value) {
                if ($value == 'undefined') {
                    continue;
                } else {
                    $param[$key] = $value;
                }
            }
        }

        if (array_key_exists('q', $parameter)) {
            $param['q'] = $parameter['q'];
        } else {
            $param['q'] = '';
        }

        $records =  Partisipan::with('pembayaran')->orderBy('partisipan_alamat','ASC')->filter($param)->get();
        $data['records'] = $records; 

        $pembayaran =  Pembayaran::where('pembayaran_iuran_id', $param['id'])->get();

        $total = collect($pembayaran)->sum('pembayaran_jumlah');
        $data['total'] = $total;

        $html = view('main/components/view_sumbangan', $data)->render();

        echo json_encode(['html' => $html]);

    }













    public function pagination($parameter, $limit, $model, $url)
    {
        $data['page'] = (array_key_exists('page', $parameter) && $parameter['page'] > 0) ? $parameter['page'] : 1;
        
        $limit_start = ($data['page'] - 1) * $limit;
        $data['no'] = $limit_start + 1;

        $data['records'] = array_slice(json_decode($model), $limit_start, $limit, true);
        $data['total_records'] = count($model);

        $data['jumlah_page'] = ceil($data['total_records']/ $limit);
        $data['jumlah_number'] = 1;
        $data['start_number'] = ($data['page'] > $data['jumlah_number'])? $data['page'] - $data['jumlah_number'] : 1;
        $data['end_number'] = ($data['page'] < ($data['jumlah_page'] - $data['jumlah_number']))? $data['page'] + $data['jumlah_number'] : $data['jumlah_page'];

        $data['url_view'] = $url;

        return $data;
    }
}
