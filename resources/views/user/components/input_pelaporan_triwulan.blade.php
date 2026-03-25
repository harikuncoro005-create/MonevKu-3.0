<div class="mb-2 row">
    <div class="col-lg-4 text-gray-500">
        Triwulan
    </div>
    <div class="col-lg-8">
        <select id="tw" class="form-control search" param="tw">
            <option value="" hidden></option>
            @foreach ($triwulan as $index => $item)
                <option value="{{ json_encode($item) }}">{{ $item['nama'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#tw").select2({ 
            width: '100%',
            placeholder: 'Pilih Triwulan',
        })

        $('#tw').on('change', function() {
            $(".btn-search").removeClass("disabled");
        });
    });
</script>