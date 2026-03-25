@extends('layout.layout_admin')

@section('main_content')

@php
    $colspan = 8;
@endphp

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-column flex-sm-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div class="w-100">
                <a href="/iuran/partisipan?id={{ $data->iuran->iuran_id }}" class="btn rounded px-4 text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="float-right">
        <div class="">
            <a href="" class="text-gray-400 shadow-sm hover-blue-500 px-2 py-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa-solid fa-bars-staggered"></i></a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#" data-id="{{ $data->partisipan_id }}" do="update" title="Ubah Dokumen" class="dropdown-item text-blue-500 btn-edit-partisipan">Ubah</a>
                <a href="#" data-id="{{ $data->partisipan_id }}" do="partisipan" class="dropdown-item text-blue-500 btn-delete-partisipan">Hapus</a>
            </div>
        </div>
    </div>
    <div class="text-gray-500" style="width: 100%;">
        <div class="mb-2">
            <div class="small text-secondary">Tujuan</div>
            <div class="text-base"><strong>{{ $data->iuran->iuran_nama }}</strong></div>
        </div>
        <div class="mb-2">
            <div class="small text-secondary">Kategori</div>
            <div class="text-base"><span class="px-2 py-1 text-white text-small rounded" style="background-color: {{ $kategori[$data->partisipan_kategori]['color'] }}">{{ $kategori[$data->partisipan_kategori]['nama'] }}</span></div>
        </div>
        <div class="mb-2">
            <div class="small text-secondary">Nama</div>
            <div class="text-base">{{ $data->partisipan_nama }}</div>
        </div>
        <div class="mb-2">
            <div class="small text-secondary">Alamat</div>
            <div class="text-base">{{ $data->partisipan_alamat ? $data->partisipan_alamat : '-' }}</div>
        </div>
        <div class="mb-2">
            <div class="small text-secondary">No. HP</div>
            <div class="text-base">{{ $data->partisipan_hp ? $data->partisipan_hp : '-' }}</div>
        </div>
        <div>
            <div class="small text-secondary">Keterangan</div>
            <div class="text-base text-gray-400" style="line-height: 1; font-size: 0.875rem">{!! $data->partisipan_keterangan ? nl2br($data->partisipan_keterangan) : '-' !!}</div>
        </div>
        <hr>
        <div>
            <div class="text-gray-400" style="font-size: 0.675rem"><em>Ditambahkan oleh {{ auth()->user()->admin_nama }}</em></div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div class="text-gray-400">DOKUMEN</div>
        <div>
            <a href="#" data-id="{{ $data->partisipan_id }}" do="create" title="Tambah Dokumen" class="btn btn-sm px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add-doc"><i class="fa-solid fa-plus"></i></a>
        </div>
    </div>
    <hr>
    @if ($data->dokumen->count() != 0) 
        @foreach ($data->dokumen as $index => $item)
        <div class="d-flex flex-row justify-content-between border rounded px-3 py-2 text-small mb-2" style="line-height: 1">
            <div><a href="" data-id="{{ $item->dokumen_id }}" do="view" title="Lihat Dokumen" class="btn-add-doc">{{ $index+1 }}. {{ $item->dokumen_nama }}</a></div>
            <div>
                <a href="" data-id="{{ $item->dokumen_id }}" do="dokumen" class="text-gray-400 text-decoration-none btn-delete-dokumen">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center text-gray-400 text-small">Dokumen Kosong</div>
    @endif
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    <div class="mb-2 d-flex justify-content-between align-items-center flex-row" style="column-gap: 0.5rem;">
        <div class="text-gray-400">RIWAYAT PEMBAYARAN</div>
        <div>
            <a href="#" data-id="{{ $data->partisipan_id }}" do="create" title="Tambah Pembayaran" class="btn btn-sm px-4 btn-blue-500 w-100 shadow-sm text-nowrap btn-add"><i class="fa-solid fa-plus"></i></a>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td style="vertical-align: middle; width: 2rem;">NO</td>
                        <td style="vertical-align: middle; width: 5rem;">PEMBAYARAN</td>
                        <td style="vertical-align: middle; width: 20rem;">JUMLAH</td>
                        <td style="vertical-align: middle;">KETERANGAN</td>
                        <td style="vertical-align: middle; width: 5rem;">DOKUMEN</td>
                        <td style="vertical-align: middle; width: 5rem;">TANGGAL</td>
                        <td style="vertical-align: middle; width: 10rem;">ADMIN</td>
                        <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
              </thead>
              <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;"></tbody>
        </table>
    </div>
    <div id="pagination"></div>
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
    $('.btn-delete-dokumen').off('click').on('click', function(e) {
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
	                    location.reload()
	                }, 1000)
	            }
	        })
	    })
    })
</script>

<script type="text/javascript">
    $('.btn-edit-partisipan').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "/form-partisipan",
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
    $('.btn-add-doc').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-doc').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "/form-partisipan-dokumen",
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
                $('#form-doc').html('<div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading')
            },
            success:function(res) {
                setTimeout(function() {
                    $('#form-doc').html(res.html)
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
    $('.btn-delete-partisipan').off('click').on('click', function(e) {
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
	                    location.href = '/iuran/partisipan?id={{ $data->iuran->iuran_id }}'
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
                $('#pagination').html(res.pagination)
                $.ytLoad('complete')
            }
        })
    });
</script>

<script type="text/javascript">
    $(window).on('popstate', function() {
        var url = new URL($(location).attr('href'));
        var parameter = url.search
          .replace('?', '')
          .split('&')
          .map(param => param.split('='))
          .reduce((values, [ key, value ]) => {
                values[ key ] = decodeURIComponent((value + '').replace(/\+/g, '%20'))
                return values
            }, {})

        if(typeof parameter.q !== 'undefined' ) {
            $('#text-search').val(url.searchParams.get('q'))
        } else {
            $('#text-search').val('')
        }

        $.each( parameter, function( key, value ) {
            $('select[param="'+key+'"]').val(value)
        });

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url }}",
            method: "POST",
            data: {parameter: parameter},
            async:true,
            dataType:"json",
            beforeSend: function() {
                $.ytLoad('start')
            },
            success:function(res) {
                $('#view-content').html(res.html)
                $('#pagination').html(res.pagination)
                $.ytLoad('complete')
            }
        })
    });
</script>




















@endsection