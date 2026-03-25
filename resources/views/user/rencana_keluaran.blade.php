@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div id="view-content" style="line-height: 1;">
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
            url: "{{ $url_view }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<div class="card-body border rounded bg-white d-flex flex-column align-items-center justify-content-center p-5"><div class="modern-spinner mb-3"></div><div class="text-muted font-weight-bold" style="font-size: 0.85rem; letter-spacing: 1px;"> MEMUAT DATA...</div></div>')
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