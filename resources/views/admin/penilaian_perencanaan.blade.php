@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/panel/penilaian" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>
<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>PENILAIAN PERENCANAAN</span>
            </div>
        </div>
    </div>


    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 10rem">BULAN</td>
                        <td style="vertical-align: middle;">NAMA DOKUMEN</td>
                        <td style="vertical-align: middle; width: 10rem;">DEADLINE</td>
                        <td style="vertical-align: middle; width: 5rem;">STATUS</td>
                        <td style="vertical-align: middle; width: 10rem;">AKSI</td>
                    </tr>
            </thead>
            <tbody class="text-small text-gray-500" style="line-height: 1;">
                @foreach ($bulan as $index => $item)
                <tr>
                    <td>{{ $item }}</td>
                    <td>{!! $penilaian_rencana->has($index) ?  $penilaian_rencana->get($index)->penilaian_rencana_nama : '<div class="text-gray-400"><em>Belum input dokumen yang dinilai</em></div>' !!}</td>
                    <td class="text-center text-nowrap">{{ $penilaian_rencana->has($index) ? \Carbon\Carbon::parse($penilaian_rencana->get($index)->penilaian_rencana_deadline)->locale('id')->isoFormat('D MMMM YYYY') : '-' }}</td>
                    <td class="text-center">{{ $penilaian_rencana->has($index) ?  $penilaian_rencana->get($index)->item->count() : 0 }}/{{ $instansi->count() }}</td>
                    <td class="text-center">
                        @if ($penilaian_rencana->has($index))
                        <div class="d-flex justify-content-center" style="column-gap: 1rem">
                            <a href="" to="update" data-id="{{ $penilaian_rencana->get($index)->penilaian_rencana_id }}" class="text-nowrap text-info btn-add" title="Ubah Penilaian Dokumen Perencanaan" bulan="{{ $index }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="/panel/penilaian-perencanaan/detail?id={{ $penilaian_rencana->has($index) ?  $penilaian_rencana->get($index)->penilaian_rencana_id : '' }}" class="text-nowrap text-success" title="Detail Penilaian Perencanaan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                            </a>
                        </div>
                        @else
                        <a href="" to="create" data-id="" class="text-nowrap text-gray-500 btn-add" title="Buat Penilaian Dokumen Perencanaan" bulan="{{ $index }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
            {{-- <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody> --}}
        </table>
    </div>
    <div id="pagination"></div>
</div>

<div id="modal-form" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-content"></div>
    </div>
</div>

<script type="text/javascript">
    $('.btn-add').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_form }}",
            method:"POST",
            data: {
                to: $(this).attr('to'),
                title: $(this).attr('title'),
                id: $(this).data('id'),
                bulan: $(this).attr('bulan')
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

@endsection