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
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>PERMISSION</span>
            </div>
        </div>
    </div>
    <form id="form-add" autocomplete="off">
    <input type="hidden" class="form-control" name="tahun" value="{{ $tahun }}">
    <div id="view-content" style="line-height: 1;"></div>
    <div class="mt-3 d-flex justify-content-end">
        <button role="submit" class="btn btn-blue-500 px-4 btn-save" disabled>Simpan</button>
    </div>
    </form>
</div>

<script type="text/javascript">
    $('.btn-save').off('click').on('click', function(e) {
        e.preventDefault()
        var form = $('#form-add')[0];
        var formData = new FormData(form);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_post }}",
            method:"POST",
            data: formData,
            processData:false,
            contentType:false,
            cache:false,
            dataType:"json",
            beforeSend: function() {
                $('.btn-save').attr('disabled', true);
            },
            success:function(res) {
                // console.log(res)
                // $('.btn-save').removeAttr('disabled', true);
                setTimeout(function() {
                    $.ytLoad({
                        registerAjaxHandlers: false
                    });

                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: "{{ $url_view }}",
                        method: "POST",
                        async:true,
                        dataType:"json",
                        beforeSend: function() {
                            $('#view-content').html('<div class="card-body border mt-3"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                            $.ytLoad('start')
                        },
                        success:function(res) {
                            $('#view-content').html(res.html)
                            $.ytLoad('complete')
                            $('.btn-save').removeAttr('disabled', true);
                        }
                    })
                }, 1000)  
            }
        })
    })
</script>

<script type="text/javascript">
    $(document).ready(function(){
        $.ytLoad({
            registerAjaxHandlers: false
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_view }}",
            method: "POST",
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#view-content').html('<div class="card-body border mt-3"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                $.ytLoad('start')
            },
            success:function(res) {
                $('.btn-save').removeAttr('disabled', true);
                $('#view-content').html(res.html)
                $.ytLoad('complete')
            }
        })

    });
</script>

@endsection