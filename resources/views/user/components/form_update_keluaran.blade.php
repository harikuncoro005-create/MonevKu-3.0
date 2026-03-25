<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" class="form-control" name="keluaran_id" value="{{ $keluaran->keluaran_id }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Subkegiatan</div>
                <div>
                    <textarea class="form-control" readonly>{{ $nomenklatur->nomenklatur_kode.' - '.$nomenklatur->nomenklatur_nama }}</textarea>
                </div>
            </div>
            <hr>
            @if ($kode == 1)
                <div class="my-2">
                    <div class="text-gray-400 text-small">Keluaran <small class="text-danger">*</small></div>
                    <div>
                        <textarea id="keluaran" name="keluaran" class="form-control" readonly>{{ $nomenklatur->nomenklatur_indikator_keluaran }}</textarea>
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Target <small class="text-danger">*</small></div>
                    <div>
                        <input type="text" id="target" name="target" class="form-control" value="{{ $keluaran->keluaran_target }}">
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Satuan <small class="text-danger">*</small></div>
                    <div>
                        <input type="text" id="satuan" name="satuan" class="form-control" value="{{ $nomenklatur->nomenklatur_satuan_keluaran }}"  readonly>
                    </div>
                </div>
            @else
                <div class="my-2">
                    <div class="text-gray-400 text-small">Keluaran <small class="text-danger">*</small></div>
                    <div>
                        <textarea id="keluaran" name="keluaran" class="form-control">{{ $keluaran->keluaran_nama }}</textarea>
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Target <small class="text-danger">*</small></div>
                    <div>
                        <input type="text" id="target" name="target" class="form-control" value="{{ $keluaran->keluaran_target }}">
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Satuan <small class="text-danger">*</small></div>
                    <div>
                        <input type="text" id="satuan" name="satuan" class="form-control" value="{{ $keluaran->keluaran_satuan }}">
                    </div>
                </div>
            @endif
        </div>
        <br>
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