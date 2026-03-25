<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div id="modal-view">
            <div style="line-height: 1">
                <div class="my-2">
                    <div class="text-gray-400 text-small">Sesi<small class="text-danger">*</small></div>
                    <div>
                        <select name="sesi" id="sesi" class="form-control">
                            @foreach ($sesi as $item)
                                <option value="{{ $item->sesi_kode }}" {{ $item->sesi_status ? 'selected' : '' }}>{{ $item->sesi_nama.' - '.$item->sesi_tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <div class="p-2 alert-danger text-small text-center rounded"><i class="fa-solid fa-triangle-exclamation"></i> Semua Data Sesi Akan Dihapus</div>
                </div>
                <div class="progress mt-2 d-none">
                    <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
            </div>
            <br>
            <div id="message_error" class="text-small"></div>
            <div class="">
                <button type="submit" class="btn btn-block btn-blue-500 px-3 rounded btn-save">Hapus</button>
                <button type="button" class="btn btn-block btn-gray-300 px-3 rounded" class="close" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function updateProgress(percentage) {
        var progressBar = $('#progress-bar');
        progressBar.css('width', percentage + '%');
        progressBar.attr('aria-valuenow', percentage);
        progressBar.text(percentage + '%');
    }

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
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                        updateProgress(percentComplete);
                    }
                }, false);
                return xhr;
            },
            beforeSend: function() {
                $('.btn-save').attr('disabled', true);
                $('.progress').removeClass('d-none');
                updateProgress(0);
            },
            success:function(res) {
                console.log(res)
                if(!res.status) {
                    $('.btn-save').removeAttr('disabled', true);
                    $('.progress').addClass('d-none');
                    updateProgress(0);
                    if (res.message.message) {
                        $('#message_error').html('<div class="alert-danger py-2 mb-2 text-center rounded">'+res.message.message+'</div>')
                    } else {
                        $('#message_error').html('')
                    }

                    $.each(res.message, function(i, item) {
                        $('#'+i).addClass('is-invalid')
                    }); 
                }

                if(res.status) {
                    updateProgress(100);
                    setTimeout(function() {
                        $('#modal-view').html(res.html)
                        $('.btn-close').off('click').on('click', function () {
                            $('#modal-form').modal('hide')
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
                                    $('#view-content').html('<tr><td colspan="24"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                                    $.ytLoad('start')
                                },
                                success:function(res) {
                                    $('#view-content').html(res.html)
                                    $('#pagination').html(res.pagination)
                                    $.ytLoad('complete')
                                }
                            })
                        });
                    }, 1000)
                }  
            }
        })
    })
</script>