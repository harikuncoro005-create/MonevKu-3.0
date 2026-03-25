<?php

namespace App\Http\Controllers;

use App\Models\Iuran;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.dashboard', [
            'titlePage' => 'Dashboard',
            // 'url' => '/view-diagram-pembayaran',
            // 'tahun' => $tahun,
            // 'iuran' => $iuran,
            // 'iuran_prioritas' => $iuran_prioritas
        ]);
    }
}
