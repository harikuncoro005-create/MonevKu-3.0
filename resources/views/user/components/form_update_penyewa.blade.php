@php
    $colspan = 6;
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
            <input type="hidden" name="id" class="form-control" value="{{ $result->penyewa_id }}">
            <input type="hidden" name="warga_id" class="form-control" value="{{ $result->penyewa_warga_id }}">
            <div class="my-2">
                <div class="text-gray-400 text-small">Kondisi</div>
                <div>
                    <select id="aktif" name="aktif" class="form-control">
                        <option value="1" {{ $result->penyewa_aktif == '1' ? ' selected' : '' }}>Aktif</option>
                        <option value="0" {{ $result->penyewa_aktif == '0' ? ' selected' : '' }}>Tidak Aktif</option> 
                    </select>
                </div>
            </div>
            <hr>
            <div class="my-2">
                <div class="text-gray-400 text-small">Status Kepemilikan</div>
                <div>
                    <select id="kepemilikan" name="kepemilikan" class="form-control">
                        <option value="1" {{ $result->penyewa_kepemilikan == '1' ? ' selected' : '' }}>Pemilik</option>
                        <option value="0" {{ $result->penyewa_kepemilikan == '0' ? ' selected' : '' }}>Penyewa</option>
                    </select>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Kedudukan <small class="text-danger">*</small></div>
                <div>
                    <select id="kedudukan" name="kedudukan" class="form-control">
                        <option value="1" {{ $result->penyewa_kedudukan == '1' ? ' selected' : '' }}>Berkeluarga</option>
                        <option value="0" {{ $result->penyewa_kedudukan == '0' ? ' selected' : '' }}>Bukan Berkeluarga</option>
                    </select>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Status <small class="text-danger">*</small></div>
                <div>
                    <select id="status" name="status" class="form-control">
                        <option value="1" {{ $result->penyewa_status == '1' ? ' selected' : '' }}>Lajang</option>
                        <option value="2" {{ $result->penyewa_status == '2' ? ' selected' : '' }}>Menikah</option>
                        <option value="3" {{ $result->penyewa_status == '3' ? ' selected' : '' }}>Janda/Duda</option>
                    </select>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Tanggal Mulai Masuk</div>
                <div>
                    <input type="text" id="awal" name="awal" class="form-control bg-white datepicker" readonly value="{{ \Carbon\Carbon::parse($result->penyewa_awal)->format('d/m/Y') }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Tanggal Keluar </div>
                <div>
                    <input type="text" id="akhir" name="akhir" class="form-control bg-white datepicker" readonly value="{{ $result->penyewa_akhir ? \Carbon\Carbon::parse($result->penyewa_akhir)->format('d/m/Y') : '' }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nama <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="nama" name="nama" class="form-control" value="{{ $result->penyewa_nama }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nomor HP</div>
                <div>
                    <input type="text" id="hp" name="hp" class="form-control" value="{{ $result->penyewa_hp }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Keterangan </div>
                <div>
                    <textarea id="keterangan" name="keterangan" class="form-control">{{ $result->penyewa_keterangan }}</textarea>
                </div>
            </div>
            <div class="my-2" id="form-upload">
                @if ($result->penyewa_dokumen != '' && file_exists(public_path('/assets/img/penyewa/'.$result->penyewa_dokumen)))
                <div class="text-gray-400 text-small">Dokumen Penyewa</div>
                <div>
                    <div class="d-flex flex-row justify-content-between border border-success rounded px-3 py-2 text-small mb-2" style="line-height: 1">
                        <div class="text-success">{{ $result->penyewa_dokumen }}</div>
                        <div>
                            <a href="" data-id="{{ $result->penyewa_id }}" do="delete-attachment" class="text-gray-400 text-decoration-none btn-delete-attachment">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $('.btn-delete-attachment').off('click').on('click', function(e) {
                            e.preventDefault();
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                url: "/form-penyewa",
                                method:"POST",
                                data: {
                                    parameter: {
                                        to: $(this).attr('do'),
                                        title: "",
                                        id: $(this).data('id'),
                                        url: ""
                                    }
                                },
                                async:true,
                                dataType:"json",
                                beforeSend: function() {
                                    $('#form-upload').html('<div class="text-center text-gray-400 text-small py-2"><i class="fas fa-spinner fa-spin"></i> Loading')
                                },
                                success:function(res) {
                                    setTimeout(function() {
                                        $('#form-upload').html(res.html)
                                    }, 500)
                                }
                            })
                        })
                    </script>
                </div>
                @else
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
                @endif 
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