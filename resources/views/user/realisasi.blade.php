@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/monev/detail?ref={{ Request::get('ref')}}" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-sm table-borderless">
                <tbody class="text-small text-gray-500" style="line-height: 1rem;">
                    @foreach ($ref_kode as $item)
                    <tr class="text-nowrap">
                        <td style="vertical-align: middle; width:10rem">{{ $item['kode_nama'] }}</td>
                        <td style="vertical-align: middle; width:10rem">{{ $item['kode_nomenklatur'] }}</td>
                        <td style="vertical-align: middle">{{ $nomenklatur[$item['kode_nomenklatur']]->nomenklatur_nama }}</td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
    <br>
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>KELUARAN</span>
            </div>
        </div>
    </div>
    @if ($keluaran_kepmen)
        <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
            <table class="table">
                <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">NO</td>
                        <td style="vertical-align: middle; width: 5rem;">KODE</td>
                        <td class="text-left" style="vertical-align: middle;">KELUARAN</td>
                        <td style="vertical-align: middle; width: 5rem;">TARGET</td>
                        <td style="vertical-align: middle; width: 5rem;">SATUAN</td>
                    </tr>
                </thead>
                <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_tipe }}.1</td>
                        <td class="text-left" style="vertical-align: middle;">{{ $nomenklatur[$keluaran_kepmen->keluaran_subkegiatan_kode]->nomenklatur_nama }}</td>
                        <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_target }}</td>
                        <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_satuan }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <br>
        <div class="d-flex justify-content-center alert-info py-2 font-weight-bold rounded">PROGRES SAMPAI DENGAN BULAN {{ strtoupper($bulan) }}</div>
        <br>
        <div class="my-2 text-gray-500">
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                    <span>REALISASI KELUARAN</span>
                </div>
            </div>
        </div>
        <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
            <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        {{-- <td style="vertical-align: middle; width: 5rem;">BULAN</td> --}}
                        <td style="vertical-align: middle; width: 10rem;">TARGET KUMULATIF</td>
                        <td style="vertical-align: middle; width: 10rem;">REALISASI KUMULATIF</td>
                        <td style="vertical-align: middle;">REALISASI</td>
                        <td style="vertical-align: middle;">BUKTI DUKUNG</td>
                        <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
                </thead>
                <tbody class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle;">{{ $rencana_keluaran_target[$bulan_index] }}</td>
                        <td style="vertical-align: middle;">{{ $rencana_keluaran_realisasi ? $rencana_keluaran_realisasi[$bulan_index] : 0 }}</td>
                        <td style="vertical-align: middle;">{{ $rencana_keluaran_realisasi_bulan ? $rencana_keluaran_realisasi_bulan->{'keluaran_'.$bulan_index} : 0 }}</td>
                        <td style="vertical-align: middle;">
                            @if ($lampiran_keluaran)
                                @if (($lampiran_keluaran->{'lampiran_'.$bulan_index}['tipe'] ?? null) == 1)
                                    <a href="#" class="btn-add" to="view" title="Detail Dokumen" data-id="{{ $lampiran_keluaran->lampiran_id }}" bulan="{{ Request::get('bulan') }}" url="{{ $url_form_keluaran }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    </a>
                                @elseif (($lampiran_keluaran->{'lampiran_'.$bulan_index}['tipe'] ?? null) == 2)
                                    <a href="{{ $lampiran_keluaran->{'lampiran_'.$bulan_index}['filename'] }}" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                    </svg>
                                    </a>
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                            
                        </td>
                        <td class="text-nowrap" style="vertical-align: middle;">
                            @if ($permission)
                            <a href="" bulan="{{ Request::get('bulan') }}" data-id="{{ $keluaran_kepmen->keluaran_kode }}" to="update" title="Ubah Realisasi Keluaran" class="btn-add" url="{{ $url_form_keluaran }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>  
                            @else
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>    
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br>
        <div class="my-2 text-gray-500">
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                    <span>REALISASI KEUANGAN</span>
                </div>
            </div>
        </div>
        <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
            <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 10rem;">TARGET KUMULATIF</td>
                        <td style="vertical-align: middle; width: 10rem;">REALISASI KUMULATIF</td>
                        <td style="vertical-align: middle; width:">REALISASI</td>
                    </tr>
                </thead>
                <tbody class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width:">{{ str_replace(',','.', number_format($keuangan_target[$bulan_index])) }}</td>
                        <td style="vertical-align: middle; width:">{{ str_replace(',','.', number_format($keuangan_realisasi[$bulan_index] ?? 0)) }}</td>
                        <td style="vertical-align: middle; width:">{{ str_replace(',','.', number_format($keuangan->{'keuangan_'.$bulan_index} ?? 0)) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>
        <div class="my-2 text-gray-500">
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                    <span>REALISASI FISIK</span>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
            <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 10rem;">TARGET KUMULATIF</td>
                        <td style="vertical-align: middle; width: 10rem;">REALISASI KUMULATIF</td>
                        <td style="vertical-align: middle; width:">REALISASI</td>
                    </tr>
                </thead>
                <tbody class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width:">{{ str_replace(',','.', number_format($fisik_target[$bulan_index])) }}</td>
                        <td style="vertical-align: middle; width:">{{ str_replace(',','.', number_format($fisik_realisasi[$bulan_index])) }}</td>
                        <td style="vertical-align: middle; width:">{{ str_replace(',','.', number_format($fisik_realisasi_bulan)) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if (count($fisik_target) != 0)
            <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
                <table class="table table-bordered">
                        <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                            <tr class="text-center">
                                <td style="vertical-align: middle; width: 5rem;">KODE</td>
                                <td style="vertical-align: middle;">AKTIVITAS KEGIATAN</td>
                                <td style="vertical-align: middle; width: 5rem;">ACUAN</td>
                                <td style="vertical-align: middle; width: 5rem;">REALISASI</td>
                                <td style="vertical-align: middle; width: 5rem;">BUKTI DUKUNG</td>
                                <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                            </tr>
                    </thead>
                    <tbody class="text-small text-gray-500" style="line-height: 1;">
                        @foreach ($tahapan as $key => $value)
                        <tr class="text-center font-weight-bold alert-warning">
                            <td class="text-left" style="vertical-align: middle; width: 5rem;">{{ $key }}</td>
                            <td class="text-left" style="vertical-align: middle;">{{ $value['nama'] }}</td>
                            <td colspan="4" style="vertical-align: middle;"></td>
                        </tr>
                        @if (count($value['data']) != 0)
                            @foreach ($value['data'] as $item)
                                <tr class="text-center">
                                    <td class="text-left" style="vertical-align: middle; width: 5rem;">{{ $key }}.{{ $item['fisik_nomor'] }}</td>
                                    <td class="text-left" style="vertical-align: middle;">{{ $item['fisik_aktivitas'] }}</td>
                                    <td style="vertical-align: middle;">{{ str_replace(".", ",", sprintf('%.2f', $item['fisik_acuan'])) }}</td>
                                    <td style="vertical-align: middle;">{{ str_replace(".", ",", sprintf('%.2f', data_get($fisik_realisasi_kode->get($item['fisik_kode']), 'fisik_'.$bulan_index, '0'))) }}</td>

                                    <td style="vertical-align: middle;">
                                    @if (data_get($fisik_realisasi_kode->get($item['fisik_kode']), "lampiran_fisik.lampiran_{$bulan_index}.tipe", '0') == 1)
                                        <a href="#" class="btn-add" to="view" title="Detail Dokumen" data-id="{{ data_get($fisik_realisasi_kode->get($item['fisik_kode']), "lampiran_fisik.lampiran_id", '') }}" bulan="{{ $bulan_index }}" url="{{ $url_form_fisik }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                        </a>

                                    @elseif (data_get($fisik_realisasi_kode->get($item['fisik_kode']), "lampiran_fisik.lampiran_{$bulan_index}.tipe", '0') == 2)
                                        <a href="{{ data_get($fisik_realisasi_kode->get($item['fisik_kode']), "lampiran_fisik.lampiran_{$bulan_index}.filename", '') }}" target="_blank">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                            </svg>
                                        </a>
                                    @else
                                    -
                                    @endif
                                    </td>

                                    
                                    <td class="text-nowrap d-flex justify-content-center" style="vertical-align: middle; width: 5rem; column-gap: 0.5rem">
                                        @if ($permission)
                                        <a href="#" bulan="{{ $bulan_index }}" data-id="{{ $item['fisik_kode'] }}" title="Ubah Realisasi Fisik" to="update" class="btn-add" url="{{ $url_form_fisik }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>  
                                        @else
                                        <div class="text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                            </svg>
                                        </div>    
                                        @endif
                                        
                                    </td>
                                    
                                </tr>
                            @endforeach
                        @else
                        <tr class="text-center">
                            <td colspan="16">-</td>
                        </tr>
                        @endif
                                
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu</div></div>
        @endif

        <br>
        <div class="my-2 text-gray-500">
            <div class="d-flex flex-row align-items-center justify-content-between">
                <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                    <span>PERMASALAHAN DAN TINDAK LANJUT</span>
                </div>
                @if (!$permasalahan)
                <div>
                    <button type="button" bulan="{{ $bulan_index }}" to="create" data-id="{{ Request::get('ref') }}" title="Tambah Permasalahan dan Tindak Lanjut" url="{{ $url_form_permasalahan }}" class="btn btn-sm btn-primary btn-add">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>
                @endif
            </div>
        </div>
        @if ($permasalahan)
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td style="width: 10rem">Permasalahan</td>
                        <td><div style="font-size:0.875rem; line-height:1; text-align: justify;">{!! json_decode($permasalahan->permasalahan_deskripsi) !!}</div></td>
                        <td rowspan="2" style="width: 5rem" class="text-center">
                            @if ($permission)
                            <a href="" bulan="{{ $bulan_index }}" to="update" data-id="{{ $permasalahan->permasalahan_id }}" title="Ubah Permasalahan dan Tindak Lanjut" url="{{ $url_form_permasalahan }}" class="btn-add">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>  
                            @else
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div>    
                            @endif
                            
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 10rem">Tindak Lanjut</td>
                        <td><div style="font-size:0.875rem; line-height:1; text-align: justify;">{!! json_decode($permasalahan->permasalahan_tindaklanjut) !!}</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        @else
        <div class="card-body border">
            <div class="text-center text-gray-400 text-small">Silahakan Input Permasalahan dan Tindak Lanjut</div>
        </div>
        @endif
    @else
        <div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu di Menu <strong><a href="/renja/indikator?ref={{ Request::get('ref')}}">RENJA</a></strong> dan <strong><a href="/rencana-keluaran/detail?ref={{ Request::get('ref')}}">Rencana Keluaran</a></strong></div></div>
    @endif
</div>

<div id="modal-form" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-content"></div>
    </div>
</div>

<script type="text/javascript">
    $('.btn-add').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('url'),
            method:"POST",
            data: {
                id: $(this).data('id'),
                title: $(this).attr('title'),
                to: $(this).attr('to'),
                bulan: $(this).attr('bulan')
            },
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#form-content').html('<div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading')
            },
            success:function(res) {
                console.log(res)
                setTimeout(function() {
                    $('#form-content').html(res.html)
                }, 500)
            }
        })
    })
</script>

@endsection