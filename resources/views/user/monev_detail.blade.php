@extends('layout.layout_admin')

@section('main_content')

<style>
.sticky-col {
    position: -webkit-sticky;
    position: sticky;
    background-color: white !important; /* Agar tidak transparan saat ditimpa */
    z-index: 2;
}

/* Atur posisi horizontal masing-masing kolom */
/* Sesuaikan nilai 'left' dengan lebar kolom Anda */

.first-col {
    left: 0;
    z-index: 3; /* Lebih tinggi agar tidak tertutup kolom 2 */
}

.first-col-right {
    right: 0;
    min-width: 80px;
}

.second-col {
    left: 7.5rem; /* Contoh: jika lebar kolom pertama 100px */
}

.third-col {
    left: 22.5rem; /* Contoh: jika lebar kolom pertama + kedua = 200px */
}

/* Khusus Header agar tetap di atas saat scroll vertikal (opsional) */
thead th.sticky-col {
    z-index: 10;
    top: 0;
}
</style>

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/monev" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
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
                    <tr>
                        <td colspan="2" style="vertical-align: middle">PAGU</td>
                        <td style="vertical-align: middle">{{ str_replace(',','.', number_format($keuangan_target->keuangan_pagu )) }}</td>
                    </tr>
                </tbody>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
    <br>
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>PROGRESS</span>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-sm table-bordered">
            <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center">
                    <th class="sticky-col first-col" rowspan="3" style="vertical-align: middle; min-width: 5rem;">BULAN</td>
                    <th colspan="4" style="vertical-align: middle; min-width: 9rem;">TARGET</th>
                    <th colspan="4" style="vertical-align: middle; min-width: 9rem;">REALISASI</th>
                    <th colspan="4" style="vertical-align: middle; min-width: 9rem;">DEVIASI</th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 10rem;">PERMASALAHAN</th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 10rem;">TINDAK LANJUT</th>
                    <th rowspan="3" style="vertical-align: middle; min-width: 10rem;">CATATAN PENGAMPU</th>
                    <th class="sticky-col first-col-right" rowspan="3" style="vertical-align: middle; min-width: 5rem;">AKSI</th>
                </tr>
                <tr class="text-center">
                    <td rowspan="2" style="vertical-align: middle; min-width: 2.5rem;">Keluaran</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">Keuangan</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 2.5rem;">Fisik</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 2.5rem;">Keluaran</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">Keuangan</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 2.5rem;">Fisik</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 2.5rem;">Keluaran</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">Keuangan</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 2.5rem;">Fisik</td>
                </tr>
                <tr class="text-center">
                    <td style="vertical-align: middle; min-width: 4rem;"><em style="font-size: 0.75rem">Rp</em></td>
                    <td style="vertical-align: middle; min-width: 2rem;"><em style="font-size: 0.75rem">%</em></td>
                    <td style="vertical-align: middle; min-width: 4rem;"><em style="font-size: 0.75rem">Rp</em></td>
                    <td style="vertical-align: middle; min-width: 2rem;"><em style="font-size: 0.75rem">%</em></td>
                    <td style="vertical-align: middle; min-width: 4rem;"><em style="font-size: 0.75rem">Rp</em></td>
                    <td style="vertical-align: middle; min-width: 2rem;"><em style="font-size: 0.75rem">%</em></td>
                </tr>
            </thead>
            <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                @foreach ($bulan as $index => $item)
                <tr class="text-center small {{ \Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()) ? '' : 'bg-light' }}">
                    <td class="py-4 font-weight-bold px-2 sticky-col first-col" style="vertical-align: middle;">{{ strtoupper($item) }}</td>
                    <td>{{ $keluaran_kumulatif_target ? $keluaran_kumulatif_target[$index] : 0 }}</td>
                    <td class="py-4 text-right" style="vertical-align: middle;">{{ str_replace(',','.', number_format($keuangan_kumulatif_target[$index])) }}</td>
                    <td class="py-4 alert-warning" style="vertical-align: middle;">{{ $keuangan_presentase_kumulatif_target[$index] }}</td>
                    <td class="py-4" style="vertical-align: middle;">{{ str_replace(',','.', sprintf('%.2f',$fisik_kumulatif_target[$index])) }}</td>

                    <td>{{ $keluaran_kumulatif_realisasi ? $keluaran_kumulatif_realisasi[$index] : 0 }}</td>
                    <td class="py-4 text-right" style="vertical-align: middle;">{{ \Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()) ? str_replace(',','.', number_format($keuangan_kumulatif_realisasi ? $keuangan_kumulatif_realisasi[$index] : 0)) : '-' }}</td>
                    <td class="py-4 alert-warning" style="vertical-align: middle;">{{ \Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()) ? (isset($keuangan_presentase_kumulatif_realisasi[$index]) ? $keuangan_presentase_kumulatif_realisasi[$index] : '-') : '-' }}</td>
                    <td class="py-4" style="vertical-align: middle;">{{ str_replace(',','.', sprintf('%.2f', $fisik_kumulatif_realisasi ? $fisik_kumulatif_realisasi[$index] : 0)) }}</td>

                    <td>{{ ($keluaran_kumulatif_realisasi ? $keluaran_kumulatif_realisasi[$index] : 0)-($keluaran_kumulatif_target ? $keluaran_kumulatif_target[$index] : 0) }}</td>
                    <td class="py-4 text-right" style="vertical-align: middle;">{{ \Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()) ? str_replace(',','.', number_format((isset($keuangan_kumulatif_realisasi[$index]) ?? 0)-$keuangan_kumulatif_target[$index])) : '-' }}</td>
                    <td class="py-4" style="vertical-align: middle;">{{ \Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()) ? str_replace(',','.', sprintf('%.2f', (isset($keuangan_presentase_kumulatif_realisasi[$index]) ?? 0)-$keuangan_presentase_kumulatif_target[$index])) : '-' }}</td>
                    <td class="py-4" style="vertical-align: middle;">{{ str_replace(',','.', sprintf('%.2f',($fisik_kumulatif_realisasi ? $fisik_kumulatif_realisasi[$index] : 0)-$fisik_kumulatif_target[$index])) }}</td>
                    <td class="py-4 text-left" style="vertical-align: middle;">
                        <em>
                        @if(\Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()))
                            {!! $permasalahan->has($index) ? json_decode($permasalahan[$index]->permasalahan_deskripsi) : '-' !!}
                        @else
                            -
                        @endif
                        </em>
                    </td>
                    <td class="py-4 text-left" style="vertical-align: middle;">
                        <em>
                        @if(\Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()))
                            {!! $permasalahan->has($index) ? json_decode($permasalahan[$index]->permasalahan_tindaklanjut) : '-' !!}
                        @else
                            -
                        @endif
                        </em>
                    </td>
                    <td class="text-gray-300">
                        <em >
                        @if(\Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()))
                            {{ filled($permasalahan->get($index)?->permasalahan_catatan) ? $permasalahan->get($index)->permasalahan_catatan : 'Belum Ada Catatan' }}
                        @else
                            -
                        @endif
                        </em>
                    </td>
                    <td class="sticky-col first-col-right">
                        @if ( \Carbon\Carbon::create(session('session_tahun'), $index, 1)->startOfMonth()->lessThanOrEqualTo(\Carbon\Carbon::now()->startOfMonth()))
                        <div class="d-flex justify-content-center flex-column" style="row-gap:0.5rem">
                            <div>
                                <a title="Input Realisasi" href="/monev/realisasi?ref={{ Request::get('ref') }}&bulan={{ $index }}" class="text-nowrap text-decoration-none" style="font-size: 0.75rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="18" width="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                            </div>
                            {{-- <div><a href="/monev/realisasi-fisik?ref={{ Request::get('ref') }}&bulan={{ $index }}" class="d-inline-block w-100 bg-warning py-1 px-2 text-nowrap text-white rounded shadow-sm text-decoration-none" style="font-size: 0.75rem">Realisasi Fisik</a></div>
                            <div><a href="/monev/input-permasalahan?ref={{ Request::get('ref') }}&bulan={{ $index }}" class="d-inline-block w-100 bg-secondary py-1 px-2 text-nowrap text-white rounded shadow-sm text-decoration-none" style="font-size: 0.75rem">Input Permasalahan</a></div> --}}
                            {{-- <a href="" class="text-gray-400 px-2 py-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bars-staggered"></i></a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" data-id="" do="update" title="Ubah Warga" class="dropdown-item text-blue-500">Realisasi Keluaran</a>
                                <a href="#" data-id="" do="warga" class="dropdown-item text-blue-500 btn-delete-warga">Realisasi Fisik</a>
                                <a href="#" data-id="" do="warga" class="dropdown-item text-blue-500 btn-delete-warga">Input Permasalahan</a>
                            </div> --}}
                        </div> 
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection