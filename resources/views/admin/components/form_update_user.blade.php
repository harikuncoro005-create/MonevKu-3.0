@php
    $colspan = 5;
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
            <input type="hidden" name="id" class="form-control" value="{{ $result->admin_id }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Username <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="username" name="username" class="form-control" value="{{ $result->username }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Password <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="password" name="password" class="form-control">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nama <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="nama" name="nama" class="form-control" value="{{ $result->admin_nama }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nomor HP <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="hp" name="hp" class="form-control" value="{{ $result->admin_hp }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Level <small class="text-danger">*</small></div>
                <div>
                    <select id="level" name="level" class="form-control">
                        <option value="1" {{ $result->admin_level == '1' ? 'selected' : '' }}>Admin</option>
                        <option value="7" {{ $result->admin_level == '7' ? 'selected' : '' }}>Administrator</option>>
                    </select>
                </div>
            </div>
            <hr>
            <div class="my-2">
                <div class="text-gray-400 text-small">Admin Aktif <small class="text-danger">*</small></div>
                <div>
                    <select id="aktif" name="aktif" class="form-control">
                        <option value="1" {{ $result->admin_aktif == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $result->admin_aktif == '0' ? 'selected' : '' }}>Tidak Aktif</option>>
                    </select>
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