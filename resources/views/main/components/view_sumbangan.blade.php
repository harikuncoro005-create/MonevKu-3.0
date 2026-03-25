@php
    $colspan = 4;
@endphp

<?php if($records->count() == 0) { ?>
    <tr>
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;">
            <td class="text-center">{{ $index+1 }}</td>
            <td>{{ $item->partisipan_nama }}</td>
            <td class="text-center">{{ $item->partisipan_alamat }}</td>
            <td style="text-align: right;">{{ str_replace(',','.', number_format(collect($item->pembayaran)->sum('pembayaran_jumlah'))) }}</td>
            {{-- <td>
                <a href="/iuran/partisipan/detail?id={{ $item->partisipan_id }}">{{ $item->partisipan_nama }}</a>
            </td>
            <td class="text-center">{{ $item->partisipan_alamat }}</td>
            <td class="text-center"><div class="px-2 py-1 text-white rounded" style="background-color: {{ $kategori[$item->partisipan_kategori]['color'] }}; font-size: 0.65rem">{{ $kategori[$item->partisipan_kategori]['nama'] }}</div></td>
            <td class="text-right">{{ str_replace(',','.', number_format(collect($item->pembayaran)->sum('pembayaran_jumlah'))) }}</td> --}}
        </tr>
<?php } ?>
    <tr class="bg-light">
        <td colspan="{{ $colspan-1 }}" class="text-small text-gray-400">Total :</td>
        <td colspan="{{ $colspan-1 }}" class="text-small text-gray-400" style="text-align: right;">{{ str_replace(',','.', number_format( $total)) }}</td>
    </tr>
<?php } ?>