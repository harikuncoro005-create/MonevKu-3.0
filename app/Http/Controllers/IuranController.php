<?php

namespace App\Http\Controllers;

use App\Models\Bulan;
use App\Models\Iuran;
use App\Models\Partisipan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class IuranController extends Controller
{
    public function iuran()
    {
        return view('iuran.iuran', [
            'titlePage' => 'Iuran',
            'url' => '/view-iuran',
            'url_form' => '/form-iuran'
        ]);
    }

    public function partisipan(Request $request)
    {
        if ($request->get('id')) {
            $iuran = Iuran::with('pembayaran')->where('iuran_id',  $request->get('id'))->first();
            if ($iuran) {
                $pembayaran = $iuran->pembayaran;
                $total = str_replace(',','.', number_format(collect($pembayaran)->sum('pembayaran_jumlah')));
                return view('iuran.partisipan', [
                    'titlePage' => 'Partisipan',
                    'url' => '/view-partisipan',
                    'url_form' => '/form-partisipan',
                    'data' => $iuran,
                    'total' => $total
                ]);
            } else {
                return redirect('/iuran');
            }
        } else {
            return redirect('/iuran');
        }
    }

    public function detail(Request $request)
    {
        if ($request->get('id')) {
            $kategori = [
                '1' => ['nama' => 'Pemilik', 'color' => '#34D399'],
                '2' => ['nama' => 'Penyewa', 'color' => '#A78BFA'],
                '3' => ['nama' => 'Umum', 'color' => '#F472B6'],
            ];

            $partisipan = Partisipan::with('iuran','dokumen')->where('partisipan_id',  $request->get('id'))->first();
            if ($partisipan) {
                return view('iuran.detail', [
                    'titlePage' => 'Detail',
                    'url' => '/view-pembayaran',
                    'url_form' => '/form-partisipan-pembayaran',
                    'kategori' => $kategori,
                    'data' => $partisipan
                ]);
            } else {
                abort(404);
            }
        } else {
            return redirect('/iuran');
        }
        
    }

    public function rekap(Request $request)
    {
        if ($request->get('id')) {
            $iuran = Iuran::where('iuran_id',  $request->get('id'))->first();
            if ($iuran) {
                $tahun = [];

                for ($i=2025; $i <= (date('Y') + 1) ; $i++) { 
                    $tahun[] = $i;
                }

                $bulan = Bulan::orderBy('bulan_id','asc')->get();

                return view('iuran.rekap', [
                    'titlePage' => 'Rekap Pembayaran',
                    'url' => '/view-rekap-pembayaran',
                    'data' => $iuran,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                ]);
            } else {
                return redirect('/iuran/partisipan?id='.$request->get('id'));
            }
        } else {
            return redirect('/iuran/partisipan?id='.$request->get('id'));
        }
    }




}
