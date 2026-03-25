@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/renja" class="btn-back">
                    <div class="icon-circle">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    <span class="btn-text">Kembali</span>
                </a>
            </div>
            {{-- <div>
                <a href="/renja" class="btn btn-action border btn-outline-secondary text-nowrap" style="font-size: 0.75rem"><i class="fa-solid fa-arrow-left mr-1" style="font-size: 0.75rem"></i><span>Kembali</span></a>
            </div> --}}
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    {{-- <div class="info-container px-1" style="max-height: 40rem; overflow-y: auto;">
        @foreach ($ref_kode as $item)
        <div class="info-card-wrap mb-2">
            <div class="card border-0 shadow-sm custom-radius">
                <div class="card-body p-3">
                    <div class="responsive-flex-container">
                        
                        <div class="info-meta">
                            <div class="category-tag">
                                {{ $item['kode_nama'] }}
                            </div>
                            <div class="code-badge">
                                {{ $item['kode_nomenklatur'] }}
                            </div>
                        </div>

                        <div class="v-divider d-none d-md-block mx-3"></div>

                        <div class="info-content mt-2 mt-md-0">
                            <span class="text-nomenklatur">
                                {{ $nomenklatur[$item['kode_nomenklatur']]->nomenklatur_nama }}
                            </span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div> --}}

    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-sm table-borderless">
                <tbody class="text-small text-gray-500" style="line-height: 1rem;">
                    @foreach ($ref_kode as $item)
                    <tr class="text-nowrap">
                        <td style="vertical-align: middle; width:10rem">{{ $item['kode_nama'] }}</td>
                        <td style="vertical-align: middle; width:10rem">{{ $item['kode_nomenklatur'] }}</td>
                        <td style="vertical-align: middle">{{ $nomenklatur[$item['kode_nomenklatur']]->nomenklatur_nama }}</td>
                    </tr>
                    @endforeach
                </tbody>
        </table>
    </div>
    <br>
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center text-primary rounded-sm mr-2" 
                    style="width: 30px; height: 30px; border: px solid #e3e6f0;">
                    <i class="fa-solid fa-folder-tree fa-xs"></i>
                </div>
                
                <div class="border-left pl-2" style="line-height: 1">
                    <span class="font-weight-bold text-primary text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.8px;">
                        Keluaran Kepmendagri
                    </span>
                </div>
            </div>
            
            @if (!$keluaran_kepmen)
            <div>
                <a href="javascript:void(0)"
                kode="1"
                data-id="{{ Request::get('ref') }}"
                to="create"
                title="Tambah Keluaran Kepmendagri"
                class="btn-modern btn-add">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <span class="text-label">Tambah</span>
                </a>
            </div>  
            @endif
        </div>
    </div>
    @if ($keluaran_kepmen)
        <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
            <table class="table">
                    <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                        <tr class="text-center">
                            <td style="vertical-align: middle; width: 5rem;">NO</td>
                            <td style="vertical-align: middle; width: 5rem;">KODE</td>
                            <td class="text-left" style="vertical-align: middle;">KELUARAN</td>
                            <td style="vertical-align: middle; width: 5rem;">TARGET</td>
                            <td style="vertical-align: middle; width: 5rem;">SATUAN</td>
                            <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                        </tr>
                </thead>
                <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                        <tr class="text-center">
                            <td style="vertical-align: middle; width: 5rem;">1</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_tipe }}.1</td>
                            <td class="text-left" style="vertical-align: middle;">{{ $nomenklatur[$keluaran_kepmen->keluaran_subkegiatan_kode]->nomenklatur_nama }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_target }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_satuan }}</td>
                            <td style="vertical-align: middle; width: 5rem;">
                                @if ($keluaran_kepmen->keluaran_status)
                                <div class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </div> 
                                @else
                                <a href="javascript:void(0)" 
                                kode="1" 
                                data-id="{{ $keluaran_kepmen->keluaran_id }}" 
                                to="update" 
                                title="Keluaran Kepmendagri" 
                                class="btn-action-edit btn-edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu</div></div>
    @endif
    <br>
    @if ($keluaran_kepmen)
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center text-primary rounded-sm mr-2" 
                    style="width: 30px; height: 30px; border: px solid #e3e6f0;">
                    <i class="fa-solid fa-folder-tree fa-xs"></i>
                </div>
                
                <div class="border-left pl-2" style="line-height: 1">
                    <span class="font-weight-bold text-primary text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.8px;">
                        Keluaran Riil
                    </span>
                </div>
            </div>
            @if (!$keluaran_kepmen->keluaran_status)
            <div>
                <a href="javascript:void(0)"
                kode="2"
                data-id="{{ Request::get('ref') }}"
                to="create"
                title="Tambah Keluaran Riil"
                class="btn-modern btn-add">
                    <div class="icon-wrapper">
                        <i class="fa-solid fa-plus"></i>
                    </div>
                    <span class="text-label">Tambah</span>
                </a>
            </div>
            @endif
        </div>
    </div>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        @if (count($keluaran_riil) > 0)
            <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
                <table class="table">
                        <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                            <tr class="text-center">
                                <td style="vertical-align: middle; width: 5rem;">NO</td>
                                <td style="vertical-align: middle; width: 5rem;">KODE</td>
                                <td class="text-left" style="vertical-align: middle;">KELUARAN</td>
                                <td style="vertical-align: middle; width: 5rem;">TARGET</td>
                                <td style="vertical-align: middle; width: 5rem;">SATUAN</td>
                                <td style="vertical-align: middle; width: 5rem;">AKSI</td>
                            </tr>
                    </thead>
                    <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                    @foreach ($keluaran_riil as $index => $item)
                        <tr class="text-center">
                            <td style="vertical-align: middle; width: 5rem;">{{ $loop->iteration }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $item->keluaran_tipe }}.{{ $loop->iteration }}</td>
                            <td class="text-left" style="vertical-align: middle;">{{ $item->keluaran_nama }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $item->keluaran_target }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $item->keluaran_satuan }}</td>
                            <td style="vertical-align: middle; width: 5rem;">
                                @if ($keluaran_kepmen->keluaran_status)
                                <div class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </div> 
                                @else
                                <div class="d-flex justify-content-center" style="gap: 0.5rem;">
                                    <a href="javascript:void(0)" 
                                    kode="2" 
                                    data-id="{{ $item->keluaran_id }}" 
                                    to="update" 
                                    title="Keluaran Riil" 
                                    class="btn-action-edit btn-edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    <a href="javascript:void(0)" 
                                    data-id="{{ $item->keluaran_id }}" 
                                    title="Hapus" 
                                    class="btn-action-delete btn-delete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </div>
                                {{-- <a href="" kode="2" data-id="{{ $item->keluaran_id }}" to="update" title="Ubah" class="btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <a href="" data-id="{{ $item->keluaran_id }}" title="Hapus"  class="btn-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a> --}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu</div></div>
        @endif
        
    </div>
    @endif
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
        var id = $(this).data('id')
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: "/delete-keluaran",
	            method:"POST",
	            data: {id:id},
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
    $('.btn-edit').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-form').modal('show')
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $url_form }}",
            method:"POST",
            data: {
                parameter: {
                    id: $(this).data('id'),
                    kode: $(this).attr('kode'),
                    title: $(this).attr('title'),
                    to: $(this).attr('to'),
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
                    id: $(this).data('id'),
                    kode: $(this).attr('kode'),
                    title: $(this).attr('title'),
                    to: $(this).attr('to'),
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

@endsection