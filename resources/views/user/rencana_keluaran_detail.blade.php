@extends('layout.layout_admin')

@section('main_content')

<style>
.sticky-col {
    position: -webkit-sticky;
    position: sticky;
    background-color: white !important; /* Agar tidak transparan saat ditimpa */
    z-index: 2;
}

/* Atur posisi horizontal masing-masing kolom */
/* Sesuaikan nilai 'left' dengan lebar kolom Anda */

.first-col {
    left: 0;
    z-index: 3; /* Lebih tinggi agar tidak tertutup kolom 2 */
}

.first-col-right {
    right: 0;
    min-width: 80px;
}

.second-col {
    left: 7.5rem; /* Contoh: jika lebar kolom pertama 100px */
}

.third-col {
    left: 22.5rem; /* Contoh: jika lebar kolom pertama + kedua = 200px */
}

/* Khusus Header agar tetap di atas saat scroll vertikal (opsional) */
thead th.sticky-col {
    z-index: 10;
    top: 0;
}
</style>

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/rencana-keluaran" class="btn-back">
                    <div class="icon-circle">
                        <i class="fa-solid fa-arrow-left"></i>
                    </div>
                    <span class="btn-text">Kembali</span>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    @if ($keluaran_kepmen->keluaran_status)
        <div class="w-100 alert-success mb-4 rounded">
            <div class="text-center py-2 text-small">Approved</div>
        </div>
    @endif
    
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
                        </tr>
                </thead>
                <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                        <tr class="text-center">
                            <td style="vertical-align: middle; width: 5rem;">1</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_tipe }}.1</td>
                            <td class="text-left" style="vertical-align: middle;">{{ $nomenklatur[$keluaran_kepmen->keluaran_subkegiatan_kode]->nomenklatur_nama }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_target }}</td>
                            <td style="vertical-align: middle; width: 5rem;">{{ $keluaran_kepmen->keluaran_satuan }}</td>
                        </tr>
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
                            Rencana Keluaran
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
            <table class="table table-hover table-bordered">
                <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        @foreach ($bulan as $item)
                            <td style="vertical-align: middle; width: 5rem;">{{ strtoupper(substr($item, 0, 3)) }}</td>
                        @endforeach
                        <td style="vertical-align: middle; width: 5rem;">TOTAL</td>
                        <td class="sticky-col first-col-right" style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
                </thead>
                <tbody id="view-content" class="text-small text-gray-500" style="line-height: 1;">
                    <tr class="text-center">
                        @foreach ($bulan as $index => $item)
                            <td style="vertical-align: middle; min-width: 5rem;">{{ $keluaran_kepmen->{'keluaran_'.$index} }}</td>
                        @endforeach
                        <td style="vertical-align: middle; min-width: 5rem;">
                            <strong>{{ $total }}</strong>
                        </td>
                        <td class="sticky-col first-col-right" style="vertical-align: middle; min-width: 5rem;">
                            @if ($keluaran_kepmen->keluaran_status)
                            <div class="text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                </svg>
                            </div> 
                            @else
                            <a href="javascript:void(0)"
                                data-id="{{ $keluaran_kepmen->keluaran_id }}" 
                                to="update" 
                                title="Ubah Rencana Keluaran" 
                                class="btn-action-edit btn-edit">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            {{-- <a href="" data-id="{{ $keluaran_kepmen->keluaran_id }}" to="update" title="Ubah Rencana Keluaran" class="btn-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a> --}}
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        @canany(['admin', 'administrator'])
        <div class="mt-4 d-flex justify-content-end">
            @if ($keluaran_kepmen->keluaran_status)
            <button data-id="{{ $keluaran_kepmen->keluaran_id }}" 
            class="btn-modern-action btn-modern-unapprove btn-approve" 
            name="keluaran" 
            kode="0">
        <i class="fa-solid fa-circle-xmark"></i>
        <span>Unapprove</span>
    </button>
                {{-- <button data-id="{{ $keluaran_kepmen->keluaran_id }}" class="btn btn-sm btn-danger btn-approve" name="keluaran" kode="0" style="width: 7.5rem">Unpprove</button> --}}
            @else
            <button data-id="{{ $keluaran_kepmen->keluaran_id }}" 
            class="btn-modern-action btn-modern-approve btn-approve" 
            name="keluaran" 
            kode="1">
        <i class="fa-solid fa-circle-check"></i>
        <span>Approve</span>
    </button>
                {{-- <button data-id="{{ $keluaran_kepmen->keluaran_id }}" class="btn btn-sm btn-success btn-approve" name="keluaran" kode="1" style="width: 7.5rem">Approve</button> --}}
            @endif
            
        </div>
        <script>
            $('.btn-approve').off('click').on('click', function(e) {
                e.preventDefault();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ $url_approve }}",
                    method:"POST",
                    data: {
                        id: $(this).data('id'),
                        name: $(this).attr('name'),
                        kode: $(this).attr('kode') 
                    },
                    async:true,
                    dataType:"json",
                    beforeSend: function() {
                        $('.btn-approve').attr('disabled', true);
                    },
                    success:function(res) {
                        location.reload()
                    }
                })
            })
        </script>
        @endcanany

    @else
        <div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu</div></div>
    @endif
    
</div>

<div id="modal-form" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content p-4" style="border-radius: 0.75rem;" id="form-content"></div>
    </div>
</div>

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