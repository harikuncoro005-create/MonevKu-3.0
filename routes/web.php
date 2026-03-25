<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\RenderController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\IuranController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\PreventBackHistory;

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return 'DONE';
});

Route::group(['middleware' => ['guest:admin', PreventBackHistory::class]], function () { 
    Route::get('/', [MainController::class, 'login']);
});

// Route::get('/', [MainController::class, 'index']);
Route::get('/sumbangan', [MainController::class, 'sumbangan']);
Route::get('/proposal', [MainController::class, 'proposal']);
Route::post('/view-sumbangan', [RenderController::class, 'view_sumbangan']);
Route::post('/view-diagram-main', [RenderController::class, 'view_diagram_main']);

//AUTH
Route::post('/sign-in', [AuthController::class, 'login']);
Route::get('/sign-out', [AuthController::class, 'logout']);

Route::group(['middleware' => ['auth:admin', PreventBackHistory::class]], function () {
    // DASHBOARD
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/dashboard', [UserController::class, 'dashboard_user']);
    Route::post('/view-dashboard-user', [RenderController::class, 'view_dashboard_user']);
    Route::post('/form-session', [FormController::class, 'form_session']);
    // Route::post('/view-diagram-pembayaran', [RenderController::class, 'view_diagram_pembayaran']);

    // SESI
    Route::get('/sesi', [UserController::class, 'sesi']);

    // RENSTRA
    Route::get('/renstra', [UserController::class, 'renstra']);

    // RENJA
    Route::get('/renja', [UserController::class, 'renja']);
    Route::get('/renja/indikator', [UserController::class, 'renja_indikator']);

    Route::post('/view-renja', [RenderController::class, 'view_renja']);
    Route::post('/form-renja-indikator', [FormController::class, 'form_renja_indikator']);

    // ROPK KELUARAN
    Route::get('/rencana-keluaran', [UserController::class, 'rencana_keluaran']);
    Route::get('/rencana-keluaran/detail', [UserController::class, 'rencana_keluaran_detail']);
    Route::post('/form-rencana-keluaran', [FormController::class, 'form_rencana_keluaran']);
    Route::post('/form-realisasi-keluaran', [FormController::class, 'form_realisasi_keluaran']);

    Route::post('/view-rencana-keluaran', [RenderController::class, 'view_rencana_keluaran']);

    Route::get('/dpa', [UserController::class, 'dpa']);
    Route::get('/dpa/indikator', [UserController::class, 'dpa_indikator']);
    Route::get('/dpa/indikator-detail', [UserController::class, 'dpa_indikator_detail']);

    // ROPK FISIK
    Route::get('/ropk-fisik', [UserController::class, 'ropk_fisik']);
    Route::get('/ropk-fisik/detail', [UserController::class, 'ropk_fisik_detail']);
    Route::post('/form-ropk-fisik', [FormController::class, 'form_ropk_fisik']);
    Route::post('/form-realisasi-fisik', [FormController::class, 'form_realisasi_fisik']);

    Route::post('/view-ropk-fisik', [RenderController::class, 'view_ropk_fisik']);
    Route::post('/view-ropk-fisik-detail', [RenderController::class, 'view_ropk_fisik_detail']);

    Route::get('/ropk-keuangan', [UserController::class, 'ropk_keuangan']);

    Route::post('/view-ropk-keuangan', [RenderController::class, 'view_ropk_keuangan']);


    Route::get('/ropk-belanja', [UserController::class, 'ropk_belanja']);

    //MONEV
    Route::get('/monev', [UserController::class, 'monev']);
    Route::get('/monev/detail', [UserController::class, 'monev_detail']);
    Route::get('/monev/realisasi', [UserController::class, 'monev_realisasi']);

    Route::get('/monev/realisasi-keluaran', [UserController::class, 'monev_realisasi_keluaran']);
    Route::get('/monev/realisasi-fisik', [UserController::class, 'monev_realisasi_fisik']);
    Route::get('/monev/input-permasalahan', [UserController::class, 'monev_input_permasalahan']);

    Route::post('/input-attachment', [FormController::class, 'input_attachment']);

    Route::post('/view-monev', [RenderController::class, 'view_monev']);

    // PERMASALAHAN
    Route::post('/form-permasalahan', [FormController::class, 'form_permasalahan']);

    // PELAPORAN
    Route::get('/pelaporan', [UserController::class, 'pelaporan']);
    Route::post('/view-pelaporan', [RenderController::class, 'view_pelaporan']);
    Route::post('/input-pelaporan-bulan', [FormController::class, 'input_pelaporan_bulan']);


    // ADMIN PANEL
    Route::get('/panel', [AdminController::class, 'panel']);
    Route::get('/panel/anggaran-kas', [AdminController::class, 'anggaran_kas']);
    Route::get('/panel/anggaran-kas-pd', [AdminController::class, 'anggaran_kas_pd']);
    Route::get('/panel/fisik', [AdminController::class, 'fisik']);
    Route::get('/panel/fisik/detail', [AdminController::class, 'fisik_detail']);
    Route::get('/panel/keluaran', [AdminController::class, 'keluaran']);

    Route::post('/view-anggaran-kas', [RenderController::class, 'view_anggaran_kas']);
    Route::post('/view-anggaran-kas-pd', [RenderController::class, 'view_anggaran_kas_pd']);
    Route::post('/form-keuangan-admin', [FormController::class, 'form_keuangan_admin']);

    Route::post('/view-fisik-admin', [RenderController::class, 'view_fisik_admin']);
    Route::post('/form-fisik-admin', [FormController::class, 'form_fisik_admin']);
    Route::post('/view-fisik-detail-admin', [RenderController::class, 'view_fisik_detail_admin']);

    Route::get('/panel/penilaian', [AdminController::class, 'penilaian']);
    Route::get('/panel/penilaian-perencanaan', [AdminController::class, 'penilaian_perencanaan']);
    Route::get('/panel/penilaian-perencanaan/detail', [AdminController::class, 'penilaian_perencanaan_detail']);
    Route::post('/form-penilaian-perencanaan', [FormController::class, 'form_penilaian_prencanaan']);
    Route::post('/form-penilaian-perencanaan-opd', [FormController::class, 'form_penilaian_prencanaan_opd']);
    
    Route::get('/panel/penilaian-pelaporan', [AdminController::class, 'penilaian_pelaporan']);
    Route::post('/view-penilaian-pelaporan', [RenderController::class, 'view_penilaian_pelaporan']);

    Route::get('/panel/penilaian-rekap', [AdminController::class, 'penilaian_rekap']);
    Route::post('/view-penilaian-rekap', [RenderController::class, 'view_penilaian_rekap']);

    Route::get('/panel/pelaporan', [AdminController::class, 'pelaporan']);
    Route::post('/view-pelaporan-daerah', [RenderController::class, 'view_pelaporan_daerah']);

    Route::get('/panel/permission', [AdminController::class, 'permission']);
    Route::post('/view-permission', [RenderController::class, 'view_permission']);

    // PENGATURAN
    Route::get('/pengaturan', [AdminController::class, 'pengaturan']);
    Route::get('/pengaturan/admin', [AdminController::class, 'admin']);
    Route::get('/pengaturan/admin/profile', [UserController::class, 'profile']);
    Route::get('/pengaturan/admin-sesi', [AdminController::class, 'sesi']);
    Route::get('/pengaturan/nomenklatur-perencanaan', [AdminController::class, 'nomenklatur_perencanaan']);

    Route::post('/form-nomenklatur', [FormController::class, 'form_nomenklatur']);
    Route::post('/view-nomenklatur-perencanaan', [RenderController::class, 'view_nomenklatur_perencanaan']);
    Route::post('/form-sesi', [FormController::class, 'form_sesi']);
    Route::post('/view-sesi', [RenderController::class, 'view_sesi']);

    // Route::post('/view-user', [RenderController::class, 'view_user']);
    // Route::post('/form-user', [FormController::class, 'form_user']);

    // USER
    // Route::get('/user', [UserController::class, 'user']);
    // Route::get('/user/detail', [UserController::class, 'detail']);
    // Route::get('/user/penghuni', [UserController::class, 'penghuni']);

    // Route::post('/view-user', [RenderController::class, 'view_user']);
    // Route::post('/view-penyewa', [RenderController::class, 'view_penyewa']);
    // Route::post('/view-penghuni', [RenderController::class, 'view_penghuni']);

    // Route::post('/form-user', [FormController::class, 'form_user']);
    // Route::post('/form-penyewa', [FormController::class, 'form_penyewa']);
    // Route::post('/form-penghuni', [FormController::class, 'form_penghuni']);

    // ADMIN
    // Route::get('/admin', [AdminController::class, 'admin']);
    Route::post('/view-admin', [RenderController::class, 'view_admin']);
    Route::post('/form-admin', [FormController::class, 'form_admin']);

    // IURAN
    // Route::get('/iuran', [IuranController::class, 'iuran']);
    // Route::get('/iuran/partisipan', [IuranController::class, 'partisipan']);
    // Route::get('/iuran/partisipan/detail', [IuranController::class, 'detail']);
    // Route::get('/iuran/rekap', [IuranController::class, 'rekap']);

    // Route::post('/view-iuran', [RenderController::class, 'view_iuran']);
    // Route::post('/view-partisipan', [RenderController::class, 'view_partisipan']);
    // Route::post('/view-pembayaran', [RenderController::class, 'view_pembayaran']);
    // Route::post('/view-rekap-pembayaran', [RenderController::class, 'view_rekap_pembayaran']);

    // Route::post('/form-iuran', [FormController::class, 'form_iuran']);
    // Route::post('/form-partisipan', [FormController::class, 'form_partisipan']);
    // Route::post('/form-partisipan-kategori', [FormController::class, 'form_partisipan_kategori']);
    // Route::post('/form-partisipan-warga', [FormController::class, 'form_partisipan_warga']);
    // Route::post('/form-partisipan-pembayaran', [FormController::class, 'form_partisipan_pembayaran']);
    // Route::post('/form-partisipan-dokumen', [FormController::class, 'form_partisipan_dokumen']);

    Route::get('/admin', [AdminController::class, 'admin']);

    Route::get('/bantuan', [BantuanController::class, 'bantuan']);

    Route::get('/kas', [KasController::class, 'kas']);

    // PROCESS

    // --admin
    Route::post('/create-session', [ProcessController::class, 'create_session']);
    Route::post('/approve-admin', [ProcessController::class, 'approve_admin']);

    Route::post('/create-admin', [ProcessController::class, 'create_admin']);
    Route::post('/update-admin', [ProcessController::class, 'update_admin']);

    Route::post('/create-sesi', [ProcessController::class, 'create_sesi']);
    Route::post('/update-sesi', [ProcessController::class, 'update_sesi']);

    Route::post('/delete-keuangan', [ProcessController::class, 'delete_keuangan']);

    Route::post('/import-keuangan', [ImportController::class, 'import_keuangan']);
    Route::post('/salin-keuangan', [ImportController::class, 'salin_keuangan']);
    Route::post('/hapus-keuangan', [ImportController::class, 'hapus_keuangan']);

    Route::post('/create-nomenklatur', [ProcessController::class, 'create_nomenklatur']);
    Route::post('/update-nomenklatur', [ProcessController::class, 'update_nomenklatur']);
    Route::post('/delete-nomenklatur', [ProcessController::class, 'delete_nomenklatur']);

    // --user
    Route::post('/create-keluaran', [ProcessController::class, 'create_keluaran']);
    Route::post('/update-keluaran', [ProcessController::class, 'update_keluaran']);
    Route::post('/delete-keluaran', [ProcessController::class, 'delete_keluaran']);

    Route::post('/create-rencana-keluaran', [ProcessController::class, 'create_rencana_keluaran']);
    Route::post('/update-rencana-keluaran', [ProcessController::class, 'update_rencana_keluaran']);

    Route::post('/create-ropk-fisik', [ProcessController::class, 'create_ropk_fisik']);
    Route::post('/update-ropk-fisik', [ProcessController::class, 'update_ropk_fisik']);
    Route::post('/delete-ropk-fisik', [ProcessController::class, 'delete_ropk_fisik']);

    Route::post('/create-realisasi-fisik', [ProcessController::class, 'create_realisasi_fisik']);
    Route::post('/update-realisasi-fisik', [ProcessController::class, 'update_realisasi_fisik']);
    Route::post('/delete-attachment-realisasi-fisik', [ProcessController::class, 'delete_attachment_realisasi_fisik']);

    Route::post('/update-realisasi-keluaran', [ProcessController::class, 'update_realisasi_keluaran']);

    Route::post('/create-permasalahan', [ProcessController::class, 'create_permasalahan']);
    Route::post('/update-permasalahan', [ProcessController::class, 'update_permasalahan']);
    Route::post('/upsert-permasalahan', [ProcessController::class, 'upsert_permasalahan']);

    Route::post('/create-penilaian-perencanaan', [ProcessController::class, 'create_penilaian_perencanaan']);
    Route::post('/update-penilaian-perencanaan', [ProcessController::class, 'update_penilaian_perencanaan']);
    Route::post('/delete-penilaian-perencanaan', [ProcessController::class, 'delete_penilaian_perencanaan']);

    Route::post('/create-penilaian-perencanaan-opd', [ProcessController::class, 'create_penilaian_perencanaan_opd']);
    Route::post('/update-penilaian-perencanaan-opd', [ProcessController::class, 'update_penilaian_perencanaan_opd']);
    Route::post('/delete-penilaian-perencanaan-opd', [ProcessController::class, 'delete_penilaian_perencanaan_opd']);

    Route::post('/export-laporan-apbd-bulanan', [ExportController::class, 'laporan_apbd_bulanan']);
    Route::post('/export-laporan-rencana-aksi', [ExportController::class, 'laporan_rencana_aksi']);
    Route::post('/export-laporan-lampiran-kinerja', [ExportController::class, 'laporan_lampiran_kinerja']);

    Route::post('/upsert-permission', [ProcessController::class, 'upsert_permission']);

    
});

