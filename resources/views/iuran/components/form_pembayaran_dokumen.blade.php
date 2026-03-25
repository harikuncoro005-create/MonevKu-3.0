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