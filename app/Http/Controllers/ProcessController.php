<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Dokumen;
use App\Models\Fisik;
use App\Models\Instansi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

use Exception;
use Illuminate\Support\Carbon;

use App\Models\Warga;
use App\Models\Iuran;
use App\Models\Keluaran;
use App\Models\Keuangan;
use App\Models\Lampiran;
use App\Models\LampiranKeluaran;
use App\Models\NomenklaturPerencanaan;
use App\Models\Partisipan;
use App\Models\Pembayaran;
use App\Models\Penghuni;
use App\Models\PenilaianRencana;
use App\Models\PenilaianRencanaItem;
use App\Models\Penyewa;
use App\Models\Permasalahan;
use App\Models\Permission;
use App\Models\RencanaKeluaran;
use App\Models\Sesi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProcessController extends Controller
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

    public function approve_admin(Request $request)
    {
        $id = $request->id;
        $instansi_kode = session('session_instansi');
        $sesi_kode = session('session_kode')->sesi_kode;

        if ($request->name == 'keluaran') { 
            try {
                $result = Keluaran::where('keluaran_id', $id)->first();
                $result->keluaran_status = $request->kode;
                $result->save();
                return response()->json(['status' => true]);
            } catch (\Throwable $th) {
                return response()->json(['status' => true]);
            }
        }

        if ($request->name == 'fisik') { 
            try {
                $nomenklatur = NomenklaturPerencanaan::where('nomenklatur_id', $id)->first();
                if ($nomenklatur) {
                    Fisik::where('fisik_instansi_kode', $instansi_kode)
                        ->where('fisik_sesi_kode', $sesi_kode)
                        ->where('fisik_subkegiatan_kode', $nomenklatur->nomenklatur_kode)
                        ->where('fisik_jenis', 0)
                        ->update(['fisik_status' => $request->kode]);
                }
                return response()->json(['status' => true]);
            } catch (\Throwable $th) {
                return response()->json(['status' => true]);
            }
        }

        return response()->json(['status' => true]);
    }

    public function create_nomenklatur(Request $request)
    {
        $rules = [
            'kode' => 'required',
            'nama' => 'required',
            'tahun' => 'required',
        ];

        try {
            $validator = Validator::make(
                $request->all(),
                $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'nomenklatur_kode' => $request->kode,
                    'nomenklatur_nama' => $request->nama,
                    'nomenklatur_indikator_keluaran' => $request->indikator,
                    'nomenklatur_satuan_keluaran' => $request->satuan,
                    'nomenklatur_tahun' => $request->tahun,
                ];

                NomenklaturPerencanaan::create($data);
                
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_nomenklatur(Request $request)
    {
        $rules = [
            'kode' => 'required',
            'nama' => 'required',
            'tahun' => 'required',
        ];

        try {
            $validator = Validator::make(
                $request->all(),
                $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'nomenklatur_kode' => $request->kode,
                    'nomenklatur_nama' => $request->nama,
                    'nomenklatur_indikator_keluaran' => $request->indikator,
                    'nomenklatur_satuan_keluaran' => $request->satuan,
                    'nomenklatur_tahun' => $request->tahun,
                ];

                NomenklaturPerencanaan::where('nomenklatur_id', $request->nomenklatur_id)->update($data);
                
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function delete_nomenklatur(Request $request)
    {
        try {
            $result = NomenklaturPerencanaan::where('nomenklatur_id', $request->id)->first();
            $result->delete();

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    public function create_session(Request $request)
    {
        $rules = [];

        if ($request->session == 'instansi') {
            $rules['instansi'] = 'required';
        }

        try {
            $validator = Validator::make(
                $request->all(),
                $rules
            );

            if ($validator->fails()) {
                if ($validator->errors()->has('instansi')) {
                    $validator->errors()->add(
                        'message',
                        'Pilih Perangkat Daerah'
                    );
                }

                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                if ($request->instansi) {
                    Session::put('session_instansi', $request->instansi);
                }

                if ($request->sesi) {
                    $result = Sesi::where('sesi_kode', $request->sesi)->first();
                    Session::put('session_kode', $result);
                }
                
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function create_sesi(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
                    'tanggal' => 'required',
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
                $data = [
                    'sesi_nama' => Str::upper($request->nama),
                    'sesi_kode' => $this->generateUniqueCode(),
                    'sesi_tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                    'sesi_tahun' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->year,
                    'sesi_keterangan' =>  $request->keterangan,
                ];

                Sesi::create($data);
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_sesi(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
                    'tanggal' => 'required',
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
                $sesi = Sesi::where('sesi_tahun', $request->tahun)->where('sesi_status', 1)->first();

                if ($request->status) {
                    $data = [
                        'sesi_nama' => Str::upper($request->nama),
                        'sesi_tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                        'sesi_tahun' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->year,
                        'sesi_keterangan' =>  $request->keterangan,
                        'sesi_status'=> 1
                    ];

                    Sesi::where('sesi_tahun', $request->tahun)->update(['sesi_status' => 0]);
                    Sesi::where('sesi_id', $request->id)->update($data);

                    return response()->json(['status' => true, 'message' => 'Berhasil']);
                } else {
                    if ($request->id == $sesi->sesi_id && $request->status == 0) {
                        return response()->json(['status' => false, 'message' => ['message' => 'Tidak Dijinkan Menonaktifkan Semua Sesi']]); 
                    } else {
                        $data = [
                            'sesi_nama' => Str::upper($request->nama),
                            'sesi_tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                            'sesi_tahun' => Carbon::createFromFormat('d/m/Y', $request->tanggal)->year,
                            'sesi_keterangan' =>  $request->keterangan,
                            'sesi_status' => $request->status
                        ];
                        Sesi::where('sesi_id', $request->id)->update($data);
                        return response()->json(['status' => true, 'message' => 'Berhasil']);
                    }
                    
                }
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function delete_keuangan(Request $request)
    {
        try {
            $result = Keuangan::where('keuangan_id', $request->id)->first();
            $result->delete();

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    public function create_keluaran(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'keluaran' => 'required',
                    'satuan' => 'required',
                    'target' => 'required'
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
                $kode = $this->generateCode(6);
                $data = [
                    'keluaran_uid' => session('session_kode')->sesi_tahun.session('session_kode')->sesi_kode.'0'.str_replace('.', '', $request->subkegiatan).'-'.$kode,
                    'keluaran_tipe' => $request->kode,
                    'keluaran_kode' => $kode,
                    'keluaran_subkegiatan_kode' => $request->subkegiatan,
                    'keluaran_instansi_kode' => session('session_instansi'),
                    'keluaran_sesi_kode' => session('session_kode')->sesi_kode,
                    'keluaran_nama' => $request->keluaran,
                    'keluaran_satuan' => $request->satuan,
                    'keluaran_target' => $request->target,
                    'keluaran_tahun' => session('session_kode')->sesi_tahun,
                    'keluaran_jenis' => 0
                ];

                Keluaran::create($data);
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_keluaran(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'keluaran' => 'required',
                    'satuan' => 'required',
                    'target' => 'required'
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

                $data = [
                    'keluaran_nama' => $request->keluaran,
                    'keluaran_satuan' => $request->satuan,
                    'keluaran_target' => $request->target,
                ];

                $result = Keluaran::where('keluaran_id', '=', $request->keluaran_id);
                $result->update($data);

                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function delete_keluaran(Request $request)
    {
        try {
            $result = Keluaran::where('keluaran_id', $request->id)->first();
            $result->delete();

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    // public function create_rencana_keluaran(Request $request)
    // {
    //     $rules = [];
    //     $bulan = $this->bulan;
    //     foreach ($bulan as $key => $value) {
    //         $rules['bulan_'.$key] = 'required';
    //     }

    //     try {
    //         $validator = Validator::make(
    //             $request->all(), $rules
    //         );

    //         if ($validator->fails()) {
    //             return response()->json(
    //                 [
    //                     'status' => false,
    //                     'message' => $validator->errors()
    //                 ]
    //             );
    //         } else {
    //             $target = $request->keluaran_target;
    //             $count = [];
    //             foreach ($bulan as $key => $value) {
    //                 array_push($count, $request->{ 'bulan_'.$key } );
    //             }

    //             if ($target == array_sum($count)) {
    //                 $keluaran = Keluaran::where('keluaran_id', $request->keluaran_id)->first();
    //                 if ($keluaran) {
    //                     $data = [
    //                         'rencana_keluaran_keluaran_id' => $keluaran->keluaran_id,
    //                         'rencana_keluaran_instansi_kode' => $keluaran->keluaran_instansi_kode,
    //                         'rencana_keluaran_subkegiatan_kode' => $keluaran->keluaran_subkegiatan_kode,
    //                         'rencana_keluaran_tahun' => $keluaran->keluaran_tahun,
    //                         'rencana_keluaran_sesi_kode' => $keluaran->keluaran_sesi_kode,
    //                         'rencana_keluaran_jenis' => 0
    //                     ];

    //                     foreach ($bulan as $key => $value) {
    //                         $data['rencana_keluaran_'.$key] =  $request->{ 'bulan_'.$key };
    //                     }

    //                     RencanaKeluaran::create($data);
    //                     return response()->json(['status' => true, 'message' => 'Berhasil']);
    //                 } else {
    //                     return response()->json(['status' => false, 'message' => ['message' => 'Data Gagal Ditambahkan']]);
    //                 }
    //             } else {
    //                 return response()->json(['status' => false, 'message' => ['message' => 'Jumlah Target dan Rencana Keluaran Belum Sesuai', 'jumlah' => array_sum($count)]]);
    //             }
    //         }
    //     } catch (Exception $error) {
    //         return response()->json(['status' => false, 'message' => $error]);
    //     }
    // }

    public function update_rencana_keluaran(Request $request)
    {
        $rules = [];
        $bulan = $this->bulan;
        foreach ($bulan as $key => $value) {
            $rules['bulan_'.$key] = 'required';
        }

        try {
            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $target = intval($request->keluaran_target);
                $count = [];
                foreach ($bulan as $key => $value) {
                    array_push($count, intval($request->{ 'bulan_'.$key } ));
                }

                if ($target == array_sum($count)) {
                    $data = [];
                    foreach ($bulan as $key => $value) {
                        $data['keluaran_'.$key] =  $request->{ 'bulan_'.$key };
                    }

                    $result = Keluaran::where('keluaran_id', $request->keluaran_id);
                    $result->update($data);
                    if ($result) {
                        return response()->json(['status' => true, 'message' => 'Berhasil']);
                    } else {
                        return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                    }
                    
                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'Jumlah Target dan Rencana Keluaran Belum Sesuai', 'jumlah' => array_sum($count)]]);
                }
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function create_ropk_fisik(Request $request)
    {
        $rules = [
            'tahapan' => 'required',
            'nomor' => 'required',
            'aktivitas' => 'required',
            'acuan' => 'required'
        ];

        $bulan = $this->bulan;
        foreach ($bulan as $key => $value) {
            $rules['bulan_'.$key] = 'required';
        }

        try {
            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $keluaran = Keluaran::where('keluaran_id', $request->keluaran_id)->first();
                if ($keluaran) {
                    $count = [];
                    foreach ($bulan as $key => $value) {
                        array_push($count, $request->{ 'bulan_'.$key } );
                    }

                    if ($request->acuan == array_sum($count)) {
                        $kode = $this->generateCode(6);
                        $data = [
                            'fisik_uid' => session('session_kode')->sesi_kode.session('session_tahun').'0'.$keluaran->keluaran_instansi_kode.$keluaran->keluaran_subkegiatan_kode.'-'.$kode,
                            'fisik_instansi_kode' => $keluaran->keluaran_instansi_kode,
                            'fisik_subkegiatan_kode' => $keluaran->keluaran_subkegiatan_kode,
                            'fisik_kode' => $kode,
                            'fisik_tahapan' => $request->tahapan,
                            'fisik_nomor' => $request->nomor,
                            'fisik_aktivitas' => $request->aktivitas,
                            'fisik_acuan' => $request->acuan,
                            'fisik_tahun' => session('session_tahun'),
                            'fisik_sesi_kode' => session('session_kode')->sesi_kode,
                            'fisik_jenis' => 0
                        ];
                        
                        foreach ($bulan as $key => $value) {
                            $data['fisik_'.$key] =  $request->{ 'bulan_'.$key };
                        }

                        $result = Fisik::create($data);
                    
                        if ($result) {
                            return response()->json(['status' => true, 'message' => 'Berhasil']);
                        } else {
                            return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                        }
                        
                    } else {
                        return response()->json(['status' => false, 'message' => ['message' => 'Jumlah Acuan dan ROPK Fisik Belum Sesuai', 'data' => array_sum($count)]]);
                    }
                    
                    
                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'Data Gagal Ditambahkan']]);
                }
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_ropk_fisik(Request $request)
    {
        $rules = [
            'tahapan' => 'required',
            'nomor' => 'required',
            'aktivitas' => 'required',
            'acuan' => 'required'
        ];

        $bulan = $this->bulan;
        foreach ($bulan as $key => $value) {
            $rules['bulan_'.$key] = 'required';
        }

        try {
            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $count = [];
                foreach ($bulan as $key => $value) {
                    array_push($count, $request->{ 'bulan_'.$key } );
                }

                if ($request->acuan == array_sum($count)) {
                    $data = [
                        'fisik_tahapan' => $request->tahapan,
                        'fisik_nomor' => $request->nomor,
                        'fisik_aktivitas' => $request->aktivitas,
                        'fisik_acuan' => $request->acuan,
                    ];
                    
                    foreach ($bulan as $key => $value) {
                        $data['fisik_'.$key] =  $request->{ 'bulan_'.$key };
                    }

                    $result = Fisik::where('fisik_id', $request->fisik_id);
                    $result->update($data);
                
                    if ($result) {
                        return response()->json(['status' => true, 'message' => 'Berhasil']);
                    } else {
                        return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                    }
                    
                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'Jumlah Acuan dan ROPK Fisik Belum Sesuai']]);
                }
                    
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function delete_ropk_fisik(Request $request)
    {
        try {
            $result = Fisik::where('fisik_id', $request->id)->first();
            $result->delete();
            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    // public function create_realisasi_fisik(Request $request)
    // {
    //     $rules = [
    //         'tipe' => 'required',
    //         'realisasi' => 'required'
    //     ];

    //     if ($request->has('tipe') && $request->tipe == 1) {
    //         $rules['dokumen'] = 'required|mimes:pdf|max:1048';
    //     }

    //     if ($request->has('tipe') && $request->tipe == 2) {
    //         $rules['link'] = 'required|url';
    //     }

    //     $messages = [
    //         'link.required' => 'Link Tidak Boleh Kosong',
    //         'link.url' => 'Format Link Tidak Valid',
    //         'dokumen.required' => 'Dokumen Tidak Boleh Kosong',
    //         'dokumen.mimes' => 'Format Dokumen Tidak Valid',
    //         'dokumen.max' => 'Ukuran File Maximal 1 MB'
    //     ];

    //     try {
    //         $validator = Validator::make(
    //             $request->all(), $rules, $messages
    //         );

    //         if ($validator->fails()) {

    //             if ($validator->errors()->has('tipe')) {
    //                 $validator->errors()->add(
    //                     'message',
    //                     'Lampiran Belum Ada'
    //                 );
    //             }

    //             if ($validator->errors()->has('dokumen')) {
    //                 $validator->errors()->add(
    //                     'message',
    //                     $validator->errors()->first('dokumen')
    //                 );
    //             }

    //             if ($validator->errors()->has('link')) {
    //                 $validator->errors()->add(
    //                     'message',
    //                     $validator->errors()->first('link')
    //                 );
    //             }

    //             return response()->json(
    //                 [
    //                     'status' => false,
    //                     'message' => $validator->errors()
    //                 ]
    //             );
    //         } else {
    //             $target_fisik = Fisik::where('fisik_id', $request->fisik_id)->first();
    //             if ($target_fisik) {
    //                 $data = [
    //                     'fisik_keluaran_id' => $target_fisik->fisik_keluaran_id,
    //                     'fisik_instansi_kode' => $target_fisik->fisik_instansi_kode,
    //                     'fisik_subkegiatan_kode' => $target_fisik->fisik_subkegiatan_kode,
    //                     'fisik_kode' => $target_fisik->fisik_kode,
    //                     'fisik_tahapan' => $target_fisik->fisik_tahapan,
    //                     'fisik_nomor' => $target_fisik->fisik_nomor,
    //                     'fisik_aktivitas' => $target_fisik->fisik_aktivitas,
    //                     'fisik_acuan' => $target_fisik->fisik_acuan,
    //                     'fisik_tahun' => '2025',
    //                     'fisik_sesi_kode' => 'LIRS6N',
    //                     'fisik_jenis' => 1,
    //                     'fisik_'.$request->bulan => $request->realisasi
    //                 ];

    //                 $realisasi_fisik = Fisik::create($data);
    //                 $fisik_id = $realisasi_fisik->fisik_id;

    //                 $lampiran = [
    //                     'lampiran_fisik_id' => $fisik_id,
    //                     'lampiran_tipe' => $request->tipe,
    //                     'lampiran_bulan' => $request->bulan
    //                 ];

    //                 if ($request->file('dokumen')) {
    //                     $file = $request->file('dokumen');
    //                     $extension = $file->extension();
    //                     $path = 'assets/img/fisik/';
    //                     $filename = time().'_'.$request->fisik_id .'.'. $extension;
    //                     $upload = $this->upload($file, $extension, $path, $filename);
    //                     $lampiran['lampiran_filename'] = $upload;
    //                 } else {
    //                     $lampiran['lampiran_filename'] = $request->link;
    //                 }

    //                 $result = Lampiran::create($lampiran);
                
    //                 if ($realisasi_fisik && $result) {
    //                     return response()->json(['status' => true, 'message' => 'Berhasil']);
    //                 } else {
    //                     return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
    //                 }

    //             } else {
    //                  return response()->json(['status' => false, 'message' => ['message' => 'Data Gagal Ditambahkan']]);
    //             }
    //         }
    //     } catch (Exception $error) {
    //         return response()->json(['status' => false, 'message' => $error]);
    //     }
    // }

    public function update_realisasi_fisik(Request $request)
    {
        $rules = [
            'realisasi' => 'required'
        ];

        if ($request->lampiran == '' && $request->realisasi != 0) {
            $rules['tipe'] = 'required';
        }

        if ($request->has('tipe') && $request->tipe == 1) {
            $rules['dokumen'] = 'required|mimes:pdf|max:1048';
        }

        if ($request->has('tipe') && $request->tipe == 2) {
            $rules['link'] = 'required|url';
        }

        $messages = [
            'link.required' => 'Link Tidak Boleh Kosong',
            'link.url' => 'Format Link Tidak Valid',
            'dokumen.required' => 'Dokumen Tidak Boleh Kosong',
            'dokumen.mimes' => 'Format Dokumen Tidak Valid',
            'dokumen.max' => 'Ukuran File Maximal 1 MB'
        ];

        try {
            $validator = Validator::make(
                $request->all(), $rules, $messages
            );

            if ($validator->fails()) {

                if ($validator->errors()->has('tipe')) {
                    $validator->errors()->add(
                        'message',
                        'Bukti Dukung Belum Ada'
                    );
                }

                if ($validator->errors()->has('dokumen')) {
                    $validator->errors()->add(
                        'message',
                        $validator->errors()->first('dokumen')
                    );
                }

                if ($validator->errors()->has('link')) {
                    $validator->errors()->add(
                        'message',
                        $validator->errors()->first('link')
                    );
                }

                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $realisasi_fisik = Fisik::where('fisik_id', $request->fisik_id)->first();
                if ($realisasi_fisik) {
                    $data = [
                        'fisik_'.$request->bulan => $request->realisasi
                    ];

                    $realisasi_fisik->update($data);

                    if ($request->lampiran == '' && $request->realisasi != 0) {
                        $lampiran = [];

                        if ($request->file('dokumen')) {
                            $file = $request->file('dokumen');
                            $extension = $file->extension();
                            $path = 'assets/img/fisik/';
                            $filename = time().'_'.$realisasi_fisik->fisik_uid .'.'. $extension;
                            $upload = $this->upload($file, $extension, $path, $filename);
                            $lampiran['lampiran_'.$request->bulan] = [
                                'tipe' => $request->tipe,
                                'filename' => $upload
                            ];
                        } else {
                             $lampiran['lampiran_'.$request->bulan] = [
                                'tipe' => $request->tipe,
                                'filename' => $request->link
                            ];;
                        }

                        $result = $realisasi_fisik->lampiran_fisik()->updateOrCreate(
                            [
                                'lampiran_kode' => $realisasi_fisik->fisik_uid,
                            ],
                            $lampiran
                        );
                    
                        if ($realisasi_fisik && $result) {
                            return response()->json(['status' => true, 'message' => 'Berhasil']);
                        } else {
                            return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                        }
                    } else {
                        if ($realisasi_fisik) {
                            return response()->json(['status' => true, 'message' => 'Berhasil']);
                        } else {
                            return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                        }
                    }

                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'Data Gagal Ditambahkan']]);
                }
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_realisasi_keluaran(Request $request)
    {
        $rules = [
            'realisasi' => 'required'
        ];

        if ($request->lampiran == '' && $request->realisasi != 0) {
            $rules['tipe'] = 'required';
        }

        if ($request->has('tipe') && $request->tipe == 1) {
            $rules['dokumen'] = 'required|mimes:pdf|max:1048';
        }

        if ($request->has('tipe') && $request->tipe == 2) {
            $rules['link'] = 'required|url';
        }

        $messages = [
            'link.required' => 'Link Tidak Boleh Kosong',
            'link.url' => 'Format Link Tidak Valid',
            'dokumen.required' => 'Dokumen Tidak Boleh Kosong',
            'dokumen.mimes' => 'Format Dokumen Tidak Valid',
            'dokumen.max' => 'Ukuran File Maximal 1 MB'
        ];

        try {
            $validator = Validator::make(
                $request->all(), $rules, $messages
            );

            if ($validator->fails()) {

                if ($validator->errors()->has('tipe')) {
                    $validator->errors()->add(
                        'message',
                        'Bukti Dukung Belum Ada'
                    );
                }

                if ($validator->errors()->has('dokumen')) {
                    $validator->errors()->add(
                        'message',
                        $validator->errors()->first('dokumen')
                    );
                }

                if ($validator->errors()->has('link')) {
                    $validator->errors()->add(
                        'message',
                        $validator->errors()->first('link')
                    );
                }

                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {
                $realisasi_keluaran = Keluaran::with('lampiran_keluaran')->where('keluaran_id', $request->keluaran_id)->first();
                if ($realisasi_keluaran) {
                    $data = [
                        'keluaran_'.$request->bulan => $request->realisasi
                    ];

                    $realisasi_keluaran->update($data);
                    // $fisik_id = $realisasi_keluaran->keluaran_id;

                    if ($request->lampiran == '' && $request->realisasi != 0) {
                        $lampiran = [];

                        if ($request->file('dokumen')) {
                            $file = $request->file('dokumen');
                            $extension = $file->extension();
                            $path = 'assets/img/keluaran/';
                            $filename = time().'_'.$realisasi_keluaran->keluaran_uid .'.'. $extension;
                            $upload = $this->upload($file, $extension, $path, $filename);
                            $lampiran['lampiran_'.$request->bulan] = [
                                'tipe' => $request->tipe,
                                'filename' => $upload
                            ];
                        } else {
                            $lampiran['lampiran_'.$request->bulan] = [
                                'tipe' => $request->tipe,
                                'filename' => $request->link
                            ];
                        }

                        $result = $realisasi_keluaran->lampiran_keluaran()->updateOrCreate(
                            [
                                'lampiran_kode' => $realisasi_keluaran->keluaran_uid,
                            ],
                            $lampiran
                        );

                        // $result = Lampiran::create($lampiran);
                    
                        if ($realisasi_keluaran && $result) {
                            return response()->json(['status' => true, 'message' => 'Berhasil']);
                        } else {
                            return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                        }
                    } else {
                        if ($realisasi_keluaran) {
                            return response()->json(['status' => true, 'message' => 'Berhasil']);
                        } else {
                            return response()->json(['status' => false, 'message' => ['message' => 'Terjadi Kesalahan']]);
                        }
                    }

                } else {
                    return response()->json(['status' => false, 'message' => ['message' => 'Data Gagal Ditambahkan']]);
                }
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function create_permasalahan(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'permasalahan' => 'required',
                    'tindaklanjut' => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => ['message' => 'Permasalahan dan Tidak Lanjut Wajib Diisi']
                    ]
                );
            } else {
                $data = [
                    'permasalahan_kode' => $request->tahun.$request->sesi_kode.$request->instansi_kode.str_replace('.','',$request->subkegiatan_kode.'-'.$request->bulan),
                    'permasalahan_instansi_kode' => $request->instansi_kode,
                    'permasalahan_subkegiatan_kode' => $request->subkegiatan_kode,
                    'permasalahan_bulan' => $request->bulan,
                    'permasalahan_deskripsi' => json_encode($request->permasalahan),
                    'permasalahan_tindaklanjut' => json_encode($request->tindaklanjut),
                    'permasalahan_tahun' => $request->tahun,
                    'permasalahan_sesi_kode' => $request->sesi_kode,
                ];

                Permasalahan::create($data);
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_permasalahan(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'permasalahan' => 'required',
                    'tindaklanjut' => 'required'
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => ['message' => 'Permasalahan dan Tidak Lanjut Wajib Diisi']
                    ]
                );
            } else {
                $data = [
                    'permasalahan_deskripsi' => json_encode($request->permasalahan),
                    'permasalahan_tindaklanjut' => json_encode($request->tindaklanjut),
                ];

                $result = Permasalahan::where('permasalahan_id', $request->id);
                $result->update($data);
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function upsert_permasalahan(Request $request)
    {
        try {
            $data_insert = [];
            $data_update = [];
            $tahun = '2025';
            $sesi_kode = 'LIRS6N';

            if ($request->subkegiatan && count($request->subkegiatan) != 0) {
                foreach ($request->subkegiatan as $item) {
                    if ($request->{'kode_'.str_replace('.', '', $item)}) {
                        $input = [
                            'permasalahan_id' => $request->{'id_'.str_replace('.', '', $item)},
                            'permasalahan_verifikasi' => $request->{'verifikasi_'.str_replace('.', '', $item)},
                            'permasalahan_catatan' => $request->{'catatan_'.str_replace('.', '', $item)}
                        ];
                        $data_update[] = $input;
                    } else {
                        $input = [
                            'permasalahan_kode' => $tahun.$sesi_kode.$request->{'instansi_kode_'.str_replace('.', '', $item)}.str_replace('.','',$request->{'subkegiatan_kode_'.str_replace('.', '', $item)}).'-'.$request->{'bulan_'.str_replace('.', '', $item)},
                            'permasalahan_instansi_kode' => $request->{'instansi_kode_'.str_replace('.', '', $item)},
                            'permasalahan_subkegiatan_kode' => $request->{'subkegiatan_kode_'.str_replace('.', '', $item)},
                            'permasalahan_bulan' => $request->{'bulan_'.str_replace('.', '', $item)},
                            'permasalahan_tahun' => $tahun,
                            'permasalahan_sesi_kode' => $sesi_kode,
                            'permasalahan_verifikasi' => $request->{'verifikasi_'.str_replace('.', '', $item)},
                            'permasalahan_catatan' => $request->{'catatan_'.str_replace('.', '', $item)}
                        ];
                        $data_insert[] = $input;
                    }
                    
                }
            }

            Permasalahan::upsert(
                $data_update,
                uniqueBy: ['permasalahan_id'],
                update: ['permasalahan_verifikasi', 'permasalahan_catatan']
            );

            Permasalahan::insert($data_insert);
            
            return response()->json(['status' => true, 'message' => 'Berhasil']);
            
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function create_penilaian_perencanaan(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
                    'tanggal' => 'required',
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
                $data = [
                    'penilaian_rencana_nama' => $request->nama,
                    'penilaian_rencana_deadline' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                    'penilaian_rencana_bulan' => $request->bulan,
                    'penilaian_rencana_tahun' => $request->tahun,
                ];

                PenilaianRencana::create($data);
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_penilaian_perencanaan(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
                    'tanggal' => 'required',
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
                $data = [
                    'penilaian_rencana_nama' => $request->nama,
                    'penilaian_rencana_deadline' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                ];

                $result = PenilaianRencana::where('penilaian_rencana_id', $request->id);
                $result->update($data);

                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function create_penilaian_perencanaan_opd(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'tanggal' => 'required',
                    'dokumen' => 'required|mimes:jpg,jpeg,png|max:1024',
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
                $id = $request->penilaian_rencana_id;
                $rencana_keluaran = PenilaianRencana::where('penilaian_rencana_id', $id)->first();

                $deadline = Carbon::parse($rencana_keluaran->penilaian_rencana_deadline);
                $diterima = Carbon::parse(Carbon::createFromFormat('d/m/Y', $request->tanggal)->toDateString());
                $jumlah = $deadline->diffInDays($diterima, false);
                $nilai = $this->get_penilaian_perencanaan($jumlah);

                $data = [
                    'penilaian_rencana_item_rencana_id' => $rencana_keluaran->penilaian_rencana_id,
                    'penilaian_rencana_item_instansi_kode' => $request->instansi_kode,
                    'penilaian_rencana_item_tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                    'penilaian_rencana_item_jumlah' => $jumlah,
                    'penilaian_rencana_item_nilai' => $nilai
                ];

                if ($request->file('dokumen')) {
                    $file = $request->file('dokumen');
                    $extension = $file->extension();
                    $path = 'assets/img/penilaian_rencana/';
                    $filename = time().'_'.$request->instansi_kode .'.'. $extension;
                    $upload = $this->upload($file, $extension, $path, $filename);
                    $data['penilaian_rencana_item_lampiran'] = $upload;
                }

                PenilaianRencanaItem::create($data);
                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    function get_penilaian_perencanaan($jumlah) {
        if ($jumlah <= 0) return 100;
        if ($jumlah < 6)  return 75;
        return 50;
    }

    public function delete_penilaian_perencanaan_opd(Request $request)
    {
        try {
            $result = PenilaianRencanaItem::where('penilaian_rencana_item_id', $request->id)->first();
            if ($result->penilaian_rencana_item_lampiran != '' && file_exists(public_path('/assets/img/penilaian_rencana/'.$result->penilaian_rencana_item_lampiran))) {
                File::delete(public_path('/assets/img/penilaian_rencana/' .$result->penilaian_rencana_item_lampiran));
            }
            $result->delete();
            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => true]);
        }
    }

    public function upsert_permission(Request $request)
    {
        try {
            $instansi = Instansi::where('kode', 1)->whereNot('instansi_nama', 'Pengecualian')->get();
            $tahun = $request->input('tahun');
            $inputPermissions = $request->input('permissions', []);

            $data_upsert = [];

            foreach ($instansi as $item) {
                $instansi_kode = $item->instansi_kode;
                
                $row = [
                    'auth_instansi_kode' => $instansi_kode,
                    'auth_tahun' => $tahun,
                    'auth_uid' => $tahun . $instansi_kode,
                ];

                for ($i = 1; $i <= 12; $i++) {
                    $row["auth_{$i}"] = isset($inputPermissions[$instansi_kode][$i]) ? 1 : 0;
                }

                $data_upsert[] = $row;
            }

            if (!empty($data_upsert)) {
                Permission::upsert(
                    $data_upsert, 
                    ['auth_uid'],
                    ['auth_1', 'auth_2', 'auth_3', 'auth_4', 'auth_5', 'auth_6', 'auth_7', 'auth_8', 'auth_9', 'auth_10', 'auth_11', 'auth_12']
                );
            }

            // foreach ($daftarInstansi as $instansi) {
            //     $kode = $instansi->instansi_kode;
            //     $dataUpdate = [];

            //     // 3. Loop 12 bulan untuk mengisi kolom auth_1 sampai auth_12
            //     for ($i = 1; $i <= 12; $i++) {
            //         // Jika ada di input berarti 1 (checked), jika tidak ada berarti 0 (unchecked)
            //         $dataUpdate["auth_{$i}"] = isset($permissionsInput[$kode][$i]) ? 1 : 0;
            //     }

            //     // 4. Update atau Insert ke database
            //     // Gunakan updateOrInsert agar data yang belum ada otomatis terbuat
            //     DB::table('permissions')->updateOrInsert(
            //         [
            //             'auth_instansi_kode' => $kode,
            //             'auth_tahun' => $tahun
            //         ],
            //         $dataUpdate
            //     );
            // }

            // Permasalahan::upsert(
            //     $data_update,
            //     uniqueBy: ['permasalahan_id'],
            //     update: ['permasalahan_verifikasi', 'permasalahan_catatan']
            // );
            
            return response()->json(['status' => true, 'message' => $data_upsert ]);
            
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }





    public function create_admin(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'username' => 'required|min:3|unique:monev_admin,username|alpha_dash',
                    'password' => 'required|min:6',
                    'nama' => 'required',
                    'otorisasi' => 'required|array',
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

                $data = [
                    'username' => $request->username,
                    'password' => Hash::make($request->password),
                    'admin_nama' => Str::upper($request->nama),
                    'admin_otorisasi' => $request->otorisasi,
                    'admin_role' => $request->role,
                ];

                Admin::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function update_admin(Request $request)
    {
        try {
            $rules = [
                'username' => 'required|min:3|unique:monev_admin,username,'.$request->id.',admin_id|alpha_dash',
                'nama' => 'required',
                'otorisasi' => 'required|array',
            ];

            if ($request->password != '') {
                $rules['password'] = [
                    Password::min(8)
                        ->letters()
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ];
            }

            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'username' => $request->username,
                    'admin_nama' => Str::upper($request->nama),
                    'admin_otorisasi' => $request->otorisasi,
                    'admin_role' => $request->role,
                    'admin_aktif' => $request->aktif,
                ];

                if ($request->password != '') {
                $data['password'] = Hash::make($request->password);
            }

                $result = Admin::where('admin_id', '=', $request->id);
                $result->update($data);

                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function create_user(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nomor' => 'required',
                    'nama' => 'required',
                    'hp' => 'required',
                    'status' => 'required',
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

                $data = [
                    'warga_no_rumah' => $request->nomor,
                    'warga_nama' => Str::upper($request->nama),
                    'warga_hp' => $request->hp,
                    'warga_status' => $request->status,
                    'warga_keterangan' => $request->keterangan,
                ];

                Warga::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function update_user(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nomor' => 'required',
                    'nama' => 'required',
                    'hp' => 'required',
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

                $data = [
                    'warga_no_rumah' => $request->nomor,
                    'warga_nama' => Str::upper($request->nama),
                    'warga_hp' => $request->hp,
                    'warga_status' => $request->status,
                    'warga_keterangan' => $request->keterangan,
                ];

                $result = Warga::where('warga_id', '=', $request->id);
                $result->update($data);

                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function delete_detail_user(Request $request)
    {
        try {
            if ($request->to == 'penyewa') {
                $result = Penyewa::with('penghuni')->where('penyewa_id', $request->id)->first();
                if ($result->penyewa_dokumen != '' && file_exists(public_path('/assets/img/penyewa/'.$result->penyewa_dokumen))) {
                    File::delete(public_path('/assets/img/penyewa/' . $result->penyewa_dokumen));
                }
                $result->penghuni()->each(function ($item) {
                    $item->delete();
                });
                $result->delete();
            }

            if ($request->to == 'warga') {
                $result = Warga::with('penyewa', 'penyewa.penghuni')->where('warga_id', $request->id)->first();
                $result->penyewa()->each(function ($item) {
                    if ($item->penyewa_dokumen != '' && file_exists(public_path('/assets/img/penyewa/'.$item->penyewa_dokumen))) {
                        File::delete(public_path('/assets/img/penyewa/' . $item->penyewa_dokumen));
                    }
                    $item->penghuni()->each(function ($item2) {
                        $item2->delete();
                    });
                    $item->delete();
                });
                $result->delete();
            }

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    public function create_penyewa(Request $request)
    {
        try {

            $rules = [
                'kepemilikan' => 'required',
                'kedudukan' => 'required',
                'nama' => 'required',
                'status' => 'required',
            ];

            if ($request->file('dokumen')) {
                $rules['dokumen'] = 'mimes:jpg,jpeg,png,pdf|max:2048';
            }


            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'penyewa_warga_id' => $request->warga_id,
                    'penyewa_kepemilikan' => $request->kepemilikan,
                    'penyewa_kedudukan' => $request->kedudukan,
                    'penyewa_nama' => Str::upper($request->nama),
                    'penyewa_hp' => $request->hp,
                    'penyewa_keterangan' => $request->keterangan,
                    'penyewa_status' => $request->status,
                    'penyewa_awal' => $request->awal ? Carbon::createFromFormat('d/m/Y', $request->awal) : NULL,
                ];

                if ($request->file('dokumen')) {
                    $file = $request->file('dokumen');
                    $extension = $file->extension();
                    $path = 'assets/img/penyewa/';
                    $filename = time().'_'.$request->warga_id .'.'. $extension;
                    $upload = $this->upload($file, $extension, $path, $filename);
                    $data['penyewa_dokumen'] = $upload;
                }

                Penyewa::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_penyewa(Request $request)
    {
        try {

            $rules = [
                'nama' => 'required',
                'status' => 'required',
            ];

            if ($request->file('dokumen')) {
                $rules['dokumen'] = 'mimes:jpg,jpeg,png,pdf|max:2048';
            }


            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'penyewa_kepemilikan' => $request->kepemilikan,
                    'penyewa_kedudukan' => $request->kedudukan,
                    'penyewa_nama' => Str::upper($request->nama),
                    'penyewa_hp' => $request->hp,
                    'penyewa_keterangan' => $request->keterangan,
                    'penyewa_status' => $request->status,
                    'penyewa_awal' => $request->awal ? Carbon::createFromFormat('d/m/Y', $request->awal) : NULL,
                    'penyewa_akhir' => $request->akhir ? Carbon::createFromFormat('d/m/Y', $request->akhir) : NULL,
                    'penyewa_aktif' => $request->aktif,
                ];

                if ($request->file('dokumen')) {
                    $file = $request->file('dokumen');
                    $extension = $file->extension();
                    $path = 'assets/img/penyewa/';
                    $filename = time().'_'.$request->warga_id .'.'. $extension;
                    $upload = $this->upload($file, $extension, $path, $filename);
                    $data['penyewa_dokumen'] = $upload;
                }

                $result = Penyewa::where('penyewa_id', '=', $request->id);
                $result->update($data);

                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function create_penghuni(Request $request)
    {
        try {

            $rules = [
                'nama' => 'required',
                'status' => 'required',
            ];

            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'penghuni_warga_id' => $request->warga_id,
                    'penghuni_penyewa_id' => $request->penyewa_id,
                    'penghuni_nama' => Str::upper($request->nama),
                    'penghuni_nik' => $request->nik,
                    'penghuni_tempat_lahir' => Str::upper($request->tempat),
                    'penghuni_tanggal_lahir' => $request->tanggal ? Carbon::createFromFormat('d/m/Y', $request->tanggal) : NULL,
                    'penghuni_status' => $request->status,
                    'penghuni_keterangan' => $request->keterangan
                ];

                Penghuni::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_penghuni(Request $request)
    {
        try {

            $rules = [
                'nama' => 'required',
                'status' => 'required',
            ];

            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'penghuni_nama' => Str::upper($request->nama),
                    'penghuni_nik' => $request->nik,
                    'penghuni_tempat_lahir' => Str::upper($request->tempat),
                    'penghuni_tanggal_lahir' => $request->tanggal ? Carbon::createFromFormat('d/m/Y', $request->tanggal) : NULL,
                    'penghuni_status' => $request->status,
                    'penghuni_keterangan' => $request->keterangan,
                    'penghuni_kondisi' => $request->kondisi,
                ];

                $result = Penghuni::where('penghuni_id', '=', $request->id);
                $result->update($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function delete_penghuni(Request $request)
    {
        try {
            $result = Penghuni::where('penghuni_id', $request->id)->first();
            $result->delete();

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    public function create_iuran(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
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

                $data = [
                    'iuran_nama' => Str::upper($request->nama),
                    'iuran_keterangan' => $request->keterangan,
                ];

                Iuran::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function update_iuran(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nama' => 'required',
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

                $data = [
                    'iuran_nama' => Str::upper($request->nama),
                    'iuran_keterangan' => $request->keterangan,
                ];

                $result = Iuran::where('iuran_id', '=', $request->id);
                $result->update($data);

                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function delete_iuran(Request $request)
    {
        try {
            $result = Iuran::with('partisipan', 'pembayaran')->where('iuran_id', $request->id)->first();
            $result->partisipan()->each(function ($item) {
                $item->delete();
            });
            $result->pembayaran()->each(function ($item) {
                if ($item->pembayaran_dokumen != '' && file_exists(public_path('/assets/img/pembayaran/'.$item->pembayaran_dokumen))) {
                    File::delete(public_path('/assets/img/pembayaran/' . $item->pembayaran_dokumen));
                }
                $item->delete();
            });
            $result->delete();

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => false]);
        }
    }

    public function create_partisipan(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'kategori' => 'required',
                    'nama' => 'required',
                    'alamat' => 'required',
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

                $data = [
                    'partisipan_iuran_id' => $request->iuran_id,
                    'partisipan_kategori' => $request->kategori,
                    'partisipan_nama' => Str::upper($request->nama),
                    'partisipan_alamat' => $request->alamat,
                    'partisipan_hp' => $request->hp,
                    'partisipan_keterangan' => $request->keterangan,
                    'partisipan_admin_id' => auth()->user()->admin_id,
                ];

                Partisipan::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function update_partisipan(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'kategori' => 'required',
                    'nama' => 'required',
                    'alamat' => 'required',
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

                $data = [
                    'partisipan_kategori' => $request->kategori,
                    'partisipan_nama' => Str::upper($request->nama),
                    'partisipan_alamat' => $request->alamat,
                    'partisipan_hp' => $request->hp,
                    'partisipan_keterangan' => $request->keterangan,
                ];

                $result = Partisipan::where('partisipan_id', '=', $request->id);
                $result->update($data);

                return response()->json(['status' => true, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => true, 'message' => $error]);
        }
    }

    public function create_partisipan_pembayaran(Request $request)
    {
        try {

            $rules = [
                'jumlah' => 'required',
                'tanggal' => 'required',
            ];

            if ($request->file('dokumen')) {
                $rules['dokumen'] = 'mimes:jpg,jpeg,png,pdf|max:2048';
            }


            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'pembayaran_iuran_id' => $request->iuran_id,
                    'pembayaran_partisipan_id' => $request->partisipan_id,
                    'pembayaran_tipe' => $request->pembayaran,
                    'pembayaran_jumlah' => str_replace(".","", $request->jumlah),
                    'pembayaran_tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                    'pembayaran_keterangan' => $request->keterangan,
                    'pembayaran_admin_id' => auth()->user()->admin_id,
                ];

                if ($request->file('dokumen')) {
                    $file = $request->file('dokumen');
                    $extension = $file->extension();
                    $path = 'assets/img/pembayaran/';
                    $filename = time().'_'.$request->partisipan_id .'.'. $extension;
                    $upload = $this->upload($file, $extension, $path, $filename);
                    $data['pembayaran_dokumen'] = $upload;
                }

                Pembayaran::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function update_partisipan_pembayaran(Request $request)
    {
        try {

            $rules = [
                'jumlah' => 'required',
                'tanggal' => 'required',
            ];

            if ($request->file('dokumen')) {
                $rules['dokumen'] = 'mimes:jpg,jpeg,png,pdf|max:2048';
            }


            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'pembayaran_tipe' => $request->pembayaran,
                    'pembayaran_jumlah' => str_replace(".","", $request->jumlah),
                    'pembayaran_keterangan' => $request->keterangan,
                    'pembayaran_tanggal' => Carbon::createFromFormat('d/m/Y', $request->tanggal),
                ];

                if ($request->file('dokumen')) {
                    $file = $request->file('dokumen');
                    $extension = $file->extension();
                    $path = 'assets/img/pembayaran/';
                    $filename = time().'_'.$request->partisipan_id .'.'. $extension;
                    $upload = $this->upload($file, $extension, $path, $filename);
                    $data['pembayaran_dokumen'] = $upload;
                }
            
                $result = Pembayaran::where('pembayaran_id', '=', $request->id);
                $result->update($data);

                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }

    public function delete_detail_partisipan(Request $request)
    {
        try {
            if ($request->to == 'pembayaran') {
                $result = Pembayaran::where('pembayaran_id', $request->id)->first();
                if ($result->pembayaran_dokumen != '' && file_exists(public_path('/assets/img/pembayaran/'.$result->pembayaran_dokumen))) {
                    File::delete(public_path('/assets/img/pembayaran/' . $result->pembayaran_dokumen));
                }
                $result->delete();
            }

            if ($request->to == 'dokumen') {
                $result = Dokumen::where('dokumen_id', $request->id)->first();
                if ($result->dokumen_file != '' && file_exists(public_path('/assets/img/dokumen/'.$result->dokumen_file))) {
                    File::delete(public_path('/assets/img/dokumen/' . $result->dokumen_file));
                    $result->delete();
                }
            }

            if ($request->to == 'partisipan') {
                $result = Partisipan::with('dokumen','pembayaran')->where('partisipan_id', $request->id)->first();
                $result->dokumen()->each(function ($item) {
                    if ($item->dokumen_file != '' && file_exists(public_path('/assets/img/dokumen/'.$item->dokumen_file))) {
                        File::delete(public_path('/assets/img/dokumen/' . $item->dokumen_file));
                    }
                    $item->delete();
                });
                $result->pembayaran()->each(function ($item) {
                    if ($item->pembayaran_dokumen != '' && file_exists(public_path('/assets/img/pembayaran/'.$item->pembayaran_dokumen))) {
                        File::delete(public_path('/assets/img/pembayaran/' . $item->pembayaran_dokumen));
                    }
                    $item->delete();
                });
                $result->delete();
            }

            return response()->json(['status' => true ]);
        } catch (Exception $error) {
            return response()->json(['status' => true]);
        }
    }

    public function create_partisipan_dokumen(Request $request)
    {
        try {

            $rules = [
                'nama' => 'required',
                'dokumen' => 'required|mimes:jpg,jpeg,png,pdf|max:2048'
            ];

            $validator = Validator::make(
                $request->all(), $rules
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => $validator->errors()
                    ]
                );
            } else {

                $data = [
                    'dokumen_partisipan_id' => $request->partisipan_id,
                    'dokumen_nama' => $request->nama,
                ];

                if ($request->file('dokumen')) {
                    $file = $request->file('dokumen');
                    $extension = $file->extension();
                    $path = 'assets/img/dokumen/';
                    $filename = time().'_'.$request->partisipan_id .'.'. $extension;
                    $upload = $this->upload($file, $extension, $path, $filename);
                    $data['dokumen_file'] = $upload;
                }

                Dokumen::create($data);
                return response()->json(['status' => $data, 'message' => 'Berhasil']);
            }
        } catch (Exception $error) {
            return response()->json(['status' => false, 'message' => $error]);
        }
    }



































    public function upload($file, $extension, $path, $filename) {
        if (in_array($extension, ['jpg','jpeg','png'])) {
            $manager = new ImageManager(new Driver());
            $image_resize = $manager->read($file->path());
            // $image_resize->orientate();
            // $image_resize->resize(1280, 720, function ($constraint) {
            //     $constraint->upsize();
            //     $constraint->aspectRatio();
            // });
            $image_resize->save(public_path($path.$filename), 60);
        } elseif (in_array($extension, ['pdf'])) {
            $file->move(public_path($path), $filename);
        }
        return $filename;
    }

    public function generateUniqueCode() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (Sesi::where('sesi_kode', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;
    }

    public function generateCode($codeLength) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (Fisik::where('fisik_kode', $code)->exists()) {
            $this->generateCode($codeLength);
        }

        return $code;
    }





}
