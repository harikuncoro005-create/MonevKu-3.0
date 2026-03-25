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

<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" name="session" class="form-control" value="{{ $session }}">
            <div class="my-2">
                <div>
                    <select id="{{ $session }}" name="{{ $session }}" class="form-control select2">
                        <option value="" hidden></option>

                        @if ($session == 'instansi')
                            @foreach ($result as $item)
                                <option value="{{ $item->instansi_kode }}" {{ session('session_instansi') == $item->instansi_kode ? 'selected' : '' }}>{{ $item->instansi_nama }}</option>
                            @endforeach
                        @endif

                        @if ($session == 'sesi')
                            @foreach ($result as $item)
                                <option value="{{ $item->sesi_kode }}" {{ session('session_kode')->sesi_kode == $item->sesi_kode ? 'selected' : '' }}>{{ $item->sesi_nama }}</option>
                            @endforeach
                        @endif
                       
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div id="message_error" class="text-small"></div>
        <div class="">
            <button type="submit" class="btn btn-block btn-blue-500 px-3 rounded btn-save">Pilih</button>
            <button type="button" class="btn btn-block btn-gray-300 px-3 rounded" class="close" data-dismiss="modal">Batal</button>
        </div>
    </div>
</form>

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
                if(!res.status) {
                    $('.btn-save').removeAttr('disabled', true);
                    if (res.message.message) {
                        $('#message_error').html('<div class="alert-danger py-2 mb-2 text-center rounded">'+res.message.message+'</div>')
                    } else {
                        $('#message_error').html('')
                    } 
                }

                if(res.status) {
                    setTimeout(function() {
                        location.reload()
                    }, 1000)
                }  
            }
        })
    })
</script>



<script type="text/javascript">
    $(document).ready(function(){
        $('.select2').select2({
            placeholder: "Pilih {{ $session == 'instansi' ? 'Perangkat Daerah' : ucfirst($session) }}",
            width: '100%',
        });
    })
</script>