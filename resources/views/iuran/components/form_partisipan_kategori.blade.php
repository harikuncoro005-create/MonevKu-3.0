@if ($id == 1)
    <div class="my-2">
        <div class="text-gray-400 text-small">Data Warga <small class="text-danger">*</small></div>
        <div>
            <select id="warga" name="warga" class="form-control">
                <option></option>
                @foreach ($warga as $item)
                <option value="{{ $item->warga_id }}">{{ $item->warga_no_rumah.' - '.$item->warga_nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endif

<div class="my-2">
    <div class="text-gray-400 text-small">Nama <small class="text-danger">*</small></div>
    <div>
        <input type="text" id="nama" name="nama" class="form-control">
    </div>
</div>
<div class="my-2">
    <div class="text-gray-400 text-small">Alamat <small class="text-danger">*</small></div>
    <div>
        <input type="text" id="alamat" name="alamat" class="form-control">
    </div>
</div>
<div class="my-2">
    <div class="text-gray-400 text-small">Nomor HP </div>
    <div>
        <input type="text" id="hp" name="hp" class="form-control">
    </div>
</div>
<div class="my-2">
    <div class="text-gray-400 text-small">Keterangan </div>
    <div>
        <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#warga").select2({ 
            width: '100%',
            placeholder: '',
            dropdownParent: $('#modal-form')
        }).on('change', function (e) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "/form-partisipan-warga",
                method:"POST",
                data: {
                    id: $(this).val()
                },
                async:true,
                dataType:"json",
                success:function(res) {
                    if(res.status) {
                        $('#nama').val(res.data.warga_nama)
                        $('#alamat').val(res.data.warga_no_rumah)
                        $('#hp').val(res.data.warga_hp)
                    }
                }
            })
        });
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