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