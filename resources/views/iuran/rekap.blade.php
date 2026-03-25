@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 6;
@endphp

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-column flex-sm-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div class="w-100">
                <a href="/iuran/partisipan?id={{ $data->iuran_id }}" class="btn rounded px-4 text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
        
        <div>
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem">
                <div class="w-100">
                    <div class="input-group">
                        <input type="text" id="text-search" class="form-control" value="{{ Request::get('q') }}" placeholder="Pencarian">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-blue-500 btn-search" param="q">Cari</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div style="min-width:4rem">
            <select class="form-control search" param="limit">
                <option value="" <?= !Request::get('limit') ? 'selected' : '' ?>>10</option>
                <option value="50" <?= Request::get('limit') && Request::get('limit') == '50' ? 'selected' : '' ?>>50</option>
                <option value="100" <?= Request::get('limit') && Request::get('limit') == '100' ? 'selected' : '' ?>>100</option>
                <option value="300" <?= Request::get('limit') && Request::get('limit') == '300' ? 'selected' : '' ?>>300</option>
            </select>
        </div>
        <div class="d-flex flex-row" style="column-gap: 0.5rem">
            <div style="min-width:8rem">
                <select class="form-control search" param="bulan">
                    <option value="" {{ !Request::get('bulan') ? 'selected' : '' }}></option>
                    @foreach ($bulan as $item)
                        <option value="{{ $item->bulan_id }}" {{ Request::get('bulan') && Request::get('bulan') ==  $item->bulan_id ? 'selected' : '' }}>{{ $item->bulan_nama }}</option>
                    @endforeach
                </select>
            </div>
            <div style="min-width:5rem">
                <select class="form-control search" param="tahun">
                    @foreach ($tahun as $item)
                        <option value="{{ $item }}" 
                        @php
                            if (Request::get('tahun') && Request::get('tahun') == $item) {
                                echo 'selected';
                            } else {
                                if (date('Y') == $item) {
                                    echo 'selected';
                                }
                            }   
                        @endphp                 
                        
                        >{{ $item }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 2rem;">NO</td>
                        <td style="vertical-align: middle;">NAMA</td>
                        <td style="vertical-align: middle; width: 15rem;">NOMOR RUMAH / ALAMAT</td>
                        <td style="vertical-align: middle; width: 5rem;">PEMBAYARAN</td>
                        <td style="vertical-align: middle; width: 5rem;">TANGGAL</td>
                        <td class="bg-light"  style="vertical-align: middle; width: 10rem; position: sticky; right: var(--border-width);">JUMLAH</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
    <div id="pagination"></div>
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

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<tr><td colspan="{{ $colspan }}"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $('#pagination').html(res.pagination)
                $.ytLoad('complete')
            }
        })

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
                url: "{{ $url }}",
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
                url: "{{ $url }}",
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
            url: "{{ $url }}",
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