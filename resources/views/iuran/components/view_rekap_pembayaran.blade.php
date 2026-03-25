@php
    $colspan = 6;
@endphp

<?php if($total_records == 0) { ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;">
            <td class="text-center">{{ $index+1 }}</td>
            <td>{{ $item->partisipan->partisipan_nama }}</td>
            <td class="text-center">{{ $item->partisipan->partisipan_alamat }}</td>
            <td class="text-center">
                <div class="px-2 py-1 text-white rounded" style="background-color: {{ $pembayaran[$item->pembayaran_tipe]['color'] }}; font-size: 0.65rem">{{ $pembayaran[$item->pembayaran_tipe]['nama'] }}</div>
            </td>
            <td class="text-center">{{ \Carbon\Carbon::parse($item->pembayaran_tanggal)->format('d/m/Y') }}</td>
            <td class="text-right text-nowrap align-middle bg-white" style="column-gap: 0.75rem; position: sticky; right: var(--border-width);">
                {{ str_replace(',','.', number_format($item->pembayaran_jumlah)) }}
            </td>
        </tr>
<?php } ?>
    <tr>
        <td colspan="{{ $colspan-1 }}" class="text-small text-gray-400">Jumlah Data : {{ $total_records }}</td>
        <td class="text-right text-nowrap align-middle bg-white" style="column-gap: 0.75rem; position: sticky; right: var(--border-width);">{{ str_replace(',','.', number_format($total)) }}</td>
    </tr>
<?php } ?>
