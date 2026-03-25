@extends('layout.layout_main')

@section('admin_content')

<style>
    :root {
    --border-width: 0px;
    --border-color: #f9fafb;
    --border: var(--border-width) solid var(--border-color);
    }

    /* Sticky header */
    table {
      border-collapse: separate;
      border-spacing: var(--border-width); /* 1 */
    }

    thead {
      position: sticky;
      top: var(--border-width); /* 2 */
    }

    thead tr td, tbody tr td {
        vertical-align: middle !important;
    }

    th, td {
      box-shadow: 0 0 0 var(--border-width) var(--border-color); /* 3 */
    }
</style>

<section>
    <div class="container">
        <div class="border-bottom border-dark py-7 mt-5">
            <div class="d-flex justify-content-center" style="column-gap: 1rem">
                <div class="w-100">
                    <input class="form-control form-control-sm mb-2 border fs-1 px-4" id="text-search" type="text" value="{{ Request::get('q') }}" placeholder="Pencarian" />
                </div>
                <div class="">
                    <button class="btn btn-sm btn-success text-dark fs-1 btn-search" param="q">Cari</button>
                </div>
			</div>

            <div class="lg-pt-6 pt-2">
                <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
                    <table class="table table-hover">
                            <thead class="bg-dark text-white" style="font-weight: 500; font-size: 0.9rem; line-height: 1rem;">
                                <tr class="text-center">
                                    <td style="vertical-align: middle; width: 2rem;">NO</td>
                                    <td style="vertical-align: middle;">NAMA</td>
                                    <td style="vertical-align: middle; width: 25rem;">ALAMAT</td>
                                    <td style="vertical-align: middle; width: 10rem;">JUMLAH</td>
                                </tr>
                        </thead>
                        <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
    
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
            url: "/view-sumbangan",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<tr><td colspan="4"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                $.ytLoad('start')
            },
            success:function(res) {
                console.log(res)
                $('#view-content').html(res.html)
                $.ytLoad('complete')
            }
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
                url: "/view-sumbangan",
                method: "POST",
                data: {parameter: parameter},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $.ytLoad('start');
                },
                success:function(res) {
                    $('#view-content').html(res.html)
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
            url: "/view-sumbangan",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $.ytLoad('complete')
            }
        })
    });
</script>



@endsection