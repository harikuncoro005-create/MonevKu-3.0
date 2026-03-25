<div>
    <div class="my-3 d-flex justify-content-start">
        <form action="{{ $url_export }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="bulan" value="{{ $bulan_index }}">
            <button type="submit" class="btn btn-sm btn-success text-nowrap btn-export" style="width: 8rem" title="Export Laporan APBD Bulanan"><i class="fa-solid fa-print"></i>Export PDF</button>
        </form>
    </div>
    
    <div class="table-responsive" style="max-height: 35rem; overflow-y: auto">
        <table class="table table-sm table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center small">
                    <td rowspan="3" style="vertical-align: middle; min-width: 8rem;">KODE</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 20rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 8rem;">PAGU</td>
                    <td colspan="4" style="vertical-align: middle; min-width: 18rem;">TARGET</td>
                    <td colspan="4" style="vertical-align: middle; min-width: 18rem;">REALISASI</td>
                    <td colspan="4" style="vertical-align: middle; min-width: 18rem;">DEVIASI</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 24rem;">PERMASALAHAN</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 12rem;">TINDAK LANJUT</td>
                </tr>
                <tr class="text-center small">
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">Keluaran</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 10rem;">Keuangan</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">Fisik</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">Keluaran</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 10rem;">Keuangan</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">Fisik</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">Keluaran</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 10rem;">Keuangan</td>
                    <td rowspan="2" style="vertical-align: middle; min-width: 4rem;">Fisik</td>
                </tr>
                <tr class="text-center small">
                    <td style="vertical-align: middle; min-width: 8rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">%</td>
                    <td style="vertical-align: middle; min-width: 8rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">%</td>
                    <td style="vertical-align: middle; min-width: 8rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">%</td>
                </tr>
            </thead>
            
            {!! $view !!}

        </table>
    </div>
</div>