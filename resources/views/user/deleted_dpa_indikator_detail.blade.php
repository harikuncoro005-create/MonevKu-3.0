@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/dpa/indikator" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="w-100">
                <a href="#" do="create" title="Tambah {{ $titlePage }}" class="btn px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add"><i class="fa-solid fa-plus"></i> Tambah</a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="float-right">
        <div class="">
            <a href="" class="text-gray-400 shadow-sm hover-blue-500 px-2 py-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bars-staggered"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" data-id="" do="update" title="Ubah Warga" class="dropdown-item text-blue-500 btn-edit-warga">Ubah</a>
                <a href="#" data-id="" do="warga" class="dropdown-item text-blue-500 btn-delete-warga">Hapus</a>
            </div>
        </div>
    </div>
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
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table">
                <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">NO</td>
                        <td style="vertical-align: middle; width: 5rem;">KODE</td>
                        <td class="text-left" style="vertical-align: middle;">KELUARAN</td>
                        <td style="vertical-align: middle; width: 5rem;">TRAGET</td>
                        <td style="vertical-align: middle; width: 5rem;">SATUAN</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">1.1</td>
                        <td class="text-left" style="vertical-align: middle;">Jumlah Laporan Hasil Diklat Keistimewaan Kabupaten/Kota yang Diselenggarakan</td>
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">Laporan</td>
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
                <span>AKTIVITAS</span>
            </div>
            <div>
                <button class="btn btn-sm btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">NO</td>
                        <td style="vertical-align: middle; width: 5rem;">AKTIVITAS</td>
                        <td style="vertical-align: middle;">BENTUK KEGIATAN</td>
                        <td style="vertical-align: middle; width: 5rem;">TRAGET</td>
                        <td style="vertical-align: middle; width: 5rem;">SATUAN</td>
                        <td style="vertical-align: middle">DESKRIPSI</td>
                        <td style="vertical-align: middle; width: 5rem;">ANGGARAN</td>
                        <td style="vertical-align: middle; width: 5rem;">LOKASI</td>
                        <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">Lorem ipsum dolor sit amet.</td>
                        <td class="text-left" style="vertical-align: middle;">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit necessitatibus harum impedit.</td>
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">Dokumen</td>
                        <td style="vertical-align: middle">Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni porro vitae quibusdam quo, vero assumenda ea. At quo repudiandae eos architecto ea maiores quibusdam quos, corporis recusandae hic explicabo. Nisi?</td>
                        <td style="vertical-align: middle">12.000.000</td>
                        <td style="vertical-align: middle">Kabupaten Kulon Progo</td>
                        <td style="vertical-align: middle; width: 5rem;">
                            <a href="/dpa/indikator-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="/dpa/indikator-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 5rem;">2</td>
                        <td style="vertical-align: middle; width: 5rem;">Lorem ipsum dolor sit amet.</td>
                        <td class="text-left" style="vertical-align: middle;">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Velit necessitatibus harum impedit.</td>
                        <td style="vertical-align: middle; width: 5rem;">1</td>
                        <td style="vertical-align: middle; width: 5rem;">Dokumen</td>
                        <td style="vertical-align: middle">Lorem ipsum dolor sit amet consectetur adipisicing elit. Magni porro vitae quibusdam quo, vero assumenda ea. At quo repudiandae eos architecto ea maiores quibusdam quos, corporis recusandae hic explicabo. Nisi?</td>
                        <td style="vertical-align: middle">8.000.000</td>
                        <td style="vertical-align: middle">Kabupaten Kulon Progo</td>
                        <td style="vertical-align: middle; width: 5rem;">
                            <a href="/dpa/indikator-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="/dpa/indikator-detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </td>
                    </tr>
              </tbody>
        </table>
    </div>
</div>

@endsection