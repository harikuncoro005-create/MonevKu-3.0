@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/panel" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table ">
            <tbody class="text-small text-gray-500" style="line-height: 1rem;">
                <tr class="text-nowrap">
                    <td style="vertical-align: middle;">PENILAIAN PERENCANAAN</td>
                    <td style="vertical-align: middle; width:10rem" class="text-center"><a href="/panel/penilaian-perencanaan" class="btn btn-sm btn-info">Progress</a></td>
                </tr>
                <tr class="text-nowrap">
                    <td style="vertical-align: middle;">PENILAIAN PELAPORAN</td>
                    <td style="vertical-align: middle; width:10rem" class="text-center"><a href="/panel/penilaian-pelaporan" class="btn btn-sm btn-warning">Progress</a></td>
                </tr>
                <tr class="text-nowrap">
                    <td style="vertical-align: middle;">PENILAIAN REKAP</td>
                    <td style="vertical-align: middle; width:10rem" class="text-center"><a href="/panel/penilaian-rekap" class="btn btn-sm btn-success">Progress</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection