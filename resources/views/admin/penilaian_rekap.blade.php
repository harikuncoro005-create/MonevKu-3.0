@extends('layout.layout_admin')

@section('main_content')

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
                <a href="/panel/penilaian" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>PENILAIAN REKAP</span>
            </div>
        </div>
    </div>
    <div class="mb-2 d-flex justify-content-start align-items-center flex-row" style="column-gap: 0.5rem;">
        <div style="width:10rem;">
            <select id="bulan" class="form-control search" param="bulan">
                @foreach ($bulan as $index => $item)
                    <option value="{{ $index }}" {{ $bulan_index == $index ? 'selected' : '' }}>{{ $item }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div id="view-content" style="line-height: 1;"></div>
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


        $("#bulan").select2({ 
            width: '100%',
            placeholder: 'Pilih Instansi',
            // dropdownParent: $('#modal-form')
        }).on('change', function (e) {
            $.ytLoad({
                registerAjaxHandlers: false
            });

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
                    $('#view-content').html('<div class="card-body border mt-3"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                    $.ytLoad('start')
                },
                success:function(res) {
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
                $('#view-content').html('<div class="card-body border mt-3"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
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