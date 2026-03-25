@extends('layout.layout_admin')

@section('main_content')

<div class="bg-white rounded px-4 py-3 my-3 shadow-sm">
    <div class="d-flex flex-column flex-sm-row justify-content-between" style="row-gap:0.5rem">
        <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem; row-gap: 0.5rem;">
            <div>
                <a href="/ropk-fisik" class="btn rounded text-blue-500 w-100 shadow-sm text-nowrap hover-blue-500"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded p-4 my-3 shadow-sm">
    @if ($ropk_fisik_status)
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
    <hr>
    <div id="view-chart"></div>
    <br>
    <div class="my-2 text-gray-500">
        <div class="d-flex flex-row align-items-center justify-content-between">
            <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                </svg>
                <span>KELUARAN</span>
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
                <tbody class="text-small text-gray-500" style="line-height: 1;">
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
                <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                    </svg>
                    <span>ROPK FISIK</span>
                </div>
                @if (!$ropk_fisik_status)
                <div>
                    <button type="button" title="Tambah ROPK Fisik" class="btn btn-sm btn-primary btn-add" data-id="{{ Request::get('ref') }}" to="create">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>
                @endif
            </div>
        </div>
        <div id="view-content"></div>
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
                    url: "{{ $url_view }}",
                    method: "POST",
                    data: {parameter: parameter},
                    async:true,
                    dataType:"json",
                    beforeSend: function() {
                        $('#view-content').html('<div class="card-body border"><div class="text-center text-gray-400 text-small"><i class="fas fa-spinner fa-spin"></i> Loading</div></div>')
                        $.ytLoad('start')
                    },
                    success:function(res) {
                        console.log(res)
                        $('#view-content').html(res.html)
                        $('#view-chart').html(res.chart)
                        $.ytLoad('complete')
                    }
                })

            });
        </script>

    @else
        <div class="card-body border"><div class="text-center text-gray-400 text-small">Data Belum Diinput, Silahkan Tambahkan Dahulu</div></div>
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

{{-- <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@endsection