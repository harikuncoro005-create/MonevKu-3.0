<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KeuanganImport;
use App\Models\Instansi;
use App\Models\Keuangan;
use Exception;
use Illuminate\Support\Carbon;

use App\Models\Sesi;

class ImportController extends Controller
{
    public function import_keuangan(Request $request)
    {
        ini_set('memory_limit', '512M');
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'jenis' => 'required',
                    'dokumen' => 'required|file|mimes:xls,xlsx,csv'
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $sesi = Sesi::where('sesi_kode', $request->sesi)->first();
                $instansi_kode = Instansi::where('kode', 1)->pluck('instansi_kode')->toArray();;
                $data = [];

                if ($request->hasFile('dokumen')) {
                    try {
                        $results = Excel::toCollection(new KeuanganImport, $request->file('dokumen'));
                        
                        if (count($results[0])) {
                            foreach ($results[0] as $key => $value) {
                                if (in_array($value['instansi_kode'], $instansi_kode)) {
                                    $collect = [
                                        'keuangan_sesi_kode' => $sesi->sesi_kode,
                                        'keuangan_jenis' => $request->jenis,
                                        'keuangan_kode' => $sesi->sesi_tahun.$sesi->sesi_kode.$request->jenis.$value['instansi_kode'].str_replace('.', '', $value['subkegiatan']),
                                        'keuangan_instansi_kode' => $value['instansi_kode'],
                                        'keuangan_urusan_kode' => $value['urusan'],
                                        'keuangan_bidang_urusan_kode' => $value['bidang_urusan'],
                                        'keuangan_program_kode' => $value['program'],
                                        'keuangan_kegiatan_kode' => $value['kegiatan'],
                                        'keuangan_subkegiatan_kode' => $value['subkegiatan'],
                                        'keuangan_pagu' => $value['pagu'],
                                        'keuangan_1' => $value['januari'],
                                        'keuangan_2' => $value['februari'],
                                        'keuangan_3' => $value['maret'],
                                        'keuangan_4' => $value['april'],
                                        'keuangan_5' => $value['mei'],
                                        'keuangan_6' => $value['juni'],
                                        'keuangan_7' => $value['juli'],
                                        'keuangan_8' => $value['agustus'],
                                        'keuangan_9' => $value['september'],
                                        'keuangan_10' => $value['oktober'],
                                        'keuangan_11' => $value['november'],
                                        'keuangan_12' => $value['desember'],
                                        'keuangan_tahun' => $sesi->sesi_tahun,
                                    ];
                                    $data[] = $collect;
                                } 
                            }
                            

                            // $keuangan = Keuangan::where('keuangan_jenis', $request->jenis)->get();

                            // foreach ($keuangan as $key => $value) {
                            //     Keuangan::where('keuangan_id', $value->keuangan_id)->update([
                            //         'keuangan_kode' => $sesi->sesi_tahun.$sesi->sesi_kode.$value->keuangan_jenis.$value->keuangan_instansi_kode.str_replace('.', '', $value->keuangan_subkegiatan_kode)
                            //     ]);
                            // }

                            // Keuangan::updateOrCreate(
                            //     // PARAMETER 1: Kondisi Pencocokan (WHERE) - 3 Kolom Unik
                            //     [
                            //         'keuangan_instansi_kode'    => $value['instansi_kode'],
                            //         'keuangan_subkegiatan_kode' => $value['subkegiatan'],
                            //         'keuangan_sesi_kode'        => $sesi->sesi_kode,
                            //     ],
                            //     // PARAMETER 2: Data yang akan disimpan/diupdate (Nilai Baru)
                            //     [
                            //         'keuangan_jenis'        => $request->jenis,
                            //         'keuangan_program_kode' => $request->program_kode,
                            //         'keuangan_1'            => $request->nilai_1,
                            //         'keuangan_2'            => $request->nilai_2,
                            //         'keuangan_tahun'        => date('Y'),
                            //         // ... masukkan kolom lain yang ingin diupdate selain 3 kunci di atas
                            //     ]
                            // );

                            $res = Keuangan::upsert(
                                $data,
                                uniqueBy: ['keuangan_kode'],
                                update: [
                                    'keuangan_sesi_kode',
                                    'keuangan_jenis',
                                    'keuangan_instansi_kode',
                                    'keuangan_urusan_kode',
                                    'keuangan_bidang_urusan_kode',
                                    'keuangan_program_kode',
                                    'keuangan_kegiatan_kode',
                                    'keuangan_kegiatan_kode',
                                    'keuangan_subkegiatan_kode',
                                    'keuangan_pagu',
                                    'keuangan_1',
                                    'keuangan_2',
                                    'keuangan_3',
                                    'keuangan_4',
                                    'keuangan_5',
                                    'keuangan_6',
                                    'keuangan_7',
                                    'keuangan_8',
                                    'keuangan_9',
                                    'keuangan_10',
                                    'keuangan_11',
                                    'keuangan_12',
                                    'keuangan_tahun'
                                ]
                            );

                            $html = '<div class="alert-success text-center card-body text-small">
                                        <div>Data Berhasil Diimport</div>
                                    </div>
                                    <br>
                                    <div class="">
                                        <button type="button" class="btn btn-block btn-gray-300 px-3 rounded btn-close">Tutup</button>
                                    </div>';

                            return response()->json(['status' => true, 'html' => $html, 'message' => ['message' => 'Data Berhasil Diimport']]);
                        } else {
                            return response()->json(['status' => false, 'message' => ['message' => 'Data Tidak Ditemukan']]);
                        }

                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => ['message' => 'Error: ' . $e->getMessage()]]);
                    }
                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'File Tidak Ditemukan']]);
                }
                
                // $sheet_name = $results->keys()->toArray()[0];

                // if ($sheet_name == "Import-Keuangan") {
                //     foreach ($results as $key => $value) {
                //         $collect = [
                //             'keuangan_instansi_kode' => $value['instansi_kode']
                //         ];
                //     }
                //     return response()->json(['status' => false, 'message' => $results]);
                // } else {
                //     return response()->json(['status' => false, 'message' => ['message' => 'Format File Tidak Valid']]);
                // }

                
                
                
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function salin_keuangan(Request $request)
    {
        ini_set('memory_limit', '512M');
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'sesi_lama' => 'required',
                    'sesi_baru' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $sesi_lama = Sesi::where('sesi_kode', $request->sesi_lama)->first();
                $sesi_baru = Sesi::where('sesi_kode', $request->sesi_baru)->first();

                $keuangan = Keuangan::where('keuangan_sesi_kode', $sesi_lama->sesi_kode)->get();

                if (count($keuangan) != 0) {

                    $data = $keuangan->map(function ($item) use ($sesi_baru) {
                        $result = $item->toArray();
                        unset($result['keuangan_id']);
                        $result['keuangan_kode'] = $sesi_baru->sesi_tahun.$sesi_baru->sesi_kode.$item->keuangan_jenis.$item->keuangan_instansi_kode.str_replace('.', '', $item->keuangan_subkegiatan_kode);
                        $result['keuangan_sesi_kode'] = $sesi_baru->sesi_kode;
                        $result['keuangan_tahun'] = $sesi_baru->sesi_tahun;
                        return $result;
                    })->toArray();

                    try {
                        Keuangan::upsert(
                            $data,
                            uniqueBy: ['keuangan_kode'],
                            update: [
                                'keuangan_sesi_kode',
                                'keuangan_jenis',
                                'keuangan_instansi_kode',
                                'keuangan_urusan_kode',
                                'keuangan_bidang_urusan_kode',
                                'keuangan_program_kode',
                                'keuangan_kegiatan_kode',
                                'keuangan_kegiatan_kode',
                                'keuangan_subkegiatan_kode',
                                'keuangan_1',
                                'keuangan_2',
                                'keuangan_3',
                                'keuangan_4',
                                'keuangan_5',
                                'keuangan_6',
                                'keuangan_7',
                                'keuangan_8',
                                'keuangan_9',
                                'keuangan_10',
                                'keuangan_11',
                                'keuangan_12',
                                'keuangan_tahun'
                            ]
                        );

                        $html = '<div class="alert-success text-center card-body text-small">
                                            <div>Data Berhasil Disalin</div>
                                        </div>
                                        <br>
                                        <div class="">
                                            <button type="button" class="btn btn-block btn-gray-300 px-3 rounded btn-close">Tutup</button>
                                        </div>';

                        return response()->json(['status' => true, 'html' => $html, 'message' => ['message' => 'Data Berhasil Diimport']]);

                    } catch (\Exception $e) {
                        return response()->json(['status' => false, 'message' => ['message' => 'Error: ' . $e->getMessage()]]);
                    }
                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'Data Tidak Ditemukan']]);
                }

                return response()->json(['status' => false, 'message' => $data]);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function hapus_keuangan(Request $request)
    {
        ini_set('memory_limit', '512M');
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'sesi' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                try {
                    $keuangan = Keuangan::where('keuangan_sesi_kode', $request->sesi)->delete();;

                    $html = '<div class="alert-success text-center card-body text-small">
                                        <div>Data Berhasil Dihapus</div>
                                    </div>
                                    <br>
                                    <div class="">
                                        <button type="button" class="btn btn-block btn-gray-300 px-3 rounded btn-close">Tutup</button>
                                    </div>';

                    return response()->json(['status' => true, 'html' => $html, 'message' => ['message' => 'Data Berhasil Dihapus']]);

                } catch (\Exception $e) {
                    return response()->json(['status' => false, 'message' => ['message' => 'Error: ' . $e->getMessage()]]);
                } 
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }











}
