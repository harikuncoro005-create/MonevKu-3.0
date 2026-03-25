<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Fisik;
use App\Models\Instansi;
use App\Models\Keluaran;
use App\Models\Keuangan;
use App\Models\Lampiran;
use App\Models\NomenklaturPerencanaan;
use App\Models\Penyewa;
use App\Models\Permasalahan;
use App\Models\Permission;
use App\Models\RefKode;
use App\Models\RencanaKeluaran;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
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

    public function profile(Request $request)
    {
        // if ($this->authorize('administrator')) {
        // if (Gate::allows('administrator')) {
        if (request()->is('pengaturan/admin/profile')) {
            if ($request->get('id')) {
                $id = $request->get('id');
                $result = Admin::where('admin_id', $id)->first();
                if ($result) {
                    return view('user.profile', [
                        'titlePage' => 'Profile',
                        'url_form' => '/form-profile',
                        'result' => $result
                    ]);
                } else {
                    return view('404_admin', ['url' => '/pengaturan/admin', 'message' => 'Data Tidak Ditemukan']);
                }
            } else {
                return view('404_admin', ['url' => '/pengaturan/admin', 'message' => 'Data Tidak Ditemukan']);
            }
        } else {
            $result = auth()->user();
            return view('user.profile', [
                'titlePage' => 'Profile',
                'url_form' => '/form-profile',
                'result' => $result
            ]);
        }

        
        
    }

    public function dashboard_user(Request $request)
    {
        if ($request->get('bulan')) {
            $bulan_index = $request->get('bulan');
        } else {
            $bulan_index = Carbon::createFromFormat('m', date('m'))->subMonth()->format('m');
            // $bulan_index = date('m');
        }

        if (session()->has('session_instansi')) {
            $instansi = Instansi::where('kode', 1)->where('instansi_kode', session('session_instansi'))->first();
            return view('user.dashboard_user', [
                'titlePage' => 'Dashboard',
                'url_form' => '/form-session',
                'url_view' => '/view-dashboard-user',
                'bulan' => $this->bulan,
                'bulan_index' => $bulan_index,
                'instansi' => $instansi
            ]);
        } else {
            return view('user.dashboard_instansi', [
                'titlePage' => 'Dashboard',
                'url_form' => '/form-session',
            ]);
        }

        
    }

    public function sesi()
    {
        return view('user.sesi', [
            'titlePage' => 'Sesi',
            'url_form' => '/form-session',
        ]);
    }

    // public function dpa()
    // {
    //     return view('user.dpa', [
    //         'titlePage' => 'DPA'
    //     ]);
    // }

    // public function dpa_indikator()
    // {
    //     return view('user.dpa_indikator', [
    //         'titlePage' => 'DPA Indikator'
    //     ]);
    // }

    // public function dpa_indikator_detail()
    // {
    //     return view('user.dpa_indikator_detail', [
    //         'titlePage' => 'DPA Indikator Detail'
    //     ]);
    // }

    public function renja()
    {
        return view('user.renja', [
            'titlePage' => 'Rencana Kerja',
            'url_view' => '/view-renja',
        ]);
    }

    public function renja_indikator(Request $request)
    {
        if ($request->get('ref')) {
            $instansi_kode = session('session_instansi');
            $sesi_kode = session('session_kode')->sesi_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->get('ref'))->first();
        
            $nomenklatur_kode_all = [];
            $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
            foreach ($ref_kode as $idx => $item) {
                $index = explode('.', $nomenklatur['nomenklatur_kode']);
                $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                array_push($nomenklatur_kode_all, $nomenklatur_kode);
                $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
            }

            $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

            $keluaran = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', $sesi_kode)
            ->where('keluaran_jenis', 0)->get();

            $keluaran_kepmen = $keluaran->first(function ($item) {
                return $item->keluaran_tipe == 1;
            });

            $keluaran_riil = $keluaran->filter(function ($item) {
                return $item->keluaran_tipe == 2;
            });

            return view('user.renja_indikator', [
                'titlePage' => 'Rencana Kerja',
                'url_view' => '/view-renja-indikator',
                'url_form' => '/form-renja-indikator',
                'ref_kode' => $ref_kode,
                'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                'nomenklatur_subkegiatan' => $nomenklatur,
                'keluaran_kepmen' => $keluaran_kepmen,
                'keluaran_riil' =>  $keluaran_riil
            ]);
        } else {
            return redirect('/renja');
        }
        
        
    }

    public function rencana_keluaran()
    {
        return view('user.rencana_keluaran', [
            'titlePage' => 'Rencana Keluaran',
            'url_view' => '/view-rencana-keluaran',
        ]);
    }

    public function rencana_keluaran_detail(Request $request)
    {
        if ($request->get('ref')) {
            $instansi_kode = session('session_instansi');
            $sesi_kode = session('session_kode')->sesi_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->get('ref'))->first();

            $nomenklatur_kode_all = [];
            $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
            foreach ($ref_kode as $idx => $item) {
                $index = explode('.', $nomenklatur['nomenklatur_kode']);
                $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                array_push($nomenklatur_kode_all, $nomenklatur_kode);
                $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
            }

            $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

            $data = [
                'titlePage' => 'Detail Rencana Keluaran',
                'url_form' => '/form-rencana-keluaran',
                'url_approve' => '/approve-admin',
                'bulan' => $this->bulan,
                'ref_kode' => $ref_kode,
                'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                'nomenklatur_subkegiatan' => $nomenklatur
            ];

            $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', $sesi_kode)
            ->where('keluaran_jenis', 0)
            ->where('keluaran_tipe', 1)->first();

            $count = [];

            if ($keluaran_kepmen) {
                $data['keluaran_kepmen'] = $keluaran_kepmen;
                foreach ($this->bulan as $key => $value) {
                    array_push($count, $keluaran_kepmen->{'keluaran_'.$key});
                }
                $data['total'] = array_sum($count);
                return view('user.rencana_keluaran_detail', $data);
            } else {
                return view('404_admin', ['url' => '/renja', 'message' => 'Rencana Kerja Belum Di Input Silahkan Dilengkapi Terlebih Dahulu']);
            }
            
        } else {
            return redirect('/rencana-keluaran');
        }
    }

    public function ropk_fisik()
    {
        return view('user.ropk_fisik', [
            'titlePage' => 'ROPK Fisik',
            'url_view' => '/view-ropk-fisik',
        ]);
    }

    public function ropk_fisik_detail(Request $request)
    {
        if ($request->get('ref')) {
            $instansi_kode = session('session_instansi');
            $sesi_kode = session('session_kode')->sesi_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->get('ref'))->first();

            $nomenklatur_kode_all = [];
            $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
            foreach ($ref_kode as $idx => $item) {
                $index = explode('.', $nomenklatur['nomenklatur_kode']);
                $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                array_push($nomenklatur_kode_all, $nomenklatur_kode);
                $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
            }

            $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

            $data = [
                'titlePage' => 'Detail ROPK Fisik',
                'url_form' => '/form-ropk-fisik',
                'url_view' => '/view-ropk-fisik-detail',
                'bulan' => $this->bulan,
                'ref_kode' => $ref_kode,
                'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                'nomenklatur_subkegiatan' => $nomenklatur
            ];

            $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', $sesi_kode)
            ->where('keluaran_jenis', 0)
            ->where('keluaran_tipe', 1)->first();

            $data['keluaran_kepmen'] = $keluaran_kepmen;

            $ropk_fisik = Fisik::where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->where('fisik_jenis', 0)->orderByRaw('CAST(fisik_nomor AS UNSIGNED) ASC')->get();

            if (count($ropk_fisik) != 0) {
                $ropk_fisik_status = count($ropk_fisik) == $ropk_fisik->sum('fisik_status') ? true : false;
            } else {
                $ropk_fisik_status = false;
            }
            
            $data['ropk_fisik_status'] = $ropk_fisik_status;

            return view('user.ropk_fisik_detail', $data);
        } else {
            return redirect('/ropk-fisik');
        }

    }

    public function ropk_keuangan()
    {
        return view('user.ropk_keuangan', [
            'titlePage' => 'ROPK Keuangan',
            'url_view' => '/view-ropk-keuangan',
        ]);
    }

    public function monev(Request $request)
    {
        if ($request->get('bulan')) {
            $bulan = $request->get('bulan');
        } else {
            $bulan = Carbon::createFromFormat('m', date('m'))->subMonth()->format('m');
            // $bulan = date('m');
        }
        return view('user.monev', [
            'titlePage' => 'Monev Bulanan',
            'bulan' => $this->bulan,
            'bulan_index' => $bulan,
            'url_view' => '/view-monev',
        ]);
    }

    public function monev_detail(Request $request)
    {
        if ($request->get('ref')) {
            $instansi_kode = session('session_instansi');
            $sesi_kode = session('session_kode')->sesi_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->get('ref'))->first();
            if ($nomenklatur) {
                $bulan = $this->bulan;
            
                $nomenklatur_kode_all = [];
                $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
                foreach ($ref_kode as $idx => $item) {
                    $index = explode('.', $nomenklatur['nomenklatur_kode']);
                    $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                    array_push($nomenklatur_kode_all, $nomenklatur_kode);
                    $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
                }

                $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

                // KELUARAN

                $keluaran = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                ->where('keluaran_instansi_kode', $instansi_kode)
                ->where('keluaran_sesi_kode', $sesi_kode)
                ->where('keluaran_tipe', 1)
                ->get();

                if (count($keluaran) != 0) {
                    $keluaran_target = $keluaran->first(function ($item) {
                        return $item->keluaran_jenis == 0;
                    });

                    $keluaran_realisasi = $keluaran->first(function ($item) {
                        return $item->keluaran_jenis == 1;
                    });

                    $keluaran_total_target = 0;
                    $keluaran_kumulatif_target = [];

                    $keluaran_total_realisasi = 0;
                    $keluaran_kumulatif_realisasi = [];

                    foreach ($this->bulan as $key => $value) {
                        $keluaran_total_target += $keluaran_target->{'keluaran_'.$key};
                        $keluaran_kumulatif_target[$key] = $keluaran_total_target;
                    }

                    if ($keluaran_realisasi) {
                        foreach ($this->bulan as $key => $value) {
                            $keluaran_total_realisasi += $keluaran_realisasi->{'keluaran_'.$key};
                            $keluaran_kumulatif_realisasi[$key] = $keluaran_total_realisasi;
                        }
                    }

                    

                    // KEUANGAN
                    $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_sesi_kode', $sesi_kode)->where('keuangan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->get();

                    $keuangan_target = $keuangan->first(function ($item) {
                        return $item->keuangan_jenis == 0;
                    });

                    $keuangan_realisasi = $keuangan->first(function ($item) {
                        return $item->keuangan_jenis == 1;
                    });
                    

                    $keuangan_total_target = 0;
                    $keuangan_kumulatif_target = [];
                    $keuangan_presentase_kumulatif_target = [];

                    $keuangan_total_realisasi = 0;
                    $keuangan_kumulatif_realisasi = [];
                    $keuangan_presentase_kumulatif_realisasi = [];

                    foreach ($this->bulan as $key => $value) {
                        $keuangan_total_target += $keuangan_target->{'keuangan_'.$key};
                        $keuangan_kumulatif_target[$key] = $keuangan_total_target;
                        $keuangan_presentase_kumulatif_target[$key] = number_format(round(($keuangan_total_target*100)/$keuangan_target->keuangan_pagu, 2), 2);
                    }

                    if ($keuangan_realisasi) {
                        foreach ($this->bulan as $key => $value) {
                            $keuangan_total_realisasi += $keuangan_realisasi->{'keuangan_'.$key};
                            $keuangan_kumulatif_realisasi[$key] = $keuangan_total_realisasi;
                            $keuangan_presentase_kumulatif_realisasi[$key] = number_format(round(($keuangan_total_realisasi*100)/$keuangan_realisasi->keuangan_pagu, 2), 2);
                        }
                    }

                    // FISIK

                    $fisik = Fisik::where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                    ->where('fisik_instansi_kode', $instansi_kode)
                    ->where('fisik_sesi_kode', $sesi_kode)
                    ->get();

                    $fisik_target = $fisik->filter(function ($item) {
                        return $item->fisik_jenis == 0;
                    });

                    $fisik_realisasi = $fisik->filter(function ($item) {
                        return $item->fisik_jenis == 1;
                    });

                    $fisik_total_target = 0;
                    $fisik_kumulatif_target = [];

                    foreach ($this->bulan as $key => $value) {
                        $fisik_total_target += array_sum($fisik_target->pluck('fisik_'.$key)->toArray());
                        $fisik_kumulatif_target[$key] = $fisik_total_target;
                    }

                    $fisik_total_realisasi = 0;
                    $fisik_kumulatif_realisasi = [];

                    if ($fisik_realisasi) {
                        foreach ($this->bulan as $key => $value) {
                            $fisik_total_realisasi += array_sum($fisik_realisasi->pluck('fisik_'.$key)->toArray());
                            $fisik_kumulatif_realisasi[$key] = $fisik_total_realisasi;
                        }
                    }

                    // PERMASALAHAN
                    $permasalahan = Permasalahan::where('permasalahan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->where('permasalahan_instansi_kode', $instansi_kode)->where('permasalahan_sesi_kode', $sesi_kode)->get();
                    $permasalahan = $permasalahan->keyBy('permasalahan_bulan');

                    return view('user.monev_detail', [
                        'titlePage' => 'Monev Bulanan',
                        'url_view' => '/view-monev-detail',
                        'url_form' => '/form-monev-detail',
                        'ref_kode' => $ref_kode,
                        'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                        'nomenklatur_subkegiatan' => $nomenklatur,
                        'keluaran_kumulatif_target' => $keluaran_kumulatif_target,
                        'keluaran_kumulatif_realisasi' => $keluaran_kumulatif_realisasi,
                        'fisik_kumulatif_target' => $fisik_kumulatif_target,
                        'fisik_kumulatif_realisasi' => $fisik_kumulatif_realisasi,
                        'keuangan_target' => $keuangan_target,
                        'keuangan_kumulatif_target' => $keuangan_kumulatif_target,
                        'keuangan_presentase_kumulatif_target' => $keuangan_presentase_kumulatif_target,
                        'keuangan_kumulatif_realisasi' => $keuangan_kumulatif_realisasi,
                        'keuangan_presentase_kumulatif_realisasi' => $keuangan_presentase_kumulatif_realisasi,
                        'bulan' => $bulan,
                        'permasalahan' => $permasalahan
                    ]);
                } else {
                    return view('404_admin', ['url' => '/monev', 'message' => 'Rencana Keluaran Belum Di Input Silahkan Dilengkapi Terlebih Dahulu']);
                }

                
            } else {
                return view('404_admin', ['url' => '/monev', 'message' => 'Data Tidak Ditemukan']);
            }
        } else {
            return redirect('/monev');
        }
        
    }

    public function monev_realisasi(Request $request)
    {
        if ($request->get('ref') && $request->get('bulan')) {
            $tahun =  session('session_tahun');
            $instansi_kode = session('session_instansi');
            $sesi_kode = session('session_kode')->sesi_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->get('ref'))->first();
            $bulan_avaiable = array_key_exists($request->get('bulan'), $this->bulan);

            if ($nomenklatur && $bulan_avaiable) {
                $nomenklatur_kode_all = [];
                $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
                foreach ($ref_kode as $idx => $item) {
                    $index = explode('.', $nomenklatur['nomenklatur_kode']);
                    $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                    array_push($nomenklatur_kode_all, $nomenklatur_kode);
                    $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
                }

                $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

                $keluaran = Keluaran::with('lampiran_keluaran')->where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                ->where('keluaran_instansi_kode', $instansi_kode)
                ->where('keluaran_sesi_kode', $sesi_kode)
                ->where('keluaran_tipe', 1)->get();

                $data = [
                    'titlePage' => 'Realisasi Rencana Keluaran',
                    'bulan' => $this->bulan[$request->get('bulan')],
                    'bulan_index' => $request->get('bulan'),
                    'ref_kode' => $ref_kode,
                    'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                    'nomenklatur_subkegiatan' => $nomenklatur,
                    'url_form_fisik' => '/form-realisasi-fisik',
                    'url_form_keluaran' => '/form-realisasi-keluaran',
                    'url_form_permasalahan' => '/form-permasalahan'
                ];


                if ($keluaran) {
                
                    // KELUARAN

                    $rencana_keluaran_target = $keluaran->first(function ($item) {
                        return $item->keluaran_jenis == 0;
                    });

                    $data['keluaran_kepmen'] = $rencana_keluaran_target;

                    $total_kumulatif_target_keluaran = 0;
                    $target_keluaran_komulatif = [];
                    foreach ($this->bulan as $key => $value) {
                        $total_kumulatif_target_keluaran += $rencana_keluaran_target->{'keluaran_'.$key};
                        $target_keluaran_komulatif[$key] = $total_kumulatif_target_keluaran;
                    }

                    $rencana_keluaran_realisasi = $keluaran->first(function ($item) {
                        return $item->keluaran_jenis == 1;
                    });

                    $total_kumulatif_realisasi_keluaran = 0;
                    $realisasi_keluaran_komulatif = [];

                    if ($rencana_keluaran_realisasi) {
                        foreach ($this->bulan as $key => $value) {
                            $total_kumulatif_realisasi_keluaran += $rencana_keluaran_realisasi->{'keluaran_'.$key};
                            $realisasi_keluaran_komulatif[$key] = $total_kumulatif_realisasi_keluaran;
                        }
                    }

                    $data['lampiran_keluaran'] = $rencana_keluaran_realisasi ? $rencana_keluaran_realisasi->lampiran_keluaran : [];


                    // KEUANGAN

                    $keuangan = Keuangan::where('keuangan_instansi_kode', $instansi_kode)->where('keuangan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->where('keuangan_sesi_kode', $sesi_kode)->get();

                    $rencana_keuangan_target = $keuangan->filter(function ($item) {
                        return $item->keuangan_jenis == 0;
                    });

                    $total_kumulatif_target_keuangan = 0;
                    $target_keuangan_komulatif = [];
                    foreach ($this->bulan as $key => $value) {
                        $total_kumulatif_target_keuangan += $rencana_keuangan_target->first()->{'keuangan_'.$key};
                        $target_keuangan_komulatif[$key] = $total_kumulatif_target_keuangan;
                    }

                    $rencana_keuangan_realisasi = $keuangan->filter(function ($item) {
                        return $item->keuangan_jenis == 1;
                    });

                    $total_kumulatif_realisasi_keuangan = 0;
                    $realisasi_keuangan_komulatif = [];
                    if (count($rencana_keuangan_realisasi) != 0) {
                        foreach ($this->bulan as $key => $value) {
                            $total_kumulatif_realisasi_keuangan += $rencana_keuangan_realisasi->first()->{'keuangan_'.$key};
                            $realisasi_keuangan_komulatif[$key] = $total_kumulatif_realisasi_keuangan;
                        }
                    }
                

                    // FISIK

                    $fisik = Fisik::with('lampiran_fisik')->where('fisik_instansi_kode', $instansi_kode)->where('fisik_sesi_kode', $sesi_kode)->where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)->orderByRaw('CAST(fisik_nomor AS UNSIGNED) ASC')->get();

                    $tahapan = [
                        1 => ['nama' => 'Persiapan', 'data' => []],
                        2 => ['nama' => 'Pelaksanaan', 'data' => []],
                        3 => ['nama' => 'Pelaporan', 'data' => []],
                    ];

                    $rencana_fisik_target = $fisik->filter(function ($item) {
                        return $item->fisik_jenis == 0;
                    });

                    $total_kumulatif_target_fisik = 0;
                    $target_fisik_komulatif = [];
                    foreach ($this->bulan as $key => $value) {
                        $total_kumulatif_target_fisik += array_sum($rencana_fisik_target->pluck('fisik_'.$key)->toArray());
                        $target_fisik_komulatif[$key] = $total_kumulatif_target_fisik;
                    }

                    $rencana_fisik_realisasi = $fisik->filter(function ($item) {
                        return $item->fisik_jenis == 1;
                    });

                    $fisik_realisasi_bulan = array_sum($rencana_fisik_realisasi->pluck('fisik_'.$request->get('bulan'))->toArray());

                    $total_kumulatif_realisasi_fisik = 0;
                    $realisasi_fisik_komulatif = [];
                    foreach ($this->bulan as $key => $value) {
                        $total_kumulatif_realisasi_fisik += array_sum($rencana_fisik_realisasi->pluck('fisik_'.$key)->toArray());
                        $realisasi_fisik_komulatif[$key] = $total_kumulatif_realisasi_fisik;
                    }

                    foreach ($rencana_fisik_target as $key => $value) {
                        if ($value['fisik_tahapan'] == 1) {
                            $tahapan[1]['data'][] =  $value->toArray();
                        } else if ($value['fisik_tahapan'] == 2) {
                            $tahapan[2]['data'][] =  $value->toArray();
                        } else {
                            $tahapan[3]['data'][] =  $value->toArray();
                        }
                    }
                    

                    $fisik_realisasi_kode = [];

                    if (count($rencana_fisik_realisasi) != 0) {
                        $fisik_realisasi_kode = $rencana_fisik_realisasi->keyBy('fisik_kode');
                    }

                    // PERMASALAHAN
                    $permasalahan = Permasalahan::where('permasalahan_instansi_kode', $instansi_kode)
                    ->where('permasalahan_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                    ->where('permasalahan_bulan', $request->get('bulan'))->where('permasalahan_sesi_kode', session('session_kode')->sesi_kode)->first();

                    $data['rencana_keluaran_target'] = $target_keluaran_komulatif;
                    $data['rencana_keluaran_realisasi'] = $realisasi_keluaran_komulatif;
                    $data['keuangan_target'] = $target_keuangan_komulatif;
                    $data['keuangan_realisasi'] = $realisasi_keuangan_komulatif;
                    $data['keuangan'] = $rencana_keuangan_realisasi->first();
                    $data['fisik_target'] = $target_fisik_komulatif;
                    $data['fisik_realisasi'] = $realisasi_fisik_komulatif;
                    $data['fisik_realisasi_bulan'] = $fisik_realisasi_bulan;
                    $data['tahapan'] = $tahapan;
                    $data['fisik_realisasi_kode'] = $fisik_realisasi_kode;
                    
                    $data['permasalahan'] = $permasalahan;

                    $data['rencana_keluaran_realisasi_bulan'] = $rencana_keluaran_realisasi;

                    // PERMISSION
                    $auth = Permission::where('auth_uid', $tahun.$instansi_kode)->first();
                    $data['permission'] = $auth ? $auth->{'auth_'.$request->get('bulan')} : 0;

                    return view('user.realisasi', $data);

                } else {
                    return view('404_admin', ['url' => '/monev', 'message' => 'Rencana Keluaran Belum Di Input Silahkan Dilengkapi Terlebih Dahulu']);
                    // return view('user.realisasi', $data);
                }
                
            } else {
                return view('404_admin', ['url' => '/monev/detail?ref='.$request->get('ref'), 'message' => 'Data Tidak Ditemukan']);
            }
            
        } else {
            return view('404_admin', ['url' => '/monev/detail?ref='.$request->get('ref'), 'message' => 'Data Tidak Ditemukan']);
        }
        
        
    }

    public function monev_realisasi_fisik(Request $request)
    {
        if ($request->get('ref') && $request->get('bulan')) {
            $instansi_kode = auth()->user()->admin_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->get('ref'))->first();
            $bulan_avaiable = array_key_exists($request->get('bulan'), $this->bulan);

            if ($nomenklatur &&  $bulan_avaiable) {
                $nomenklatur_kode_all = [];
                $ref_kode = RefKode::whereNotIn('kode_id',['1','2'])->get();
                foreach ($ref_kode as $idx => $item) {
                    $index = explode('.', $nomenklatur['nomenklatur_kode']);
                    $nomenklatur_kode = implode('.', array_slice($index, 0, $item['kode_index']));
                    array_push($nomenklatur_kode_all, $nomenklatur_kode);
                    $ref_kode[$idx]['kode_nomenklatur'] = $nomenklatur_kode;
                }

                $nomenklatur_all = NomenklaturPerencanaan::whereIn('nomenklatur_kode', $nomenklatur_kode_all)->get();

                $keluaran = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                ->where('keluaran_instansi_kode', $instansi_kode)
                ->where('keluaran_sesi_kode', 'LIRS6N')
                ->where('keluaran_kode', 1)->first();

                if ($keluaran) {

                    $data = [
                        'titlePage' => 'Realisasi Fisik',
                        'bulan' => $this->bulan[$request->get('bulan')],
                        'bulan_index' => $request->get('bulan'),
                        'ref_kode' => $ref_kode,
                        'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                        'nomenklatur_subkegiatan' => $nomenklatur,
                        'keluaran_kepmen' => $keluaran
                    ];

                    $ropk_fisik = Fisik::where('fisik_keluaran_id', $keluaran->keluaran_id)->orderBy('fisik_nomor','asc')->get();

                    $fisik_target = $ropk_fisik->filter(function ($item) {
                        return $item->fisik_jenis == 0;
                    });
                
                    $tahapan = [
                        1 => ['nama' => 'Persiapan'],
                        2 => ['nama' => 'Pelaksanaan'],
                        3 => ['nama' => 'Pelaporan'],
                    ];
                    
                    foreach ($fisik_target as $key => $value) {
                        if ($value['fisik_tahapan'] == 1) {
                            $tahapan[1]['data'][] =  $value->toArray();
                        } else if ($value['fisik_tahapan'] == 2) {
                            $tahapan[2]['data'][] =  $value->toArray();
                        } else {
                            $tahapan[3]['data'][] =  $value->toArray();
                        }
                    }

                    $data['tahapan'] = $tahapan;
                    $data['fisik_target'] = $fisik_target;
                    return view('user.realisasi_fisik', $data);

                } else {
                    return view('404', ['url' => '/monev/detail?ref='.$request->get('ref')]);
                }
                

                // $rencana_keluaran_target = $keluaran->rencana_keluaran->filter(function ($item) {
                //     return $item->rencana_keluaran_jenis == 0;
                // });

                // $total_kumulatif_target_fisik = 0;
                // $target_fisik_komulatif = [];
                // foreach ($this->bulan as $key => $value) {
                //     $total_kumulatif_target_fisik += $rencana_keluaran_target->first()->{'rencana_keluaran_'.$key};
                //     $target_fisik_komulatif[$key] = $total_kumulatif_target_fisik;
                // }

                // $rencana_keluaran_realisasi = $keluaran->rencana_keluaran->filter(function ($item) {
                //     return $item->rencana_keluaran_jenis == 1;
                // });

                // // dd($rencana_keluaran_realisasi->first());

                // $data = [
                //     'titlePage' => 'Realisasi Fisik',
                //     'bulan' => $this->bulan[$request->get('bulan')],
                //     'bulan_index' => $request->get('bulan'),
                //     'ref_kode' => $ref_kode,
                //     'nomenklatur' => $nomenklatur_all->keyBy('nomenklatur_kode'),
                //     'nomenklatur_subkegiatan' => $nomenklatur,
                //     'keluaran_kepmen' => $keluaran,
                //     'rencana_keluaran_target' => $target_fisik_komulatif
                // ];

                // if ($rencana_keluaran_realisasi->isNotEmpty()) {
                //     $data['rencana_keluaran_realisasi'] = $rencana_keluaran_realisasi->first();
                //     return view('user.realisasi_rencana_keluaran', $data);
                // } else {
                    
                //     $input = [
                //         'rencana_keluaran_keluaran_id' => $keluaran->keluaran_id,
                //         'rencana_keluaran_instansi_kode' => $keluaran->keluaran_instansi_kode,
                //         'rencana_keluaran_subkegiatan_kode' => $keluaran->keluaran_subkegiatan_kode,
                //         'rencana_keluaran_tahun' => $keluaran->keluaran_tahun,
                //         'rencana_keluaran_sesi_kode' => $keluaran->keluaran_sesi_kode,
                //         'rencana_keluaran_jenis' => 1
                //     ];

                //     $rencana_keluaran = RencanaKeluaran::create($input);
                //     $rencana_keluaran_realisasi_new =  RencanaKeluaran::find($rencana_keluaran->rencana_keluaran_id);

                //     $data['rencana_keluaran_realisasi'] = $rencana_keluaran_realisasi_new;
                //     return view('user.realisasi_rencana_keluaran', $data);
                // }
                
            } else {
                return view('404', ['url' => '/monev/detail?ref='.$request->get('ref')]);
            }
            
        } else {
            return redirect('/monev/detail?ref='.$request->get('ref'));
        }
        
        
    }

    public function pelaporan()
    {
        $laporan_jenis = [
            '1' => 'Laporan APBD Bulanan',
            '2' => 'Laporan Rencana Aksi',
            '3' => 'Lampiran Rapor',
            '4' => 'Laporan Evaluasi Terhadap Hasil Renja'
        ];

        $instansi = Instansi::where('instansi_kode', session('session_instansi'))->first();

        return view('user.pelaporan', [
            'titlePage' => 'Pelaporan',
            'bulan' => $this->bulan,
            'tahun' => session('session_tahun'),
            'instansi' => $instansi,
            'laporan_jenis' => $laporan_jenis,
            'url_view' => '/view-pelaporan',
        ]);
    }














    public function user()
    {
        // $warga = Warga::with('penyewa')->get();
        // dd($warga);
        return view('user.user', [
            'titlePage' => 'Data Warga',
            'url' => '/view-user',
            'url_form' => '/form-user'
        ]);
    }

    public function detail(Request $request)
    {
        if ($request->get('id')) {

            $status = [
                '1' => ['nama' => 'Ditinggali Sendiri', 'color' => '#34D399'],
                '2' => ['nama' => 'Disewakan', 'color' => '#818CF8'],
                '3' => ['nama' => 'Kosong', 'color' => '#9CA3AF'],
            ];

            $user = Warga::where('warga_id',  $request->get('id'))->first();
            if ($user) {
                return view('user.detail', [
                    'titlePage' => 'Data Warga',
                    'url' => '/view-penyewa',
                    'url_form' => '/form-penyewa',
                    'status' => $status,
                    'data' => $user
                ]);
            } else {
                return redirect('/user');
            }
        } else {
            return redirect('/user');
        }
        
    }

    public function penghuni(Request $request)
    {
        if ($request->get('id')) {
            $user = Penyewa::with('warga')->where('penyewa_id',  $request->get('id'))->first();
            if ($user) {
                return view('user.penghuni', [
                    'titlePage' => 'Data Penghuni',
                    'url' => '/view-penghuni',
                    'url_form' => '/form-penghuni',
                    'data' => $user
                ]);
            } else {
                return redirect('/user');
            }
        } else {
            return redirect('/user');
        }
        
    }
    
    
}
