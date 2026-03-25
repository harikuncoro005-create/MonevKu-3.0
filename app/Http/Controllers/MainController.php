<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Iuran;

class MainController extends Controller
{
    public function index()
    {
        // $tahun = [];
        // $iuran = [];

        // for ($i=2025; $i <= (date('Y') + 1) ; $i++) { 
		// 	$tahun[] = $i;
		// }

        // $result = Iuran::where('iuran_prioritas', 1)->get();

        // if ($result) {
        //     $iuran = $result;
        //     $iuran_prioritas = collect($result)->where('iuran_prioritas', 1)->first();
        // }
        
        return view('main.index', [
            'titlePage' => 'Monev PRO',
            // 'url' => '/view-diagram-main',
            // 'tahun' => $tahun,
            // 'iuran' => $iuran,
            // 'iuran_prioritas' => $iuran_prioritas
        ]);
    }

    public function login()
    {
        return view('main.login', [
            'titlePage' => 'Login Admin',
        ]);
    }

    public function sumbangan()
    {
        return view('main.sumbangan', [
            'titlePage' => 'Sumbangan',
        ]);
    }

    public function proposal()
    {
        return view('main.proposal', [
            'titlePage' => 'Proposal',
        ]);
    }
}
