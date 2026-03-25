@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 24;
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
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem; overflow-x: auto">
            <div>
                <a href="/panel" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <div class="w-100">
                <a href="#" to="import" title="Import Keuangan" class="btn px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add" url="{{ $url_form }}"><i class="fa-solid fa-cloud-arrow-up"></i> Import</a>
            </div>
            <div class="w-100">
                <a href="#" to="copy" title="Salin Data Keuangan" class="btn px-4 btn-info w-100 shadow-sm text-nowrap btn-add" url="{{ $url_form }}"><i class="fa-regular fa-copy"></i> Copy</a>
            </div>
            <div class="w-100">
                <a href="#" to="delete" title="Hapus Data Keuangan" class="btn px-4 btn-danger w-100 shadow-sm text-nowrap btn-add" url="{{ $url_form }}"><i class="fa-solid fa-trash-can"></i> Delete</a>
            </div>
        </div>
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div class="">
                <a href="" class="text-gray-500 shadow-sm hover-blue-500 px-2 py-2 rounded" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bars-staggered"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="/panel/anggaran-kas-pemda" class="dropdown-item text-blue-500">Rekap Pemerintah Daerah</a>
                    <a href="/panel/anggaran-kas-pd" class="dropdown-item text-blue-500">Rekap Perangkat Daerah</a>
                </div>
            </div>
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
        <div class="d-flex justify-content-start flex-row" style="column-gap: 0.5rem;">
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
            <div style="min-width:10rem">
                <select id="jenis" class="form-control search" param="jenis">
                    <option value="" hidden></option>
                    <option value="0" {{ Request::get('jenis') && Request::get('jenis') == '0' ? 'selected' : '' }}>Perencanaan</option>
                    <option value="1" {{ Request::get('jenis') && Request::get('jenis') == '0' ? 'selected' : '' }}>Realisasi</option>
                </select>
            </div>
        </div>
        <div class="d-flex align-items-center flex-row" style="column-gap: 0.5rem;">
            <div style="min-width:20rem;">
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
                        <th  style="vertical-align: middle; min-width: 2rem;">NO</th>
                        <th  style="vertical-align: middle; min-width: 6rem;">PERANGKAT DAERAH</th>
                        <th  style="vertical-align: middle; min-width: 6rem;">URUSAN</th>
                        <th  style="vertical-align: middle; min-width: 6rem;">BIDANG URUSAN</th>
                        <th  style="vertical-align: middle; min-width: 10rem;">PROGRAM</th>
                        <th  style="vertical-align: middle; min-width: 10rem;">KEGIATAN</th>
                        <th  style="vertical-align: middle; min-width: 10rem;">SUB KEGIATAN</th>
                        <th  style="vertical-align: middle; min-width: 4rem;">PAGU</th>
                        @foreach ($bulan as $index => $item)
                        <th class="text-center" style="vertical-align: middle; min-width: 4rem">{{ substr(strtoupper($item), 0, 3) }}</td>
                        @endforeach
                        <th  style="vertical-align: middle; min-width: 5rem;">SESI</td>
                        <th  style="vertical-align: middle; min-width: 5rem;">JENIS</td>
                        <th  style="vertical-align: middle; min-width: 5rem;">TAHUN</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
    <div id="pagination"></div>
</div>

<div id="modal-form" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-content"></div>
    </div>
</div>

<div id="modal-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;">
            <div class="modal-body">
                <div class="text-center text-gray-400 mb-3"><i class="fa-solid fa-trash-can fa-2xl"></i></div>
                <div class="text-center text-gray-500">Yakin Ingin Menghapus?</div>
                <br>
                <div class="d-flex flex-row justify-content-between align-items-center" style="column-gap: 0.5rem">
                    <button type="button" class="btn btn-block btn-rose-400 px-3 rounded-pill btn-del"><i class="fa-solid fa-check"></i> Delete</button>
                    <button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
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
                title: $(this).attr('title'),
                to: $(this).attr('to'),
            },
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#form-content').html('<div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading')
            },
            success:function(res) {
                setTimeout(function() {
                    $('#form-content').html(res.html)
                }, 500)
            }
        })
    })
