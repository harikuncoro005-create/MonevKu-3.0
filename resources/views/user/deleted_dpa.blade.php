@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div style="width:20rem;">
            <select class="form-control search" param="id">
               <option value="">Sub Kegiatan 1</option>
               <option value="">Sub Kegiatan 2</option>
            </select>
        </div>
    </div>
    <br>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-bordered">
                <tbody class="text-small text-gray-500" style="line-height: 1rem;">
                    <tr>
                        <td style="vertical-align: middle; width:10rem">PROGRAM</td>
                        <td style="vertical-align: middle; width:10rem">1.01.02</td>
                        <td style="vertical-align: middle">PROGRAM PENGELOLAAN</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; width:10rem">KEGIATAN</td>
                        <td style="vertical-align: middle; width:10rem">1.01.02.2.01</td>
                        <td style="vertical-align: middle">Pengelolaan Pendidikan Sekolah</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; width:10rem">SUB KEGIATAN</td>
                        <td style="vertical-align: middle; width:10rem">1.01.02.2.01.0025</td>
                        <td style="vertical-align: middle">Pembinaan Minat, Bakat dan Kreativitas Siswa</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="vertical-align: middle; width:20rem">PAGU</td>
                        <td style="vertical-align: middle">1.324.231.900</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="vertical-align: middle">
                            <a href="/dpa/indikator" class="btn btn-sm btn-info px-3 py-1">Indikator</a>
                        </td>
                    </tr>
                </tbody>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
</div>

@endsection