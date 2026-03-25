@php
    $colspan = 3;
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
            <div class="my-2">
                <div class="text-gray-400 text-small">Nomor Rumah <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="nomor" name="nomor" class="form-control">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nama Pemilik <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="nama" name="nama" class="form-control">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nomor HP Pemilik <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="hp" name="hp" class="form-control">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Status Rumah <small class="text-danger">*</small></div>
                <div>
                    <select id="status" name="status" class="form-control">
                        <option value="" hidden>Pilih Status</option>
                        <option value="1">Ditempati Sendiri</option>
                        <option value="2">Disewakan</option>
                        <option value="3">Kosong</option>
                    </select>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Keterangan </div>
                <div>
                    <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
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
                            url: "{{ $url }}",
                            method: "POST",
                            data: {parameter: parameter},
                            async:true,
                            dataType:"json",
                            beforeSend: function() {
                                $('#view-content').html('<tr><td colspan="{{ $colspan }}"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                                $.ytLoad('start')
                            },
                            success:function(res) {
                                $('#view-content').html(res.html)
                                $('#pagination').html(res.pagination)
                                $.ytLoad('complete')
                            }
                        })
                    }, 1000)
                }  
            }
        })
    })
</script>

{{-- <script type="text/javascript">
    $(document).ready(function(){
        $("#barang").select2({ 
            width: '100%',
            placeholder: '',
        }).on('change', function (e) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "/get-barang",
                method:"POST",
                data: {
                    id: $(this).val()
                },
                async:true,
                dataType:"json",
                success:function(res) {
                    if(!res.status) {
                        $('#jenis,#satuan').addClass('is-invalid')
                    }

                    if(res.status) {
                        $('#jenis').val(res.data.jenis_produk.jenis_produk_nama)
                        $('#satuan').val(res.data.barang_satuan)
                    }
                }
            })
        });
        $("#gudang").select2({ 
            width: '100%',
            placeholder: '',
        })
    });
</script> --}}

<script type="text/javascript">
    $(document).ready(function(){
        autosize($('textarea'))
        $(".datepicker").datepicker({
            dateFormat: "dd/mm/yy",
            changeYear: true,
        });
        $('input,textarea').on('keyup', function() {
            $(this).removeClass('is-invalid');
        });

        $('select').on('change', function() {
            $(this).removeClass('is-invalid');
        });

        $('.number').mask("000.000.000.000", {reverse: true});
    })
</script>