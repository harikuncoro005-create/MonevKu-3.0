@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 4;
@endphp

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-column flex-sm-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div class="w-100">
                <a href="#" do="create" title="Tambah {{ $titlePage }}" class="btn px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add"><i class="fa-solid fa-plus"></i> Tambah</a>
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
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 20rem;">IURAN</td>
                        <td style="vertical-align: middle;">KETERANGAN</td>
                        <td style="vertical-align: middle; width: 5rem;">JUMLAH</td>
                        @can('administrator')
                        <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                        @endcan
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
            url: "{{ $url_form }}",
            method:"POST",
            data: {
                parameter: {
                    to: $(this).attr('do'),
                    title: $(this).attr('title'),
                    url: "{{ $url }}"
                }
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