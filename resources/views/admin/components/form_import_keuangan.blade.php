<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div id="modal-view">
            <div style="line-height: 1">
                <input type="hidden" class="form-control" name="penilaian_rencana_id" value="">
                <div class="my-2">
                    <div class="text-gray-400 text-small">Sesi <small class="text-danger">*</small></div>
                    <div>
                        <select name="sesi" id="sesi" class="form-control">
                            @foreach ($sesi as $item)
                                <option value="{{ $item->sesi_kode }}" {{ $item->sesi_status ? 'selected' : '' }}>{{ $item->sesi_nama.' - '.$item->sesi_tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Jenis <small class="text-danger">*</small></div>
                    <div>
                        <select name="jenis" id="jenis" class="form-control">
                            <option value="" hidden>Pilih Jenis</option>
                            <option value="0">Target</option>
                            <option value="1">Realisasi</option>
                        </select>
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Upload Dokumen</div>
                    <div class="input-group">
                        <div class="custom-file">
                            <input id="dokumen" type="file" name="dokumen" class="custom-file-input form-control-sm dokumen-change" accept=".xlsx,.xls">
                            <label class="custom-file-label col-form-label-sm rounded-right" style="overflow: hidden;">Choose file ( Excel )</label>
                        </div>
                    </div>
                    <small id="dokumen_error" class="text-danger"></small>
                    <button class="d-none w-100 text-center btn btn-sm rounded mt-2 text-small text-nowrap text-gray-400 bg-gray-200 hover-bg-gray-200 btn-del">Hapus</button>
                    <div class="progress mt-2 d-none">
                        <div id="progress-bar" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
            </div>
            <br>
            <div id="message_error" class="text-small"></div>
            <div class="">
                <button type="submit" class="btn btn-block btn-blue-500 px-3 rounded btn-save">Simpan</button>
                <button type="button" class="btn btn-block btn-gray-300 px-3 rounded" class="close" data-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('.dokumen-change').on('change', function(e) {
        e.preventDefault();
        $('#dokumen_error').html('');

        var output = document.getElementById('dokumen')
        output.src = URL.createObjectURL(e.target.files[0])

        var fileName = $(this).val().split("\\").pop();
        var filePath = $(this).closest('.custom-file-input').val();

        var ext = /(\.xlsx|\.xls)$/i;

        if (!ext.exec(filePath)) {
            $(this).closest(".custom-file-input").addClass('is-invalid').val('')
            $(this).siblings(".custom-file-label").addClass("selected").html('File Tidak Valid');
            $('.btn-del').addClass('d-none')
        } else {
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            $(this).closest(".custom-file-input").removeClass('is-invalid')
            $('.btn-del').removeClass('d-none')
        }
    })

    $('.btn-del').off('click').on('click', function(e) {
        e.preventDefault()
        $('.dokumen-change').val('')
        $('.dokumen-change').siblings(".custom-file-label").addClass("selected").html('Choose file ( Excel )');
        $('.btn-del').addClass('d-none')
    })
</script>

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

<script type="text/javascript">
    $(document).ready(function(){
        autosize($('textarea'))
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeYear: true
        });
        $('input,textarea').on('keyup', function() {
            $(this).removeClass('is-invalid');
        });
        $('input,select').on('change', function() {
            $(this).removeClass('is-invalid');
        });
        $('.number').mask("000.000.000.000", {reverse: true});
    })
</script>