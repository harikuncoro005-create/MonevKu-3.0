@php
    $colspan = 4;
@endphp

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
            <input type="hidden" name="id" class="form-control" value="{{ $result->partisipan_id }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Kategori <small class="text-danger">*</small></div>
                <div>
                    <select id="kategori" name="kategori" class="form-control">
                        <option></option>
                        <option value="1" {{ $result->partisipan_kategori == '1' ? 'selected' : '' }}>Pemilik</option>
                        <option value="2" {{ $result->partisipan_kategori == '2' ? 'selected' : '' }}>Penyewa</option>
                        <option value="3" {{ $result->partisipan_kategori == '3' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>
            </div>
            <div id="form-kategori">
                <div class="my-2">
                    <div class="text-gray-400 text-small">Nama <small class="text-danger">*</small></div>
                    <div>
                        <input type="text" id="nama" name="nama" class="form-control" value="{{ $result->partisipan_nama }}">
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Alamat <small class="text-danger">*</small></div>
                    <div>
                        <input type="text" id="alamat" name="alamat" class="form-control" value="{{ $result->partisipan_alamat }}">
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Nomor HP </div>
                    <div>
                        <input type="text" id="hp" name="hp" class="form-control" value="{{ $result->partisipan_hp }}">
                    </div>
                </div>
                <div class="my-2">
                    <div class="text-gray-400 text-small">Keterangan </div>
                    <div>
                        <textarea id="keterangan" name="keterangan" class="form-control">{{ $result->partisipan_keterangan }}</textarea>
                    </div>
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
        $("#kategori").select2({ 
            width: '100%',
            placeholder: '',
        })
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        autosize($('textarea'))
        $('input,textarea').on('keyup', function() {
            $(this).removeClass('is-invalid');
        });
    })
</script>