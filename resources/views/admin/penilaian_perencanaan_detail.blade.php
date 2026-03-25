@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/panel/penilaian-perencanaan" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
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
        <table class="table table-bordered">
            <tbody style="font-weight: 500; font-size: 0.9rem; line-height: 1rem;">
                    <tr>
                        <td style="vertical-align: middle; width: 20rem">Dokumen Perencanaan yang Dinilai</td>
                        <td style="vertical-align: middle;">{{ $penilaian_perencanaan->penilaian_rencana_nama }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; width: 20rem">Digunakan Untuk Penilaian Bulan</td>
                        <td style="vertical-align: middle;">{{ $bulan[$penilaian_perencanaan->penilaian_rencana_bulan] }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle; width: 20rem">Tanggal Deadline</td>
                        <td style="vertical-align: middle;">{{ \Carbon\Carbon::parse($penilaian_perencanaan->penilaian_rencana_deadline)->locale('id')->isoFormat('D MMMM YYYY') }}</td>
                    </tr>
            </tbody>
        </table>
    </div>

    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center">
                    <td>NO</td>
                    <td>PERANGKAT DAERAH</td>
                    <td style="vertical-align: middle; width: 10rem">TANGGAL DITERIMA</td>
                    <td style="vertical-align: middle; width: 5rem">BUKTI DUKUNG</td>
                    <td style="vertical-align: middle; width: 5rem">JUMLAH KETERLAMBATAN</td>
                    <td style="vertical-align: middle; width: 5rem">SKOR</td>
                    <td style="vertical-align: middle; width: 5rem">AKSI</td>
                </tr>
            </thead>
            <tbody style="font-weight: 500; font-size: 0.9rem; line-height: 1rem;">
                @foreach ($instansi as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->instansi_nama }}</td>
                        <td class="text-center">{{ $penilaian_perencanaan_opd->has($item->instansi_kode) ? \Carbon\Carbon::parse($penilaian_perencanaan_opd->get($item->instansi_kode)->penilaian_rencana_item_tanggal)->locale('id')->isoFormat('D MMMM YYYY') : '-' }}</td>
                        <td class="text-center">
                            @if ($penilaian_perencanaan_opd->has($item->instansi_kode))
                                <a href="#" class="btn-add" to="view" title="Bukti Dukung" data-id="{{ $penilaian_perencanaan_opd->get($item->instansi_kode)->penilaian_rencana_item_id }}" kode="" url="{{ $url_form }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                </a>
                            @else
                            -
                            @endif
                        </td>
                        <td class="text-center">{{ $penilaian_perencanaan_opd->has($item->instansi_kode) ? $penilaian_perencanaan_opd->get($item->instansi_kode)->penilaian_rencana_item_jumlah : '-' }}</td>
                        <td class="text-center">{{ $penilaian_perencanaan_opd->has($item->instansi_kode) ? $penilaian_perencanaan_opd->get($item->instansi_kode)->penilaian_rencana_item_nilai : '-' }}</td>
                        <td class="text-center">
                            @if ($penilaian_perencanaan_opd->has($item->instansi_kode))
                                <a href="" data-id="{{ $penilaian_perencanaan_opd->has($item->instansi_kode) ? $penilaian_perencanaan_opd->get($item->instansi_kode)->penilaian_rencana_item_id : '' }}" class="text-nowrap text-danger btn-delete" title="Hapus Penilaian Perangkat Daerah" url="/delete-penilaian-perencanaan-opd">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a>
                            @else
                                <a href="" to="create" data-id="{{ $penilaian_perencanaan->penilaian_rencana_id }}" kode={{ $item->instansi_kode }} class="text-nowrap text-gray-500 btn-add" title="Penilaian Perangkat Daerah" url={{ $url_form }}>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" height="20" width="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="modal-form" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-content"></div>
    </div>
</div>

<div id="modal-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;">
            <div class="modal-body">
                <div class="text-center text-gray-400 mb-3"><i class="fa-solid fa-trash-can fa-2xl"></i></div>
                <div class="text-center text-gray-500">Yakin Ingin Menghapus?</div>
                <br>
                <div class="d-flex flex-row justify-content-between align-items-center" style="column-gap: 0.5rem">
                    <button type="button" class="btn btn-block btn-rose-400 px-3 rounded-pill btn-del"><i class="fa-solid fa-check"></i> Delete</button>
                    <button type="button" class="btn btn-block btn-gray-300 px-3 rounded-pill" class="close" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.btn-delete').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show')
        var url = $(this).attr('url')
        var id = $(this).data('id')
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: url,
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
	                    location.reload()
	                }, 1000)
	            }
	        })
	    })
    })
</script>

<script type="text/javascript">
    $('.btn-add').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: $(this).attr('url'),
            method:"POST",
            data: {
                id: $(this).data('id'),
                title: $(this).attr('title'),
                to: $(this).attr('to'),
                kode: $(this).attr('kode')
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