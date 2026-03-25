@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 10;
@endphp

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-column flex-sm-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div class="w-100">
                <a href="/user" class="btn rounded px-4 text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="float-right">
        <div class="">
            <a href="" class="text-gray-400 shadow-sm hover-blue-500 px-2 py-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bars-staggered"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" data-id="{{ $data->warga_id }}" do="update" title="Ubah Warga" class="dropdown-item text-blue-500 btn-edit-warga">Ubah</a>
                <a href="#" data-id="{{ $data->warga_id }}" do="warga" class="dropdown-item text-blue-500 btn-delete-warga">Hapus</a>
            </div>
        </div>
    </div>
    <div class="text-gray-500" style="width: 100%;">
        <div class="mb-2">
            <div class="small text-secondary">Nomor Rumah</div>
            <div class="text-base">{{ $data->warga_no_rumah }}</div>
        </div>
        <div class="mb-2">
            <div class="small text-secondary">Status</div>
            <div class="text-base"><span class="px-2 py-1 text-white text-small rounded" style="background-color: {{ $status[$data->warga_status]['color'] }}">{{ $status[$data->warga_status]['nama'] }}</span></div>
        </div>
        <hr>
        <div class="mb-2">
            <div class="small text-secondary">Nama Pemilik</div>
            <div class="text-base">{{ $data->warga_nama }}</div>
        </div>
        <div class="mb-2">
            <div class="small text-secondary">No. HP</div>
            <div class="text-base">{{ $data->warga_hp }}</div>
        </div>
        <div>
            <div class="small text-secondary">Keterangan</div>
            <div class="text-base text-gray-400" style="line-height: 1; font-size: 0.875rem">{!! $data->warga_keterangan ? nl2br($data->warga_keterangan) : '-' !!}</div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div class="text-gray-400">RIWAYAT KEPENDUDUKAN</div>
        <div>
            <a href="#" data-id="{{ $data->warga_id }}" do="create" title="Tambah Kependudukan" class="btn btn-sm px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add"><i class="fa-solid fa-plus"></i></a>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 2rem;">NO</td>
                        <td style="vertical-align: middle; min-width: 10rem;">NAMA</td>
                        <td style="vertical-align: middle; width: 10rem;">KEPEMILIKAN</td>
                        <td style="vertical-align: middle; width: 10rem;">KEDUDUKAN</td>
                        <td style="vertical-align: middle; width: 10rem;">STATUS</td>
                        <td style="vertical-align: middle; width: 15rem;">HP</td>
                        <td style="vertical-align: middle; width: 15rem;">PENGHUNI</td>
                        <td style="vertical-align: middle; width: 5rem;">DOKUMEN</td>
                        <td style="vertical-align: middle; width: 8rem;">KONDISI</td>
                        <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
</div>

<div id="modal-doc" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-doc"></div>
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
    $('.btn-edit-warga').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "/form-user",
            method:"POST",
            data: {
                parameter: {
                    to: $(this).attr('do'),
                    title: $(this).attr('title'),
                    id: $(this).data('id')
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
    $('.btn-add').off('click').on('click', function(e) {
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
                    url: "{{ $url }}",
                    id: $(this).data('id')
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
    $('.btn-delete-warga').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show')
        var id = $(this).data('id')
        var to = $(this).attr('do')
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: "/delete-detail-user",
	            method:"POST",
	            data: {id: id,to: to},
                async:true,
                dataType:"json",
	            beforeSend: function() {
	                $('.btn-del').attr('disabled', true);
	            },
	            success:function(res) { 
	                setTimeout(function() {
	                    location.href = '/user'
	                }, 1000)
	            }
	        })
	    })
    })
</script>



<script type="text/javascript">
    $(document).ready(function(){

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
                $.ytLoad('complete')
            }
        })
    });
</script>




















@endsection