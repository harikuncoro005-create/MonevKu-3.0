@if ($result->{'lampiran_'.$bulan} && $result->{'lampiran_'.$bulan}['tipe'] == 1 && file_exists(public_path('/assets/img/'.$path.$result->{'lampiran_'.$bulan}['filename'])))
    @php
        $infoPath = pathinfo(public_path('/assets/img/'.$path.$result->{'lampiran_'.$bulan}['filename']));
        $extension = $infoPath['extension'];
    @endphp
    
    @if ($extension == 'pdf')
        <iframe src="/assets/img/{{ $path.$result->{'lampiran_'.$bulan}['filename'] }}" width="100%" height="800"></iframe>
    @elseif ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')
        <img class="rounded-full border-4 border-cyan-500" src="/assets/img/{{ $path.$result->{'lampiran_'.$bulan}['filename'] }}">
    @else
        <div class="text-center text-gray-500">Tidak Ditemukan</div>
    @endif
@else
    <div class="text-center text-gray-500">Tidak Ditemukan</div>
@endif

<div class="mt-2 border">
    <button type="button" class="btn btn-block btn-gray-300 px-3 rounded btn-close" class="close" data-dismiss="modal">Tutup</button>
</div>

<script type="text/javascript">
    $('.btn-close').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('hide')
        $('#form-content').html('')
    })
</script>