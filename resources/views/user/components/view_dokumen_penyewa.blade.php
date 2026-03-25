@if ($result->penyewa_dokumen != '' && file_exists(public_path('/assets/img/penyewa/'.$result->penyewa_dokumen)))
    @php
        $infoPath = pathinfo(public_path('/assets/img/penyewa/'.$result->penyewa_dokumen));
        $extension = $infoPath['extension'];
    @endphp
    
    @if ($extension == 'pdf')
        <iframe src="/public/assets/img/penyewa/{{ $result->penyewa_dokumen }}" width="100%" height="800"></iframe>
    @elseif ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png')
        <img class="rounded-full border-4 border-cyan-500" src="/public/assets/img/penyewa/{{ $result->penyewa_dokumen }}">
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