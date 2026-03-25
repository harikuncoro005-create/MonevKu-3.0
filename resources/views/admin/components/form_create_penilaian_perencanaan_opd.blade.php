<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" class="form-control" name="penilaian_rencana_id" value="{{ $penilaian_rencana_id }}">
            <input type="hidden" class="form-control" name="instansi_kode" value="{{ $instansi_kode }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Tanggal Diterima <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="tanggal" name="tanggal" class="form-control bg-white datepicker" readonly>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Upload Dokumen (max:1mb)</div>
                <div class="input-group">
                    <div class="custom-file">
                        <input id="dokumen" type="file" name="dokumen" class="custom-file-input form-control-sm dokumen-change" accept=".jpg,.jpeg,.png">
                        <label class="custom-file-label col-form-label-sm rounded-right" style="overflow: hidden;">Choose file ( PNG, JPG, JPEG )</label>
                    </div>
                </div>
                <small id="dokumen_error" class="text-danger"></small>
                <button class="d-none w-100 text-center btn btn-sm rounded mt-2 text-small text-nowrap text-gray-400 bg-gray-200 hover-bg-gray-200 btn-del">Hapus</button>
            </div>
        </div>
        <br>
        <div id="message_error" class="text-small"></div>
        <div class="">
            <button type="submit" class="btn btn-block btn-blue-500 px-3 rounded btn-save">Simpan</button>
            <button type="button" class="btn btn-block btn-gray-300 px-3 rounded" class="close" data-dismiss="modal">Batal</button>
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

        var ext = /(\.jpg|\.jpeg|\.png)$/i;

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
        $('.dokumen-change').siblings(".custom-file-label").addClass("selected").html('Choose file ( JPG, JPEG, PNG, PDF )');
        $('.btn-del').addClass('d-none')
    })
</script>

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

                    $.each(res.message, function(i, item) {
                        $('#'+i).addClass('is-invalid')
                    }); 
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