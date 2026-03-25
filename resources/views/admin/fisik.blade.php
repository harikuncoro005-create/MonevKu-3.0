@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 22;
@endphp

<style type="text/css">
    .select2-container .select2-selection {
        height: 2.5rem;
        display: flex;
        align-items: center;
    }

    .select2-selection__rendered {
         margin: 0.25rem;
    }

    .select2-selection__arrow {
        margin: 0.30rem;
    }
</style>

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/panel" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="w-100">
                <a href="#" to="copy" title="Salin Data Keuangan" class="btn px-4 btn-info w-100 shadow-sm text-nowrap btn-add" url="{{ $url_form }}"><i class="fa-regular fa-copy"></i> Copy</a>
            </div>
            <div class="w-100">
                <a href="#" to="delete" title="Hapus Data Keuangan" class="btn px-4 btn-danger w-100 shadow-sm text-nowrap btn-add" url="{{ $url_form }}"><i class="fa-solid fa-trash-can"></i> Delete</a>
            </div>
            <div class="w-100">
                <a href="/panel/fisik/detail" title="Rekap Fisik OPD" class="btn px-4 text-blue-500 hover-blue-500 w-100 shadow-sm text-nowrap"><i class="fa-solid fa-chart-bar"></i> Rekap</a>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div class="input-group">
                <input type="text" id="text-search" class="form-control" value="{{ Request::get('q') }}" placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-blue-500 btn-search" param="q">Cari</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem; overflow-y:auto">
        <div class="d-flex  justify-content-start flex-row" style="column-gap: 0.5rem; overflow-y:auto">
            <div style="min-width:4rem">
                <select class="form-control search" param="limit">
                    <option value="" <?= !Request::get('limit') ? 'selected' : '' ?>>10</option>
                    <option value="50" <?= Request::get('limit') && Request::get('limit') == '50' ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= Request::get('limit') && Request::get('limit') == '100' ? 'selected' : '' ?>>100</option>
                    <option value="300" <?= Request::get('limit') && Request::get('limit') == '300' ? 'selected' : '' ?>>300</option>
                </select>
            </div>
            <div style="min-width:12rem">
                <select id="sesi" class="form-control search" param="sesi">
                    @foreach ($sesi as $item)
                        <option value="{{ $item->sesi_kode }}" {{ Request::get('sesi') && Request::get('sesi') == $item->sesi_kode ? 'selected' : ($item->sesi_kode == session('session_kode') ? 'selected' : '') }}>{{ $item->sesi_nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="d-flex align-items-center flex-row" style="column-gap: 0.5rem;">
            <div style="width:20rem;">
                <select id="instansi" class="form-control search" param="pd">
                    <option value="" {{ !Request::get('pd') ? 'selected' : '' }}>Semua</option>
                    @foreach ($instansi as $item)
                        <option value="{{ $item->instansi_kode }}" {{ Request::get('pd') && Request::get('pd') == $item->instansi_kode ? 'selected' : '' }}>{{ $item->instansi_nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 30rem; overflow-y: auto">
        <table class="table table-sm table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center small">
                        <td  style="vertical-align: middle; width: 0.1rem;">NO</td>
                        <td  style="vertical-align: middle; width: 20rem;">PERANGKAT DAERAH</td>
                        <td  style="vertical-align: middle;">SUB KEGIATAN</td>
                        <td  style="vertical-align: middle;">TAHAPAN</td>
                        <td  style="vertical-align: middle;">AKTIVITAS</td>
                        <td  style="vertical-align: middle;">ACUAN</td>
                        @foreach ($bulan as $index => $item)
                        <td class="text-center" style="vertical-align: middle; min-width:8rem">{{ strtoupper($item) }}</td>
                        @endforeach
                        <td  style="vertical-align: middle; width: 5rem;">SESI</td>
                        <td  style="vertical-align: middle; width: 5rem;">JENIS</td>
                        <td  style="vertical-align: middle; width: 5rem;">TAHUN</td>
                        <td  style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
    <div id="pagination"></div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#sesi").select2({ 
            width: '100%',
            placeholder: 'Pilih Sesi',
        })

        $.ytLoad({
            registerAjaxHandlers: false
        });

        var url = new URL($(location).attr('href'))

        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<tr><td colspan="{{ $colspan }}"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                $('#pagination').html('')
                $.ytLoad('start')
            },
            success:function(res) {
                console.log(res)
                $('#view-content').html(res.html)
                $('#pagination').html(res.pagination)
                $.ytLoad('complete')
            }
        })

    });
</script>







@endsection