@php
    $colspan = 22;
@endphp

<?php if($total_records == 0) { ?>
    <tr class="small">
        <td colspan="{{ $colspan }}" class="text-center text-small text-gray-400">Tidak Ditemukan</td>
    </tr>
<?php } else {
    foreach ($records as $index => $item) { ?>
        <tr style="line-height: 1;" class="small">
            <td class="text-center align-middle">{{ $index+1 }}</td>
            <td class="align-middle">{{ $instansi[$item->fisik_instansi_kode]['instansi_nama'] }}</td>
            <td class="align-middle">{{ $item->fisik_subkegiatan_kode.'-'.$nomenklatur[$item->fisik_subkegiatan_kode]['nomenklatur_nama'] }}</td>
            <td class="align-middle text-center">{{ $tahapan[$item->fisik_tahapan]['nama'] }}</td>
            <td class="align-middle">{{ $item->fisik_aktivitas }}</td>
            <td class="align-middle text-center">{{ str_replace(".", ",", sprintf('%.2f', $item->fisik_acuan)) }}</td>
            @foreach ($bulan as $index => $b)
            <td class="align-middle text-center" style="width:8rem">{{ str_replace(".", ",", sprintf('%.2f', $item->{'fisik_'.$index})) }}</td>
            @endforeach
            <td class="text-center align-middle">{{ $sesi[$item->fisik_sesi_kode]['sesi_nama'] }}</td>
            <td class="text-center align-middle">{{ $item->fisik_jenis == 1 ? 'REALISASI' : 'RENCANA' }}</td>
            <td class="text-center align-middle">{{ $item->fisik_tahun }}</td>
            <td class="text-center text-nowrap align-middle" style="column-gap: 0.75rem;">
                <a href="" do="update" title="Ubah Fisik" data-id="{{ $item->fisik_id }}" class="text-gray-400 px-2 text-decoration-none btn-form">
                    <i class="fa-solid fa-pen-to-square"></i>
                </a>
                <a href="" data-id="{{ $item->fisik_id }}" class="text-danger px-2 text-decoration-none btn-delete">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="12" height="12">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </a>
            </td>
        </tr>
<?php  } } ?>

        <tr class="small">
            <td colspan="{{ $colspan }}">Jumlah data : {{ $total_records }}</td>
        </tr>