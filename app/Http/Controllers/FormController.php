<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dokumen;
use App\Models\Fisik;
use App\Models\Instansi;
use App\Models\Iuran;
use App\Models\Keluaran;
use App\Models\Lampiran;
use App\Models\LampiranFisik;
use App\Models\LampiranKeluaran;
use App\Models\NomenklaturPerencanaan;
use App\Models\Partisipan;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use App\Models\PenilaianRencana;
use App\Models\PenilaianRencanaItem;
use App\Models\Penyewa;
use App\Models\Permasalahan;
use App\Models\RencanaKeluaran;
use App\Models\Sesi;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FormController extends Controller
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

    public function form_session(Request $request)
    {
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';
        
        $data = [
            'title' => $request->title,
        ];
        
        if ($request->to == 'create-instansi') {
            $otorisasi = auth()->user()->admin_otorisasi;
            $result = Instansi::where('kode', 1)->whereIn('instansi_kode', $otorisasi)->get();

            $data['session'] = 'instansi';
            $data['result'] = $result;
            $data['url_post'] = '/create-session';
            $html = view('user/components/form_create_session', $data)->render();
        }

        if ($request->to == 'create-sesi') {
            $result = Sesi::where('sesi_tahun', session('session_tahun'))->get();
            $data['session'] = 'sesi';
            $data['result'] = $result;
            $data['url_post'] = '/create-session';
            $html = view('user/components/form_create_session', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_sesi(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['url_view'] = $parameter['url'];
            $data['url_post'] = '/create-sesi';
            $html = view('admin/components/form_create_sesi', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Sesi::where('sesi_id',  $parameter['id'])->first();
            $data['url_view'] = $parameter['url'];
            $data['url_post'] = '/update-sesi';
            $html = view('admin/components/form_update_sesi', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_admin(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';
        
        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();

        $data = [
            'title' => $parameter['title'],
            'instansi' => $instansi
        ];
        
        if ($parameter['to'] == 'create') {
            $data['url'] = $parameter['url'];
            $data['url_post'] = '/create-admin';
            $html = view('admin/components/form_create_admin', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Admin::where('admin_id',  $parameter['id'])->first();
            $data['url'] = $parameter['url'];
            $data['url_post'] = '/update-admin';
            $html = view('admin/components/form_update_admin', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_nomenklatur(Request $request)
    {
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';
        
        $data = [
            'title' => $request->title,
        ];

        
        if ($request->to == 'create') {
            $data['url_post'] = '/create-nomenklatur';
            $data['url_view'] = '/view-nomenklatur-perencanaan';
            $html = view('admin/components/form_create_nomenklatur', $data)->render();
        }

        if ($request->to == 'update') {
            $id = $request->id;
            $result = NomenklaturPerencanaan::where('nomenklatur_id', $id)->first();
            $data['result'] = $result;
            $data['url_post'] = '/update-nomenklatur';
            $data['url_view'] = '/view-nomenklatur-perencanaan';
            $html = view('admin/components/form_update_nomenklatur', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_renja_indikator(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'kode' => $parameter['kode']
        ];

        if ($parameter['to'] == 'create') {
            $data['nomenklatur'] = NomenklaturPerencanaan::where('nomenklatur_id', $parameter['id'])->first();
            $data['url_post'] = '/create-keluaran';
            $html = view('user/components/form_create_keluaran', $data)->render();
        }

        if ($parameter['to'] == 'update') {
            $data['id'] = $parameter['id'];
            $data['keluaran'] = Keluaran::where('keluaran_id', $parameter['id'])->first();
            $data['nomenklatur'] = NomenklaturPerencanaan::where('nomenklatur_kode', $data['keluaran']->keluaran_subkegiatan_kode)->first();
            $data['url_post'] = '/update-keluaran';
            $html = view('user/components/form_update_keluaran', $data)->render();
        }

        // if ($parameter['to'] == 'update' && $parameter['id']) {
        //     $data['result'] = Admin::where('admin_id',  $parameter['id'])->first();
        //     $data['url_view'] = $parameter['url'];
        //     $data['url_post'] = '/update-admin';
        //     $html = view('admin/components/form_update_admin', $data)->render();
        // }

        echo json_encode(['html' => $html]);

    }

    public function form_rencana_keluaran(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'bulan' => $this->bulan
        ];

        if ($parameter['to'] == 'create') {
            $instansi_kode = auth()->user()->admin_kode;
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $parameter['id'])->first();
            $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', 'LIRS6N')
            ->where('keluaran_kode', 1)->first();

            $data['nomenklatur'] = $nomenklatur;
            $data['keluaran_kepmen'] = $keluaran_kepmen;
            $data['url_post'] = '/create-rencana-keluaran';          
            $html = view('user/components/form_create_rencana_keluaran', $data)->render();
        }

        if ($parameter['to'] == 'update') {
            $data['id'] = $parameter['id'];
            $rencana_keluaran = Keluaran::where('keluaran_id', $parameter['id'])->first();
            $data['keluaran_kepmen'] = $rencana_keluaran;
            // $data['keluaran_kepmen'] = Keluaran::where('keluaran_id', $rencana_keluaran->rencana_keluaran_keluaran_id)->first();
            $data['nomenklatur'] = NomenklaturPerencanaan::where('nomenklatur_kode', $rencana_keluaran->keluaran_subkegiatan_kode)->first();
            $data['url_post'] = '/update-rencana-keluaran';
            $html = view('user/components/form_update_rencana_keluaran', $data)->render();

            // $html =  $data['nomenklatur'];
        }

        echo json_encode(['html' => $html]);

    }

    public function form_ropk_fisik(Request $request)
    {
        $parameter = $request->parameter;
        $instansi_kode = session('session_instansi');
        $sesi_kode = session('session_kode')->sesi_kode;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'url_view' => '/view-ropk-fisik-detail',
            'bulan' => $this->bulan
        ];

        if ($parameter['to'] == 'create') {
            
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $parameter['id'])->first();
            $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', $sesi_kode)
            ->where('keluaran_jenis', 0)
            ->where('keluaran_tipe', 1)->first();

            $data['nomenklatur'] = $nomenklatur;
            $data['keluaran_kepmen'] = $keluaran_kepmen;
            $data['url_post'] = '/create-ropk-fisik';          
            $html = view('user/components/form_create_ropk_fisik', $data)->render();
        }

        if ($parameter['to'] == 'update') {
            $data['id'] = $parameter['id'];
            $ropk_fisik = Fisik::where('fisik_id', $parameter['id'])->first();
            $data['ropk_fisik'] = $ropk_fisik;
            $keluaran_kepmen = Keluaran::where('keluaran_subkegiatan_kode', $ropk_fisik->fisik_subkegiatan_kode)
            ->where('keluaran_instansi_kode', $instansi_kode)
            ->where('keluaran_sesi_kode', $sesi_kode)
            ->where('keluaran_jenis', 0)
            ->where('keluaran_tipe', 1)->first();

            $data['keluaran_kepmen'] = $keluaran_kepmen;
            $data['nomenklatur'] = NomenklaturPerencanaan::where('nomenklatur_kode', $ropk_fisik->fisik_subkegiatan_kode)->first();
            $data['url_post'] = '/update-ropk-fisik';
            $html = view('user/components/form_update_ropk_fisik', $data)->render();

        }

        echo json_encode(['html' => $html]);

    }

    public function form_realisasi_keluaran(Request $request)
    {
        $html = '<div class="text-center text-gray-400 p-4">Tidak Ditemukan</div>';

        $data = [
            'title' => $request->title,
            'bulan' => $request->bulan
        ];

        if ($request->to == 'view') {
            $data['result'] = LampiranKeluaran::where('lampiran_id',  $request->id)->first();
            $data['path'] = 'keluaran/';
            $html = view('user/components/view_dokumen', $data)->render();
        }

        if ($request->to == 'update') {
            try {
                $keluaran = Keluaran::with('lampiran_keluaran')->where('keluaran_kode', $request->id)->get();

                $keluaran_target = $keluaran->first(function ($item) {
                    return $item->keluaran_jenis == 0;
                });

                $keluaran_realisasi = $keluaran->first(function ($item) {
                    return $item->keluaran_jenis == 1;
                });

                if (!$keluaran_realisasi) {
                    $input = [
                        'keluaran_uid' => $keluaran_target->keluaran_tahun.$keluaran_target->keluaran_sesi_kode.'1'.$keluaran_target->keluaran_instansi_kode.str_replace('.', '', $keluaran_target->keluaran_subkegiatan_kode).'-'.$keluaran_target->keluaran_kode,
                        'keluaran_tipe' => 1,
                        'keluaran_kode' => $keluaran_target->keluaran_kode,
                        'keluaran_instansi_kode' => $keluaran_target->keluaran_instansi_kode,
                        'keluaran_subkegiatan_kode' => $keluaran_target->keluaran_subkegiatan_kode,
                        'keluaran_nama' => $keluaran_target->keluaran_nama,
                        'keluaran_satuan' => $keluaran_target->keluaran_satuan,
                        'keluaran_target' => $keluaran_target->keluaran_target,
                        'keluaran_tahun' => $keluaran_target->keluaran_tahun,
                        'keluaran_sesi_kode' => $keluaran_target->keluaran_sesi_kode,
                        'keluaran_jenis' => 1
                    ];
                    $result = Keluaran::create($input);
                    $keluaran_realisasi = Keluaran::find($result->keluaran_id);
                }

                if ($keluaran_realisasi->lampiran_keluaran) {
                    $lampiran_keluaran = $keluaran_realisasi->lampiran_keluaran;
                } else {
                    $input = [
                        'lampiran_kode' => $keluaran_realisasi->keluaran_uid,
                        'lampiran_tahun' => $keluaran_target->keluaran_tahun,
                        'lampiran_sesi_kode' => $keluaran_target->keluaran_sesi_kode,
                    ];
                    $result = LampiranKeluaran::create($input);
                    $lampiran_keluaran = LampiranKeluaran::find($result->lampiran_id);
                }

                $data['bulan_ref'] = $this->bulan;
                $data['realisasi_keluaran'] = $keluaran_realisasi;
                $data['lampiran_keluaran'] = $lampiran_keluaran;
                $data['url_post'] = '/update-realisasi-keluaran';
                $html = view('user/components/form_update_realisasi_keluaran', $data)->render();
                
            } catch (\Throwable $th) {
                $html = $html;
            }
            
        }

        if ($request->to == 'delete-attachment') {
            $result = LampiranKeluaran::where('lampiran_id',  $request->id)->first();
            if ($result->{'lampiran_'.$request->bulan} != NULL && file_exists(public_path('/assets/img/keluaran/'.$result->{'lampiran_'.$request->bulan}['filename']))) {
                File::delete(public_path('/assets/img/keluaran/' . $result->{'lampiran_'.$request->bulan}['filename']));
                $result->update([
                    'lampiran_' . $request->bulan => NULL
                ]);
            }
            $html = view('user/components/input_lampiran', $data)->render();
        }

        if ($request->to == 'delete-link') {
            $result = LampiranKeluaran::where('lampiran_id',  $request->id)->first();
           
            if ($result) {
                $result->update([
                    'lampiran_' . $request->bulan => NULL
                ]);
            }
            $html = view('user/components/input_lampiran', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_realisasi_fisik(Request $request)
    {
        $html = '<div class="text-center text-gray-400 p-4">Tidak Ditemukan</div>';

        $data = [
            'title' => $request->title,
            'bulan' => $request->bulan
        ];

        if ($request->to == 'view') {
            try {
                $data['result'] = LampiranFisik::where('lampiran_id',  $request->id)->first();
                $data['path'] = 'fisik/';
                $html = view('user/components/view_dokumen', $data)->render();
            } catch (\Throwable $th) {
                $html = $html;
            }
        }

        if ($request->to == 'update') {
            try {
                $fisik = Fisik::with('lampiran_fisik')->where('fisik_kode', $request->id)->get();

                $fisik_target = $fisik->first(function ($item) {
                    return $item->fisik_jenis == 0;
                });

                $fisik_realisasi = $fisik->first(function ($item) {
                    return $item->fisik_jenis == 1;
                });

                if (!$fisik_realisasi) {
                    $input = [
                        'fisik_uid' => $fisik_target->fisik_tahun.$fisik_target->fisik_sesi_kode.'1'.str_replace('.', '', $fisik_target->fisik_subkegiatan_kode).'-'.$fisik_target->fisik_kode,
                        'fisik_tipe' => 1,
                        'fisik_kode' => $fisik_target->fisik_kode,
                        'fisik_instansi_kode' => $fisik_target->fisik_instansi_kode,
                        'fisik_subkegiatan_kode' => $fisik_target->fisik_subkegiatan_kode,
                        'fisik_nama' => $fisik_target->fisik_nama,
                        'fisik_satuan' => $fisik_target->fisik_satuan,
                        'fisik_target' => $fisik_target->fisik_target,
                        'fisik_tahun' => $fisik_target->fisik_tahun,
                        'fisik_sesi_kode' => $fisik_target->fisik_sesi_kode,
                        'fisik_jenis' => 1,
                        'fisik_tahapan' => $fisik_target->fisik_tahapan,
                        'fisik_nomor' => $fisik_target->fisik_nomor,
                        'fisik_aktivitas' => $fisik_target->fisik_aktivitas,
                        'fisik_acuan' => $fisik_target->fisik_acuan
                    ];
                    $result = Fisik::create($input);
                    $fisik_realisasi = Fisik::find($result->fisik_id);
                }

                if ($fisik_realisasi->lampiran_fisik) {
                    $lampiran_fisik = $fisik_realisasi->lampiran_fisik;
                } else {
                    $input = [
                        'lampiran_kode' => $fisik_realisasi->fisik_uid,
                        'lampiran_tahun' => $fisik_target->fisik_tahun,
                        'lampiran_sesi_kode' => $fisik_target->fisik_sesi_kode,
                    ];
                    $result = LampiranFisik::create($input);
                    $lampiran_fisik = LampiranFisik::find($result->lampiran_id);
                }

                $data['bulan_ref'] = $this->bulan;
                $data['realisasi_fisik'] = $fisik_realisasi;
                $data['lampiran_fisik'] = $lampiran_fisik;
                $data['url_post'] = '/update-realisasi-fisik';
                $html = view('user/components/form_update_realisasi_fisik', $data)->render();
                
            } catch (\Throwable $th) {
                $html = $html;
            }
            
        }

        if ($request->to == 'delete-attachment') {
            $result = LampiranFisik::where('lampiran_id',  $request->id)->first();
            if ($result->{'lampiran_'.$request->bulan} != NULL && file_exists(public_path('/assets/img/fisik/'.$result->{'lampiran_'.$request->bulan}['filename']))) {
                File::delete(public_path('/assets/img/fisik/' . $result->{'lampiran_'.$request->bulan}['filename']));
                $result->update([
                    'lampiran_' . $request->bulan => NULL
                ]);
            }
            $html = view('user/components/input_lampiran', $data)->render();
        }

        if ($request->to == 'delete-link') {
            $result = LampiranFisik::where('lampiran_id',  $request->id)->first();
           
            if ($result) {
                $result->update([
                    'lampiran_' . $request->bulan => NULL
                ]);
            }
            $html = view('user/components/input_lampiran', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_permasalahan(Request $request)
    {
        $html = '<div class="text-center text-gray-400 p-4">Tidak Ditemukan</div>';

        $data = [
            'title' => $request->title,
            'bulan' => $request->bulan
        ];

        if ($request->to == 'create') {
            $instansi_kode = session('session_instansi');
            $sesi_kode = session('session_kode')->sesi_kode;
            $tahun = session('session_tahun');
            $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $request->id)->first();

            $data['instansi_kode'] = $instansi_kode;
            $data['nomenklatur'] = $nomenklatur;
            $data['tahun'] = $tahun;
            $data['sesi_kode'] = $sesi_kode;
        
            $data['url_post'] = '/create-permasalahan';          
            $html = view('user/components/form_create_permasalahan', $data)->render();
        }

        if ($request->to == 'update') {
            $permasalahan = Permasalahan::where('permasalahan_id', $request->id)->first();

            $data = [
                'title' => $request->title,
                'url_post' => '/update-permasalahan',
                'permasalahan' => $permasalahan
            ];

            $html = view('user/components/form_update_permasalahan', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function input_attachment(Request $request)
    {
        $html = '<div class="text-center text-gray-400 p-4 text-small border rounded">Tidak Ditemukan</div>';
        $tipe = $request->tipe;
        
        if ($tipe) {
            $data = [
                'tipe' => $tipe
            ];
            $html = view('user/components/input_attachment', $data)->render();
        }
        
        // $data = [
        //     'title' => $request->title,
        //     'bulan' => $this->bulan
        // ];

        // if ($request->to == 'create') {
        //     $fisik = Fisik::where('fisik_kode', $request->id)->where('fisik_jenis', 0)->first();
        //     $data['rencana_fisik'] = $fisik;
        //     $data['url_post'] = '/create-realisasi-fisik';          
        //     $html = view('user/components/form_create_realisasi_fisik', $data)->render();
        // }

        // if ($request->to == 'update') {
        //     $data['id'] = $parameter['id'];
        //     $ropk_fisik = Fisik::where('fisik_id', $parameter['id'])->first();
        //     $data['ropk_fisik'] = $ropk_fisik;
        //     $data['keluaran_kepmen'] = Keluaran::where('keluaran_id', $ropk_fisik->fisik_keluaran_id)->first();
        //     $data['nomenklatur'] = NomenklaturPerencanaan::where('nomenklatur_kode', $data['keluaran_kepmen']->keluaran_subkegiatan_kode)->first();
        //     $data['url_post'] = '/update-ropk-fisik';
        //     $html = view('user/components/form_update_ropk_fisik', $data)->render();

        //     // $html =  $data['nomenklatur'];
        // }

        echo json_encode(['html' => $html]);

    }

    public function form_penilaian_prencanaan(Request $request)
    {
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $request->title
        ];

        if ($request->to == 'create') {
            $data['bulan_nama'] = $this->bulan[$request->bulan];
            $data['bulan_index'] = $request->bulan;
            $data['tahun'] = session('session_tahun');
            $data['url_post'] = '/create-penilaian-perencanaan';          
            $html = view('admin/components/form_create_penilaian_perencanaan', $data)->render();
        }

        if ($request->to == 'update') {
            $result = PenilaianRencana::where('penilaian_rencana_id', $request->id)->first();
            $data['penilaian_perencanaan'] = $result;
            $data['bulan_nama'] = $this->bulan[$result->penilaian_rencana_bulan];
            $data['url_post'] = '/update-penilaian-perencanaan';          
            $html = view('admin/components/form_update_penilaian_perencanaan', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_penilaian_prencanaan_opd(Request $request)
    {
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $request->title
        ];

        if ($request->to == 'create') {
            $data['penilaian_rencana_id'] = $request->id;
            $data['instansi_kode'] = $request->kode;
            $data['url_post'] = '/create-penilaian-perencanaan-opd';          
            $html = view('admin/components/form_create_penilaian_perencanaan_opd', $data)->render();
        }

        if ($request->to == 'view') {
            $data['result'] = PenilaianRencanaItem::where('penilaian_rencana_item_id',  $request->id)->first();
            $data['filename'] = $data['result']->penilaian_rencana_item_lampiran;
            $data['path'] = 'penilaian_rencana/';
            $html = view('admin/components/view_dokumen', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_keuangan_admin(Request $request)
    {
        $html = '<div class="text-center text-gray-400 p-4">Tidak Ditemukan</div>';

        $data = [
            'title' => $request->title,
        ];

        if ($request->to == 'import') {
            $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get();
            $data['url_post'] = '/import-keuangan';
            $data['url_view'] = '/view-anggaran-kas';
            $data['sesi'] = $sesi; 
            $html = view('admin/components/form_import_keuangan', $data)->render();
        }

        if ($request->to == 'copy') {
            $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get();
            $data['url_post'] = '/salin-keuangan';
            $data['url_view'] = '/view-anggaran-kas';
            $data['sesi'] = $sesi; 
            $html = view('admin/components/form_salin_keuangan', $data)->render();
        }

        if ($request->to == 'delete') {
            $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get();
            $data['url_post'] = '/hapus-keuangan';
            $data['url_view'] = '/view-anggaran-kas';
            $data['sesi'] = $sesi; 
            $html = view('admin/components/form_hapus_keuangan', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_fisik_admin(Request $request)
    {
        $html = '<div class="text-center text-gray-400 p-4">Tidak Ditemukan</div>';

        $data = [
            'title' => $request->title ? $request->title : '',
        ];

        if ($request->to == 'input') {
            $subkegiatan = Fisik::with('nomenklatur')->where('fisik_instansi_kode', $request->id)->where('fisik_sesi_kode', session('session_kode')->sesi_kode)
                ->select('fisik_subkegiatan_kode')
                ->distinct()
                ->get();
                // ->pluck('fisik_subkegiatan_kode')
                // ->toArray();
            // $data['url_post'] = '/view-fisik-admin';
            $data['url_view'] = '/view-fisik-detail-admin';
            $data['subkegiatan'] = $subkegiatan;
            $html = view('admin/components/input_subkegiatan_fisik', $data)->render();
        }

        // if ($request->to == 'import') {
        //     $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get();
        //     $data['url_post'] = '/import-keuangan';
        //     $data['url_view'] = '/view-anggaran-kas';
        //     $data['sesi'] = $sesi; 
        //     $html = view('admin/components/form_import_keuangan', $data)->render();
        // }

        // if ($request->to == 'copy') {
        //     $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get();
        //     $data['url_post'] = '/salin-keuangan';
        //     $data['url_view'] = '/view-anggaran-kas';
        //     $data['sesi'] = $sesi; 
        //     $html = view('admin/components/form_salin_keuangan', $data)->render();
        // }

        // if ($request->to == 'delete') {
        //     $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get();
        //     $data['url_post'] = '/hapus-keuangan';
        //     $data['url_view'] = '/view-anggaran-kas';
        //     $data['sesi'] = $sesi; 
        //     $html = view('admin/components/form_hapus_keuangan', $data)->render();
        // }

        echo json_encode(['html' => $html]);

    }

    public function input_pelaporan_bulan(Request $request)
    {
        $html = '';
        $jenis = $request->jenis;
        $bulan = $this->bulan;

        if ($jenis == 4) {
            $tw = [
                '1' => [
                    'id' => 1,
                    'nama' => 'TRIWULAN I',
                    'bulan' => [1,2,3],
                    'tw_kumulatif' => [1],
                    'bulan_kumulatif' => [1,2,3]
                ],
                '2' => [
                    'id' => 2,
                    'nama' => 'TRIWULAN II',
                    'bulan' => [4,5,6],
                    'tw_kumulatif' => [1,2],
                    'bulan_kumulatif' => [1,2,3,4,5,6]
                ],
                '3' => [
                    'id' => 3,
                    'nama' => 'TRIWULAN III',
                    'bulan' => [7,8,9],
                    'tw_kumulatif' => [1,2,3],
                    'bulan_kumulatif' => [1,2,3,4,5,6,7,8,9]
                ],
                '4' => [
                    'id' => 4,
                    'nama' => 'TRIWULAN IV',
                    'bulan' => [10,11,12],
                    'tw_kumulatif' => [1,2,3,4],
                    'bulan_kumulatif' => [1,2,3,4,5,6,7,8,9,10,11,12]
                ],
            ];

            $data = [
                'triwulan' => $tw
            ];

            $html = view('user/components/input_pelaporan_triwulan', $data)->render();
        }
        
        if ($jenis == 1 || $jenis == 3) {
            $data = [
                'bulan' => $bulan
            ];
            $html = view('user/components/input_pelaporan_bulan', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }









    public function form_user(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['url'] = $parameter['url'];
            $data['url_post'] = '/create-user';
            $html = view('user/components/form_create_user', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Warga::where('warga_id',  $parameter['id'])->first();
            $data['url_post'] = '/update-user';
            $html = view('user/components/form_update_user', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_penyewa(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'url' => $parameter['url'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['warga_id'] = $parameter['id'];
            $data['url_post'] = '/create-penyewa';
            $html = view('user/components/form_create_penyewa', $data)->render();
        }

        if ($parameter['to'] == 'view') {
            $data['result'] = Penyewa::where('penyewa_id',  $parameter['id'])->first();
            $html = view('user/components/view_dokumen_penyewa', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Penyewa::where('penyewa_id',  $parameter['id'])->first();
            $data['url_post'] = '/update-penyewa';
            $html = view('user/components/form_update_penyewa', $data)->render();
        }

        if ($parameter['to'] == 'delete-attachment' && $parameter['id']) {
            $result = Penyewa::where('penyewa_id',  $parameter['id'])->first();
            if ($result->penyewa_dokumen != '' && file_exists(public_path('/assets/img/penyewa/'.$result->penyewa_dokumen))) {
                File::delete(public_path('/assets/img/penyewa/' . $result->penyewa_dokumen));
                $result->update(['penyewa_dokumen' => '']);
            }
            $html = view('iuran/components/form_pembayaran_dokumen', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_penghuni(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'url' => $parameter['url'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['result'] = Penyewa::where('penyewa_id',  $parameter['id'])->first();
            $data['url_post'] = '/create-penghuni';
            $html = view('user/components/form_create_penghuni', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Penghuni::where('penghuni_id',  $parameter['id'])->first();
            $data['url_post'] = '/update-penghuni';
            $html = view('user/components/form_update_penghuni', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_iuran(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'url' => $parameter['url'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['url_post'] = '/create-iuran';
            $html = view('iuran/components/form_create_iuran', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Iuran::where('iuran_id',  $parameter['id'])->first();
            $data['url_post'] = '/update-iuran';
            $html = view('iuran/components/form_update_iuran', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_partisipan(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['url'] = $parameter['url'];
            $data['iuran_id'] = $parameter['id'];
            $data['url_post'] = '/create-partisipan';
            $html = view('iuran/components/form_create_partisipan', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Partisipan::where('partisipan_id',  $parameter['id'])->first();
            $data['url_post'] = '/update-partisipan';
            $html = view('iuran/components/form_update_partisipan', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_partisipan_kategori(Request $request)
    {
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';
        if ($request->id) {
            $data['id'] = $request->id;
            if ($request->id == 1) {
                $warga = Warga::inRandomOrder()->get();
            } else {
                $warga = [];
            }
            $data['warga'] = $warga;
            $html = view('iuran/components/form_partisipan_kategori', $data)->render();
        }
        
        echo json_encode(['html' => $html]);

    }

    public function form_partisipan_warga(Request $request)
    {
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';
        if ($request->id) {
            $warga = Warga::where('warga_id', $request->id)->first();
            if ($warga) {
                echo json_encode(['status' => true, 'data' => $warga]);
            } else {
                echo json_encode(['status' => false]);
            }  
        }

    }

    public function form_partisipan_pembayaran(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
            'url' => $parameter['url'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['partisipan_id'] = $parameter['id'];
            $data['partisipan'] = Partisipan::with('iuran')->where('partisipan_id',  $parameter['id'])->first();
            $data['url_post'] = '/create-partisipan-pembayaran';
            $html = view('iuran/components/form_create_partisipan_pembayaran', $data)->render();
        }

        if ($parameter['to'] == 'view') {
            $data['result'] = Pembayaran::where('pembayaran_id',  $parameter['id'])->first();
            $html = view('iuran/components/view_dokumen_pembayaran', $data)->render();
        }

        if ($parameter['to'] == 'update' && $parameter['id']) {
            $data['result'] = Pembayaran::where('pembayaran_id',  $parameter['id'])->first();
            $data['url_post'] = '/update-partisipan-pembayaran';
            $html = view('iuran/components/form_update_partisipan_pembayaran', $data)->render();
        }

        if ($parameter['to'] == 'delete-attachment' && $parameter['id']) {
            $result = Pembayaran::where('pembayaran_id',  $parameter['id'])->first();
            if ($result->pembayaran_dokumen != '' && file_exists(public_path('/assets/img/pembayaran/'.$result->pembayaran_dokumen))) {
                File::delete(public_path('/assets/img/pembayaran/' . $result->pembayaran_dokumen));
                $result->update(['pembayaran_dokumen' => '']);
            }
            $html = view('iuran/components/form_pembayaran_dokumen', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }

    public function form_partisipan_dokumen(Request $request)
    {
        $parameter = $request->parameter;
        $html = '<div><div class="text-center text-gray-400 p-4">Tidak Ditemukan</div><button type="button" class="btn btn-block btn-gray-300 px-3 rounded" class="close" data-dismiss="modal">Tutup</button></div>';

        $data = [
            'title' => $parameter['title'],
        ];
        
        if ($parameter['to'] == 'create') {
            $data['partisipan_id'] = $parameter['id'];
            $data['partisipan'] = Partisipan::with('iuran')->where('partisipan_id',  $parameter['id'])->first();
            $data['url_post'] = '/create-partisipan-dokumen';
            $html = view('iuran/components/form_create_partisipan_dokumen', $data)->render();
        }

        if ($parameter['to'] == 'view') {
            $data['result'] = Dokumen::where('dokumen_id',  $parameter['id'])->first();
            $html = view('iuran/components/view_partisipan_dokumen', $data)->render();
        }

        echo json_encode(['html' => $html]);

    }











}
