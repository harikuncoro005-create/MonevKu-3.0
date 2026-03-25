<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\PenilaianRencana;
use App\Models\RefKode;
use App\Models\Sesi;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
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

    public function panel()
    {
        return view('admin.panel', [
            'titlePage' => 'Admin Panel',
        ]);
    }

    public function pengaturan()
    {
        return view('admin.pengaturan', [
            'titlePage' => 'Pengaturan',
        ]);
    }

    public function nomenklatur_perencanaan()
    {
        $ref_kode = RefKode::all();
        return view('admin.nomenklatur_perencanaan', [
            'titlePage' => 'Nomenklatur Perencanaan',
            'url_form' => '/form-nomenklatur',
            'url_view' => '/view-nomenklatur-perencanaan',
            'ref_kode' => $ref_kode
        ]);
    }

    public function anggaran_kas()
    {
        $instansi = Instansi::where('kode', 1)->get();
        $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get(); 
        return view('admin.anggaran_kas', [
            'titlePage' => 'Keuangan',
            'url_view' => '/view-anggaran-kas',
            'url_form' => '/form-keuangan-admin',
            'instansi' => $instansi,
            'sesi' => $sesi,
            'bulan' => $this->bulan
        ]);
    }

    public function anggaran_kas_pd()
    {
        $instansi = Instansi::where('kode', 1)->orderBy('instansi_urut', 'desc')->get(); 
        return view('admin.anggaran_kas_pd', [
            'titlePage' => 'Anggaran KAS',
            'url_view' => '/view-anggaran-kas-pd',
            'instansi' => $instansi
        ]);
    }

    public function fisik()
    {
        $instansi = Instansi::where('kode', 1)->orderBy('instansi_urut', 'desc')->get();
        $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get(); 
        return view('admin.fisik', [
            'titlePage' => 'Fisik',
            'url_view' => '/view-fisik-admin',
            'url_form' => '/form-fisik-admin',
            'instansi' => $instansi,
            'sesi' => $sesi,
            'bulan' => $this->bulan
        ]);
    }

    public function fisik_detail()
    {
        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();
        $sesi = Sesi::where('sesi_tahun', session('session_tahun'))->get(); 
        return view('admin.fisik_detail', [
            'titlePage' => 'Fisik',
            'url_view' => '/view-fisik-admin',
            'url_form' => '/form-fisik-admin',
            'instansi' => $instansi,
            'sesi' => $sesi,
            'bulan' => $this->bulan
        ]);
    }

    public function sesi()
    {
        return view('admin.sesi', [
            'titlePage' => 'Sesi',
            'url_view' => '/view-sesi',
            'url_form' => '/form-sesi'
        ]);
    }

    public function penilaian()
    { 
        return view('admin.penilaian', [
            'titlePage' => 'Penilaian'
        ]);
    }

    public function penilaian_perencanaan()
    { 
        $data = [
            'titlePage' => 'Penilaian Perencanaan',
            'url_form' => '/form-penilaian-perencanaan',
            'bulan' => $this->bulan,
            'tahun' => session('session_tahun')
        ];

        $penilaian_rencana = [];
        $result = PenilaianRencana::with('item')->where('penilaian_rencana_tahun', session('session_tahun'))->get();
        if ($result) {
            $penilaian_rencana = $result->keyBy('penilaian_rencana_bulan');
        }

        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();

        $data['penilaian_rencana'] = $penilaian_rencana;
        $data['instansi'] =  $instansi;

        return view('admin.penilaian_perencanaan', $data);
    }

    public function penilaian_perencanaan_detail(Request $request)
    { 
        $id = $request->get('id');

        if ($id != '') {
            $result = PenilaianRencana::with('item')->where('penilaian_rencana_id', $id)->first();
        } else {
            $result = [];
        }

        if ($result) {
            $data = [
                'titlePage' => 'Penilaian Perencanaan',
                'url_form' => '/form-penilaian-perencanaan-opd',
                'bulan' => $this->bulan
            ];

            $penilaian_perencanaan = [];
            $penilaian_perencanaan_opd = [];

            if ($result) {
                $penilaian_perencanaan = $result;
                if ($result->item) {
                    $penilaian_perencanaan_opd = $result->item->keyBy('penilaian_rencana_item_instansi_kode');
                }
            }

            $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();

            $data['penilaian_perencanaan'] = $penilaian_perencanaan;
            $data['penilaian_perencanaan_opd'] = $penilaian_perencanaan_opd;
            $data['instansi'] =  $instansi;

            return view('admin.penilaian_perencanaan_detail', $data);
        } else {
            return view('404', ['url' => '/panel/penilaian-perencanaan']);
        }
    
    }

    public function penilaian_pelaporan(Request $request)
    { 
        if ($request->get('bulan')) {
            $bulan_index = $request->get('bulan');
        } else {
            $bulan_index = Carbon::createFromFormat('m', date('n'))->subMonth()->format('m');
            // $bulan_index = date('n');
        }
        
        $data = [
            'titlePage' => 'Penilaian Pelaporan',
            'url_view' => '/view-penilaian-pelaporan',
            'url_form' => '/form-penilaian-pelaporan',
            'url_post' => '/upsert-permasalahan',
            'bulan' => $this->bulan,
            'bulan_index' => $bulan_index
        ];

        // $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();
        $otorisasi = auth()->user()->admin_otorisasi;
        $instansi = Instansi::where('kode', 1)->whereIn('instansi_kode', $otorisasi)->get();
        $data['instansi'] =  $instansi;

        return view('admin.penilaian_pelaporan', $data);
    }

    public function penilaian_rekap(Request $request)
    { 
        if ($request->get('bulan')) {
            $bulan_index = $request->get('bulan');
        } else {
            $bulan_index = Carbon::createFromFormat('m', date('m'))->subMonth()->format('m');
        }
        
        $data = [
            'titlePage' => 'Penilaian Rekap',
            'url_view' => '/view-penilaian-rekap',
            'bulan' => $this->bulan,
            'bulan_index' => $bulan_index
        ];

        $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();
        $data['instansi'] =  $instansi;

        return view('admin.penilaian_rekap', $data);
    }

    public function keluaran()
    {
        return view('admin.keluaran', [
            'titlePage' => 'Keluaran',
            'url' => '/view-keluaran-admin',
            'url_form' => '/form-keluaran-admin'
        ]);
    }

    public function admin()
    {
        return view('admin.admin', [
            'titlePage' => 'Admin',
            'url' => '/view-admin',
            'url_form' => '/form-admin'
        ]);
    }

    public function pelaporan()
    {
        $laporan_jenis = [
            '1' => 'Laporan APBD Bulanan'
        ];

        // $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();

        return view('admin.pelaporan', [
            'titlePage' => 'Pelaporan',
            'bulan' => $this->bulan,
            'tahun' => session('session_tahun'),
            // 'instansi' => $instansi,
            'laporan_jenis' => $laporan_jenis,
            'url_view' => '/view-pelaporan-daerah',
        ]);
    }

    public function permission()
    {
        return view('admin.permission', [
            'titlePage' => 'Permission',
            'url_view' => '/view-permission',
            'url_post' => '/upsert-permission',
            'tahun' => session('session_tahun')
        ]);
    }

    
}