</script>

<script type="text/javascript">
    $(document).ready(function(){
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


        $("#instansi").select2({ 
            width: '100%',
            placeholder: 'Pilih Instansi',
            // dropdownParent: $('#modal-form')
        }).on('change', function (e) {
            var url = new URL($(location).attr('href'));
            var param = $(this).attr('param')
            var search_params = url.searchParams;
            search_params.delete('page');
            delete parameter['page']

            if (search_params.has(param)) {
                if ($(this).val() == '') {
                    search_params.delete(param);
                    delete parameter[param]
                } else {
                    search_params.set(param, $(this).val());
                    parameter[param] = $(this).val()
                }
            } else {
                search_params.set(param, $(this).val());
                parameter[param] = $(this).val()
            }
            
            var new_url = url.toString();
            history.pushState({}, null, new_url);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_view }}",
                method:"POST",
                data: {
                    parameter: parameter
                },
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $('#view-content').html('<tr><td colspan="{{ $colspan }}"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                    $('#pagination').html('')
                    $.ytLoad('start')
                },
                success:function(res) {
                    console.log(res)
                    setTimeout(function() {
                        $('#view-content').html(res.html)
                        $('#pagination').html(res.pagination)
                    }, 500)
                    $.ytLoad('complete')
                }
            })
        });

        $('.search').on('change', function(){
            var url = new URL($(location).attr('href'));
            var param = $(this).attr('param')
            var search_params = url.searchParams;
            search_params.delete('page');
            delete parameter['page']

            if (search_params.has(param)) {
                if ($(this).val() == '') {
                    search_params.delete(param);
                    delete parameter[param]
                } else {
                    search_params.set(param, $(this).val());
                    parameter[param] = $(this).val()
                }
            } else {
                search_params.set(param, $(this).val());
                parameter[param] = $(this).val()
            }
            
            var new_url = url.toString();
            history.pushState({}, null, new_url);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_view }}",
                method: "POST",
                data: {parameter: parameter},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $.ytLoad('start');
                },
                success:function(res) {
                    $('#view-content').html(res.html)
                    $('#pagination').html(res.pagination)
                    $.ytLoad('complete')  
                }
            })
        })

        $('.btn-search').off('click').on('click', function(){
            var url = new URL($(location).attr('href'));
            var param = $(this).attr('param')
            var search_params = url.searchParams;
            search_params.delete('page');
            delete parameter['page']

            if (search_params.has(param)) {
                if ($('#text-search').val() == '') {
                    search_params.delete(param);
                    delete parameter[param]
                } else {
                    search_params.set(param, $('#text-search').val());
                    parameter[param] = $('#text-search').val()
                }
            } else {
                search_params.set(param, $('#text-search').val());
                parameter[param] = $('#text-search').val()
            }
            
            var new_url = url.toString();
            history.pushState({}, null, new_url);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_view }}",
                method: "POST",
                data: {parameter: parameter},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $.ytLoad('start');
                },
                success:function(res) {
                    $('#view-content').html(res.html)
                    $('#pagination').html(res.pagination)
                    $.ytLoad('complete')
                }
            })
        })
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $("#sesi").select2({ 
            width: '100%',
            placeholder: 'Pilih Sesi',
        })

        $("#jenis").select2({ 
            width: '100%',
            placeholder: 'Pilih Jenis'
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

<script type="text/javascript">
    $(window).on('popstate', function() {
        var url = new URL($(location).attr('href'));
        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        if(typeof parameter.q !== 'undefined' ) {
            $('#text-search').val(url.searchParams.get('q'))
        } else {
            $('#text-search').val('')
        }

        $.each( parameter, function( key, value ) {
            $('select[param="'+key+'"]').val(value)
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $('#pagination').html(res.pagination)
                $.ytLoad('complete')
            }
        })
    });
</script>


@endsection