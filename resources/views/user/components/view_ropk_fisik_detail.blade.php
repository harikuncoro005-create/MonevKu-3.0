@if (count($ropk_fisik) != 0)
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-bordered">
                <thead style="font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                    <tr class="text-center">
                        <td rowspan="2" style="vertical-align: middle; width: 5rem;">KODE</td>
                        <td rowspan="2" style="vertical-align: middle;">AKTIVITAS KEGIATAN</td>
                        <td rowspan="2" style="vertical-align: middle; width: 5rem;">ACUAN</td>
                        <td colspan="12" style="vertical-align: middle; width: 5rem;">BULAN</td>
                        <td rowspan="2" style="vertical-align: middle; width: 5rem;">AKSI</td>
                    </tr>
                    <tr class="text-center">
                        @foreach ($bulan as $index =>$item)
                            <td style="vertical-align: middle; width: 5rem;">{{ $index }}</td>
                        @endforeach
                    </tr>
            </thead>
            <tbody class="text-small text-gray-500" style="line-height: 1;">
                @foreach ($tahapan as $key => $value)
                <tr class="text-center font-weight-bold alert-warning">
                    <td class="text-left" style="vertical-align: middle; width: 5rem;">{{ $key }}</td>
                    <td class="text-left" style="vertical-align: middle;">{{ $value['nama'] }}</td>
                    <td colspan="14" style="vertical-align: middle;"></td>
                </tr>
                @if (count($value['data']) != 0)
                    @foreach ($value['data'] as $item)
                        <tr class="text-center">
                            <td class="text-left" style="vertical-align: middle; width: 5rem;">{{ $key }}.{{ $item['fisik_nomor'] }}</td>
                            <td class="text-left" style="vertical-align: middle;">{{ $item['fisik_aktivitas'] }}</td>
                            <td style="vertical-align: middle;">{{ str_replace(".", ",", sprintf('%.2f', $item['fisik_acuan'])) }}</td>
                            @foreach ($bulan as $index => $val)
                                <td style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $item['fisik_'.$index])) }}</td>
                            @endforeach
                            <td >
                                @if ($ropk_fisik_status)
                                <div class="text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                    </svg>
                                </div>
                                @else
                                <div class="text-nowrap d-flex justify-content-center" style="vertical-align: middle; width: 5rem; column-gap: 0.5rem">
                                <a data-id="{{ $item['fisik_id'] }}" href="#" title="Ubah ROPK Fisik" to="update" class="btn-edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <a href="#" data-id="{{ $item['fisik_id'] }}" title="Hapus ROPK Fisik" class="btn-delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a>
                                @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                <tr class="text-center">
                    <td colspan="16">-</td>
                </tr>
                @endif
                        
                @endforeach
                
            </tbody>
            <tfoot class="text-small text-gray-500" style="line-height: 1;">
                <tr>
                    <td colspan="16"></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"class="text-left" style="vertical-align: middle;">JUMLAH</td>
                    <td class="{{ collect($ropk_fisik)->sum('fisik_acuan') <=> 100 ? 'alert-danger' : 'alert-success' }}" style="vertical-align: middle;">{{ str_replace(".", ",", sprintf('%.2f', collect($ropk_fisik)->sum('fisik_acuan'))) }}</td>
                    @foreach ($bulan as $index => $item)
                        <td style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', collect($ropk_fisik)->sum('fisik_'.$index))) }}</td>
                    @endforeach
                    <td></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"class="text-left" style="vertical-align: middle;">Jumlah ROPK Fisik Kumulatif</td>
                    <td></td>
                    @foreach ($bulan as $index => $item)
                        <td class="font-weight-bold" style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $fisik_komulatif[$index])) }}</td>
                    @endforeach
                    <td></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"class="text-left" style="vertical-align: middle;">% Angkas Komulatif</td>
                    <td></td>
                    @foreach ($bulan as $index => $item)
                        <td style="vertical-align: middle; width: 5rem;">{{ str_replace(".", ",", sprintf('%.2f', $keuangan_komulatif[$index])) }}</td>
                    @endforeach
                    <td></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"class="text-left" style="vertical-align: middle;">Perbandingan Fisik - Keuangan</td>
                    <td></td>
                    @foreach ($bulan as $index => $item)
                        <td style="vertical-align: middle; width: 5rem;" class="{{ $fisik_komulatif[$index]-$keuangan_komulatif[$index] < -1 ? 'alert-danger' : '' }}">{{ str_replace(".", ",", sprintf('%.2f', $fisik_komulatif[$index]-$keuangan_komulatif[$index])) }}</td>
                    @endforeach
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @canany(['admin', 'administrator'])
        <div class="mt-4 d-flex justify-content-end">
            @if ($ropk_fisik_status)
                <button data-id="{{ $ref }}" class="btn btn-sm btn-danger btn-approve" name="fisik" kode="0" style="width: 7.5rem">Unpprove</button>
            @else
                <button data-id="{{ $ref }}" class="btn btn-sm btn-success btn-approve" name="fisik" kode="1" style="width: 7.5rem">Approve</button>
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

<script type="text/javascript">
    $('.btn-delete').off('click').on('click', function(e) {
        e.preventDefault();
        $('#modal-delete').modal('show')
        var id = $(this).data('id');
        $('.btn-del').off('click').on('click', function(e){
	        e.preventDefault()
	        $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	            url: "/delete-ropk-fisik",
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
                    to: $(this).attr('to')
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