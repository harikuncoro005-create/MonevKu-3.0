<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" class="form-control" name="fisik_id" value="{{ $rencana_fisik->fisik_id }}">
            <input type="hidden" class="form-control" name="bulan" value="{{ $bulan }}">

            <div class="my-2">
                <div class="text-gray-400 text-small">Aktivitas</div>
                <div>
                    <textarea class="form-control" readonly>{{ $rencana_fisik->fisik_aktivitas }}</textarea>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Acuan</div>
                <div>
                    <input type="number" id="acuan" name="acuan" class="form-control" readonly value="{{ $rencana_fisik->fisik_acuan }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Realisasi <small class="text-danger">*</small></div>
                <div>
                    <input type="number" id="realisasi" name="realisasi" class="form-control" value="">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Lampiran <small class="text-danger">*</small></div>
                <div class="mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe" id="inlineRadio1" value="1">
                        <label class="form-check-label text-gray-400" for="inlineRadio1">File</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipe" id="inlineRadio2" value="2">
                        <label class="form-check-label text-gray-400" for="inlineRadio2">Link</label>
                        </div>
                    <div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('input[type="radio"][name="tipe"]').change(function() {
                            var tipe = $(this).val();
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url: '/input-attachment',
                                method: 'POST',
                                data: { tipe: tipe },
                                async: true,
                                dataType:"json",
                                beforeSend: function() {
                                    $('#input-attachment').html('<div class="text-center text-gray-400 text-small p-4 rounded border"><i class="fas fa-spinner fa-spin"></i> Loading</div>');
                                },
                                success: function(res) {
                                    $('#input-attachment').html(res.html);
                                }
                            });
                        });
                    });
                </script>
                <div id="input-attachment" class="mt-2">
                    <div class="text-center text-gray-400 text-small p-4 rounded border">Silihakan Pilih Lampiran</div>
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