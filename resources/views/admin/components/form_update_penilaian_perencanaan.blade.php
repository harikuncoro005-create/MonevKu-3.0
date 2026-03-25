<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" name="id" value="{{ $penilaian_perencanaan->penilaian_rencana_id}}"  class="form-control">
            <div class="my-2">
                <div class="text-gray-400 text-small">Penilaian Perencanaan Bulan <small class="text-danger">*</small></div>
                <div>
                    <input type="text" class="form-control bg-white" value="{{ $bulan_nama }}" readonly>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nama Dokumen<small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="nama" name="nama" class="form-control" value="{{ $penilaian_perencanaan->penilaian_rencana_nama }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Deadline <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="tanggal" name="tanggal" class="form-control bg-white datepicker" value="{{ \Carbon\Carbon::parse($penilaian_perencanaan->penilaian_rencana_deadline)->format('d/m/Y') }}" readonly>
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

<script type="text/javascript">
    $(document).ready(function(){
        autosize($('textarea'))
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
        });
        $('input,textarea').on('keyup', function() {
            $(this).removeClass('is-invalid');
        });

        $('select,input').on('change', function() {
            $(this).removeClass('is-invalid');
        });

        $('.number').mask("000.000.000.000", {reverse: true});
    })
</script>