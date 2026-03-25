<div class="mb-2 row">
        <div class="col-lg-4 text-gray-500">
        Sub Kegiatan
    </div>
    <div class="col-lg-8">
        <select id="subkegiatan" class="form-control search" param="subkegiatan">
            <option value="" hidden></option>
            @foreach ($subkegiatan as $item)
                <option value="{{ $item->nomenklatur->nomenklatur_id }}">{{ $item->nomenklatur->nomenklatur_nama }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="d-flex justify-content-end">
    <a href="#" class="btn btn-blue-500 btn-search disabled">Tampilkan</a>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $("#subkegiatan").select2({ 
            width: '100%',
            placeholder: 'Pilih Subkegiatan',
        }).on('change', function (e) {
            $('.btn-search').removeClass('disabled');
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var url = new URL($(location).attr('href'))

        var parameter = url.search
        .replace('?', '')
        .split('&')
        .map(param => param.split('='))
        .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        $('.btn-search').off('click').on('click', function(e){
            e.preventDefault();

            $.ytLoad({
                registerAjaxHandlers: false
            });

            var jenis = $('#jenis').val();
            var pd = $('#instansi').val();
            var sub = $('#subkegiatan').val();

            var url = new URL($(location).attr('href'));
            var search_params = url.searchParams;

            search_params.set('jenis', jenis);
            parameter['jenis'] = jenis;
            search_params.set('pd', pd);
            parameter['pd'] = pd;
            search_params.set('id', sub);
            parameter['id'] = sub;
            
            // var new_url = url.toString();
            // history.pushState({}, null, new_url);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ $url_view }}",
                method: "POST",
                data: {parameter: parameter},
                async:true,
                dataType:"json",
                beforeSend: function() {
                    $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                    $.ytLoad('start');
                },
                success:function(res) {
                    console.log(res)
                    $('#view-content').html(res.html)
                    $.ytLoad('complete')
                }
            })
        })
    });
</script>


