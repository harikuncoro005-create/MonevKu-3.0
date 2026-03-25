@php
    $colspan = 24;
@endphp

<?php if($total_records == 0) { ?>
    <tr class="small">
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;" class="small">
            <td class="text-center align-middle">{{ $index+1 }}</td>
            <td class="align-middle">{{ $instansi[$item->keuangan_instansi_kode]['instansi_nama'] }}</td>
            <td class="align-middle">{{ $item->keuangan_urusan_kode.'-'.$nomenklatur[$item->keuangan_urusan_kode]['nomenklatur_nama'] }}</td>
            <td class="align-middle">{{ $item->keuangan_bidang_urusan_kode.'-'.$nomenklatur[$item->keuangan_bidang_urusan_kode]['nomenklatur_nama'] }}</td>
            <td class="align-middle">{{ $item->keuangan_program_kode.'-'.$nomenklatur[$item->keuangan_program_kode]['nomenklatur_nama'] }}</td>
            <td class="align-middle">{{ $item->keuangan_kegiatan_kode.'-'.$nomenklatur[$item->keuangan_kegiatan_kode]['nomenklatur_nama'] }}</td>
            <td class="align-middle">{{ $item->keuangan_subkegiatan_kode.'-'.$nomenklatur[$item->keuangan_subkegiatan_kode]['nomenklatur_nama'] }}</td>
            
            <td class="align-middle text-right">{{ str_replace(',','.', number_format($item->keuangan_pagu)) }}</td>
            @foreach ($bulan as $index => $b)
            <td class="align-middle text-right">{{ str_replace(',','.', number_format($item->{'keuangan_'.$index})) }}</td>
            @endforeach
            <td class="text-center align-middle">{{ $sesi[$item->keuangan_sesi_kode]['sesi_nama'] }}</td>
            <td class="text-center align-middle">{{ $item->keuangan_jenis == 1 ? 'REALISASI' : 'RENCANA' }}</td>
            <td class="text-center align-middle">{{ $item->keuangan_tahun }}</td>

            {{-- <td class="text-center text-nowrap align-middle" style="column-gap: 0.75rem;">
                <a href="" do="update" title="Ubah Anggaran KAS" data-id="{{ $item->keuangan_id }}" class="text-gray-400 px-2 text-decoration-none btn-form">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="" data-id="{{ $item->keuangan_id }}" class="text-danger px-2 text-decoration-none btn-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="12" height="12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </a>
            </td>  --}}
        </tr>
<?php  } } ?>

        <tr class="small">
            <td colspan="{{ $colspan }}">Jumlah data : {{ $total_records }}</td>
        </tr>


<script type="text/javascript">
    $('.btn-delete').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show')
        var id = $(this).data('id')
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: "{{ $url_delete }}",
	            method:"POST",
	            data: {id:id},
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
                                $('#view-content').html('<tr><td colspan="{{ $colspan }}"><div class="text-center text-gray-400 text-small text-nowrap"><i class="fas fa-spinner fa-spin"></i> Loading</td><tr>')
                                $.ytLoad('start')
                            },
                            success:function(res) {
                                $('.btn-del').removeAttr('disabled', true);
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