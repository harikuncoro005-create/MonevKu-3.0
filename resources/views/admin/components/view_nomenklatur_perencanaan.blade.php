@php
    $colspan = 7;
@endphp

<?php if($total_records == 0) { ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400s">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;" class="small">
            <td class="text-center">{{ $index+1 }}</td>
            <td>{{ $item->nomenklatur_kode }}</td>
            <td>{{ $item->nomenklatur_nama }}</td>
            <td>{{ $item->nomenklatur_indikator_keluaran}}</td>
            <td class="text-center">{{ $item->nomenklatur_satuan_keluaran }}</td>
            <td class="text-center">{{ $item->nomenklatur_tahun }}</td>
            <td class="text-center text-nowrap align-middle" style="column-gap: 0.75rem;">
                <a href="" do="update" title="Ubah Nomenklatur Perencanaan" data-id="{{ $item->nomenklatur_id }}" class="text-gray-400 px-2 text-decoration-none btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="" data-id="{{ $item->nomenklatur_id }}" class="text-gray-400 px-2 text-decoration-none btn-delete">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>
<?php  } } ?>
        <tr class="small">
            <td colspan="{{ $colspan }}">Jumlah data : {{ $total_records }}</td>
        </tr>

<script type="text/javascript">
    $('.btn-delete').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show')
        $('.btn-del').removeAttr('disabled', true);
        var id = $(this).data('id');
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: "{{ $url_delete }}",
	            method:"POST",
	            data: {
                    id: id,
                },
                async:true,
                dataType:"json",
	            beforeSend: function() {
	                $('.btn-del').attr('disabled', true);
	            },
	            success:function(res) {
	                setTimeout(function() {
                        $('#modal-delete').modal('hide')
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
                                $('#view-content').html('<tr><td colspan="{{ $colspan }}"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</div></td><tr>')
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
	        })
	    })
    })
</script>

<script type="text/javascript">
    $('.btn-edit').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_form }}",
            method:"POST",
            data: {
                to: $(this).attr('do'),
                title: $(this).attr('title'),
                id: $(this).data('id') 
            },
            async:true,
            dataType:"json",
            beforeSend: function() {
                $('#form-content').html('<div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading')
            },
            success:function(res) {
                setTimeout(function() {
                    $('#form-content').html(res.html)
                }, 500)
            }
        })
    })
</script>
