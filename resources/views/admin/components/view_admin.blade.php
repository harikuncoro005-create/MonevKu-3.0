@php
    $colspan = 5;
@endphp

<?php if($total_records == 0) { ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;">
            <td><a href="admin/profile?id={{ $item->admin_id }}">{{ $item->admin_nama }}</a></td>
            <td class="text-center">{{ $item->username }}</td>
            <td class="text-center"><div class="px-2 py-1 text-white rounded" style="background-color: {{ $role[$item->admin_role]['color'] }}; font-size: 0.65rem">{{ $role[$item->admin_role]['nama'] }}</div></td>
            <td class="text-center">{!! $item->admin_aktif ? '<i class="fa-solid fa-circle-check text-success"></i>' : '<i class="fa-solid fa-circle-xmark text-danger"></i>' !!}</td>
            <td class="text-center text-nowrap align-middle" style="column-gap: 0.75rem;">
                <a href="" do="update" title="Ubah Admin" data-id="{{ $item->admin_id }}" class="text-gray-400 px-2 text-decoration-none btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="" data-id="{{ $item->admin_id }}" do="admin" class="text-gray-400 px-2 text-decoration-none btn-delete">
                    <i class="fa-solid fa-trash-can"></i>
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
