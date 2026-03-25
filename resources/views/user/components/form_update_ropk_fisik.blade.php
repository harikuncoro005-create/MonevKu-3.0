<form id="form-add" autocomplete="off">
    <div class="modal-body">
        <div class="modal-title text-primary" style="font-weight: 400; font-size: 1.25rem; line-height: 1rem;">{{ $title }}</div>
        <hr>
        <div style="line-height: 1">
            <input type="hidden" class="form-control" name="fisik_id" value="{{ $ropk_fisik->fisik_id }}">
            {{-- <input type="hidden" class="form-control" name="keluaran_id" value="{{ $keluaran_kepmen->keluaran_id }}">
            <input type="hidden" class="form-control" name="keluaran_target" value="{{ $keluaran_kepmen->keluaran_target }}"> --}}
            <div class="my-2">
                <div class="text-gray-400 text-small">Subkegiatan</div>
                <div>
                    <textarea class="form-control" readonly>{{ $nomenklatur->nomenklatur_kode.' - '.$nomenklatur->nomenklatur_nama }}</textarea>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Keluaran <small class="text-danger">*</small></div>
                <div>
                    <textarea id="keluaran" name="keluaran" class="form-control" readonly>{{ $nomenklatur->nomenklatur_indikator_keluaran }}</textarea>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Satuan <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="satuan" name="satuan" class="form-control" value="{{ $nomenklatur->nomenklatur_satuan_keluaran }}"  readonly>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Target <small class="text-danger">*</small></div>
                <div>
                    <input type="text" id="target" name="target" class="form-control" value="{{ $keluaran_kepmen->keluaran_target }}"  readonly>
                </div>
            </div>
            <hr>
            <div class="my-2">
                <div class="text-gray-400 text-small">Tahap <small class="text-danger">*</small></div>
                <div>
                    <select id="tahapan" name="tahapan" class="form-control">
                        <option value="1" {{ $ropk_fisik->fisik_tahapan == '1' ? 'selected' : '' }}>Persiapan</option>
                        <option value="2" {{ $ropk_fisik->fisik_tahapan == '2' ? 'selected' : '' }}>Pelaksanaan</option>
                        <option value="3" {{ $ropk_fisik->fisik_tahapan == '3' ? 'selected' : '' }}>Pelaporan</option>
                    </select>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Nomor <small class="text-danger">*</small></div>
                <div>
                    <input type="number" id="nomor" name="nomor" class="form-control" value="{{ $ropk_fisik->fisik_nomor }}">
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Aktivitas <small class="text-danger">*</small></div>
                <div>
                    <textarea id="aktivitas" name="aktivitas" class="form-control">{{ $ropk_fisik->fisik_aktivitas }}</textarea>
                </div>
            </div>
            <div class="my-2">
                <div class="text-gray-400 text-small">Acuan <small class="text-danger">*</small></div>
                <div>
                    <input type="number" id="acuan" name="acuan" class="form-control" value="{{ $ropk_fisik->fisik_acuan }}">
                </div>
            </div>
            <hr>
            <div class="my-2">
                <div class="row">
                    <div class="col-6">
                        @for ($i=1; $i <= 6; $i++)
                            <div class="text-gray-400 text-small">{{ $bulan[$i] }} <small class="text-danger">*</small></div>
                            <div>
                                <input type="number" id="bulan_{{ $i}}" name="bulan_{{ $i }}" class="form-control" value="{{ $ropk_fisik->{'fisik_'.$i} }}">
                            </div>
                        @endfor
                    </div>
                    <div class="col-6">
                        @for ($i=7; $i <= 12; $i++)
                            <div class="text-gray-400 text-small">{{ $bulan[$i] }} <small class="text-danger">*</small></div>
                            <div>
                                <input type="number" id="bulan_{{ $i }}" name="bulan_{{ $i }}" class="form-control" value="{{ $ropk_fisik->{'fisik_'.$i} }}">
                            </div>
                        @endfor
                    </div>
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
                    }
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
                            url: "{{ $url_view }}",
                            method: "POST",
                            data: {parameter: parameter},
                            async:true,
                            dataType:"json",
                            beforeSend: function() {
                                $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                                $.ytLoad('start')
                            },
                            success:function(res) {
                                $('#view-content').html(res.html)
                                $('#view-chart').html(res.chart)
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