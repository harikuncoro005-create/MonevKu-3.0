@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 5;
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
                <a href="/panel/anggaran-kas" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
        <div class="">
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
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div style="width:20rem;">
            <select id="instansi" class="form-control search" param="pd">
                <option value="" hidden></option>
                @foreach ($instansi as $item)
                    <option value="{{ $item->instansi_kode }}" {{ Request::get('pd') == $item->instansi_kode ? 'selected' : '' }}>{{ $item->instansi_nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
    
    <div id="view-content" style="line-height: 1;">

    </div>
    {{-- <div class="card-body border">
        <div class="text-center text-gray-400 text-small">Silahkan Pilih Nama Perangkat Daerah</div>
    </div> --}}
    {{-- <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td rowspan="2" style="vertical-align: middle; min-width: 5rem;">KODE</td>
                        <td rowspan="2" style="vertical-align: middle; min-width: 20rem;">NAMA URUSAN /PROGRAM / KEGIATAN / SUBKEGIATAN</td>
                        <td rowspan="2" style="vertical-align: middle; min-width: 10rem;">PAGU</td>
                        <td colspan="3" style="vertical-align: middle;">TRIWULAN I</td>
                        <td colspan="3" style="vertical-align: middle;">TRIWULAN II</td>
                        <td colspan="3" style="vertical-align: middle;">TRIWULAN III</td>
                        <td colspan="3" style="vertical-align: middle;">TRIWULAN IV</td>
                    </tr>
                    <tr class="text-center">
                        <td style="vertical-align: middle; min-width: 8rem;">JANUARI</td>
                        <td style="vertical-align: middle; min-width: 8rem;">FEBRUARI</td>
                        <td style="vertical-align: middle; min-width: 8rem;">MARET</td>
                        <td style="vertical-align: middle; min-width: 8rem;">APRIL</td>
                        <td style="vertical-align: middle; min-width: 8rem;">MEI</td>
                        <td style="vertical-align: middle; min-width: 8rem;">JUNI</td>
                        <td style="vertical-align: middle; min-width: 8rem;">JULI</td>
                        <td style="vertical-align: middle; min-width: 8rem;">AGUSTUS</td>
                        <td style="vertical-align: middle; min-width: 8rem;">SEPTEMBER</td>
                        <td style="vertical-align: middle; min-width: 8rem;">OKTOBER</td>
                        <td style="vertical-align: middle; min-width: 8rem;">NOVEMBER</td>
                        <td style="vertical-align: middle; min-width: 8rem;">DESEMBER</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div> --}}
</div>

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
                    $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                    $.ytLoad('start')
                },
                success:function(res) {
                    console.log(res)
                    setTimeout(function() {
                        $('#view-content').html(res.html)
                    }, 500)
                    $.ytLoad('complete')
                }
            })
        });
    });
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

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                $.ytLoad('start')
            },
            success:function(res) {
                console.log(res)
                $('#view-content').html(res.html)
                $.ytLoad('complete')
            }
        })

    });
</script>

@endsection