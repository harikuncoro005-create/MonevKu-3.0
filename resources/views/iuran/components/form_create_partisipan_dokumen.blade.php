<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" name="partisipan_id" class="form-control" value="{{ $partisipan->partisipan_id }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Nama Dokumen <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="nama" name="nama" class="form-control">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Upload Dokumen (max:2mb)</div>
                <div>
                    <div class="input-group">
                        <div class="custom-file">
                            <input id="dokumen" type="file" name="dokumen" class="custom-file-input form-control-sm dokumen-change" accept=".jpg,.jpeg,.png,.pdf">
                            <label class="custom-file-label col-form-label-sm rounded-right" style="overflow: hidden">Choose file ( JPG, JPEG, PNG, PDF )</label>
                        </div>
                    </div>
                    <small id="dokumen_error" class="text-danger"></small>
                    <button class="d-none w-100 text-center btn btn-sm rounded mt-2 text-small text-nowrap text-gray-400 bg-gray-200 hover-bg-gray-200 btn-del">Hapus</button>
                </div>
            </div>
        </div>
        <br>
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

        var ext = /(\.jpg|\.jpeg|\.png|\.pdf)$/i;

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
                console.log(res)
                if(!res.status) {
                    $('.btn-save').removeAttr('disabled', true);
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