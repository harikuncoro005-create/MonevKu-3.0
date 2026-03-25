@php
    $colspan = 8;
@endphp

<?php if($total_records == 0) { ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;">
            <td class="text-center">{{ $index+1 }}</td>
            <td class="text-center">
                <div class="px-2 py-1 text-white rounded" style="background-color: {{ $pembayaran[$item->pembayaran_tipe]['color'] }}; font-size: 0.65rem">{{ $pembayaran[$item->pembayaran_tipe]['nama'] }}</div>
            </td>
            <td class="text-center">
                {{ str_replace(',','.', number_format($item->pembayaran_jumlah)) }}
            </td>
            <td class="text-center">
                {!! $item->pembayaran_keterangan ? nl2br($item->pembayaran_keterangan) : '-' !!}
            </td>
            <td class="text-center">
                @if ($item->pembayaran_dokumen)
                    <a href="" do="view" title="Lihat Dokumen" data-id="{{ $item->pembayaran_id }}" class="text-gray-400 px-2 text-decoration-none btn-view">
                        <i class="fa-solid fa-file"></i></a>
                    </a>
                @else
                    -
                @endif 
            </td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->pembayaran_tanggal)->format('d/m/Y') }}</td>
            <td class="text-center">{{ $item->admin->admin_nama }}</td>
            <td class="text-center text-nowrap align-middle" style="column-gap: 0.75rem;">
                <a href="" do="update" title="Ubah Pembayaran" data-id="{{ $item->pembayaran_id }}" class="text-gray-400 px-2 text-decoration-none btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="" data-id="{{ $item->pembayaran_id }}" do="pembayaran" class="text-gray-400 px-2 text-decoration-none btn-delete">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
            {{-- <td>
                <a href="/iuran/partisipan/detail?id={{ $item->partisipan_id }}">{{ $item->partisipan_nama }}</a>
            </td>
            <td class="text-center">{{ $item->partisipan_alamat }}</td>
            <td class="text-center"><div class="px-2 py-1 text-white rounded" style="background-color: {{ $kategori[$item->partisipan_kategori]['color'] }}; font-size: 0.65rem">{{ $kategori[$item->partisipan_kategori]['nama'] }}</div></td>
            <td class="text-center">10</td> --}}
        </tr>
<?php } ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-small text-gray-400">Jumlah Data : {{ $total_records }}</td>
    </tr>
<?php } ?>

<script type="text/javascript">
    $('.btn-view').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_form }}",
            method:"POST",
            data: {
                parameter: {
                    to: $(this).attr('do'),
                    title: $(this).attr('title'),
                    id: $(this).data('id'),
                    url: "{{ $url }}"
                }
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

<script type="text/javascript">
    $('.btn-edit').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_form }}",
            method:"POST",
            data: {
                parameter: {
                    to: $(this).attr('do'),
                    title: $(this).attr('title'),
                    id: $(this).data('id'),
                    url: "{{ $url }}"
                }
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

<script type="text/javascript">
    $('.btn-delete').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show')
        var id = $(this).data('id')
        var to = $(this).attr('do')
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: "/delete-detail-partisipan",
	            method:"POST",
	            data: {id: id,to: to},
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
	        })
	    })
    })
</script>
