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
            <td>{{ $item->sesi_nama }}</td>
            <td class="text-center">{{ $item->sesi_kode }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->sesi_tanggal)->format('d/m/Y') }}</td>
            <td class="text-center">{{ $item->sesi_tahun }}</td>
            <td>{{ $item->sesi_keterangan }}</td>
            <td class="text-center">
                @if ($item->sesi_status)
                <div class="text-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" width="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                @else
                <div class="text-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" width="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                @endif
            </td>
            <td class="text-center text-nowrap align-middle" style="column-gap: 0.75rem;">
                <a href="" do="update" title="Ubah Sesi" data-id="{{ $item->sesi_id }}" class="text-gray-400 px-2 text-decoration-none btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
            </td>
        </tr>
<?php } ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-small text-gray-400">Jumlah Data : {{ $total_records }}</td>
    </tr>
<?php } ?>

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
                    url: "{{ $url_view }}"
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
