<div class="mb-2 row">
    <div class="col-lg-4 text-gray-500">
        Bulan
    </div>
    <div class="col-lg-8">
        <select id="bulan" class="form-control search" param="bulan">
            <option value="" hidden></option>
            @foreach ($bulan as $index => $item)
                <option value="{{ $index }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#bulan").select2({ 
            width: '100%',
            placeholder: 'Pilih Bulan',
        })

        $('#bulan').on('change', function() {
            $(".btn-search").removeClass("disabled");
        });
    });
</script>