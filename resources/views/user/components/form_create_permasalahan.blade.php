<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" class="form-control" name="instansi_kode" value="{{ $instansi_kode }}">
            <input type="hidden" class="form-control" name="subkegiatan_kode" value="{{ $nomenklatur->nomenklatur_kode }}">
            <input type="hidden" class="form-control" name="bulan" value="{{ $bulan }}">
            <input type="hidden" class="form-control" name="tahun" value="{{ $tahun }}">
            <input type="hidden" class="form-control" name="sesi_kode" value="{{ $sesi_kode }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Permasalahan <small class="text-danger">*</small></div>
                <div>
                    <textarea id="permasalahan" name="permasalahan" class="form-control"></textarea>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Tindak Lanjut <small class="text-danger">*</small></div>
                <div>
                    <textarea id="tindaklanjut" name="tindaklanjut" class="form-control"></textarea>
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
</form>

<script type="text/javascript">
    $('.btn-save').off('click').on('click', function(e) {
        e.preventDefault()
        tinyMCE.triggerSave(); 
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
                    if (res.message.message) {
                        $('#message_error').html('<div class="alert-danger py-2 mb-2 text-center rounded">'+res.message.message+'</div>')
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
        tinymce.init({
            selector: 'textarea',
            plugins: "code autoresize lists",
            autoresize_bottom_margin: 10,
            branding: false,
            paste_as_text: true,
            relative_urls: false,
            statusbar: false,
            mobile: {
                menubar: true
            },
            toolbar: "outdent indent | numlist bullist", 
            init_instance_callback: function (editor) {
                editor.on('keyup', function (e) {
                    $('#'+$(this).attr('id')+'_error').html('');
                });
            }
        });
    })
</script>

<script src="{{ URL::asset('assets/plugins/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/tinymce/js/tinymce/themes/mobile/theme.min.js') }}"></script>