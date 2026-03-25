@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/dpa" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="w-100">
                <a href="#" do="create" title="Tambah {{ $titlePage }}" class="btn px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add"><i class="fa-solid fa-plus"></i> Tambah</a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-sm table-borderless">
                <tbody class="text-small text-gray-500" style="line-height: 1rem;">
                    <tr class="text-nowrap">
                        <td style="vertical-align: middle; width:10rem">PROGRAM</td>
                        <td style="vertical-align: middle; width:10rem">1.01.02</td>
                        <td style="vertical-align: middle">PROGRAM PENGELOLAAN</td>
                    </tr>
                    <tr class="text-nowrap">
                        <td style="vertical-align: middle; width:10rem">KEGIATAN</td>
                        <td style="vertical-align: middle; width:10rem">1.01.02.2.01</td>
                        <td style="vertical-align: middle">Pengelolaan Pendidikan Sekolah</td>
                    </tr>
                    <tr class="text-nowrap">
                        <td style="vertical-align: middle; width:10rem">SUB KEGIATAN</td>
                        <td style="vertical-align: middle; width:10rem">1.01.02.2.01.0025</td>
                        <td style="vertical-align: middle">Pembinaan Minat, Bakat dan Kreativitas Siswa</td>
                    </tr>
                </tbody>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
    <br>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">NO</td>
                        <td style="vertical-align: middle; width: 5rem;">KODE</td>
                        <td style="vertical-align: middle;">KELUARAN</td>
                        <td style="vertical-align: middle; width: 5rem;">TRAGET</td>
                        <td style="vertical-align: middle; width: 5rem;">SATUAN</td>
                        <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">1.1</td>
                        <td class="text-left" style="vertical-align: middle;">Jumlah Laporan Hasil Diklat Keistimewaan Kabupaten/Kota yang Diselenggarakan</td>
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">Laporan</td>
                        <td style="vertical-align: middle; width: 5rem;">
                            <a href="/dpa/indikator-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">2</td>
                        <td style="vertical-align: middle; width: 5rem;">1.2</td>
                        <td class="text-left" style="vertical-align: middle;">Jumlah Laporan Hasil Diklat Keistimewaan Kabupaten/Kota yang Diselenggarakan</td>
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">Laporan</td>
                        <td style="vertical-align: middle; width: 5rem;">
                            <a href="/dpa/indikator-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                        </td>
                    </tr>
              </tbody>
        </table>
    </div>
</div>


@endsection