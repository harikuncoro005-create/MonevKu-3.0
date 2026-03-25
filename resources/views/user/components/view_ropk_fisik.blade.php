<div class="bg-white rounded my-3 shadow-sm">
    <div class="d-flex flex-row align-items-center" style="column-gap:0.5rem;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
        </svg>
        <span>ROPK FISIK</span>
    </div>
    <br>
    <div class="table-responsive" style="max-height: 40rem; overflow-y: auto">
        <table class="table table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center">
                    <td colspan="2" style="vertical-align: middle; min-width: 5rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                    <td style="vertical-align: middle;">TOTAL</td>
                    <td style="vertical-align: middle;">STATUS</td>
                    <td style="vertical-align: middle;">AKSI</td>
                </tr>
                <tr style="line-height: 1;" class="alert-warning font-weight-bold">
                    <td colspan="2">{{ $instansi->instansi_nama }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>  
            </thead>
            <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1;">
                
                <?php if(count($akun['akun_2']) == 0) { ?>
                <tr>
                    <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
                </tr>
                <?php } else {
                    foreach ($akun['akun_2'] as $val_2) { ?>
                        <tr style="line-height: 1;" class="alert-success font-weight-bold">
                            <td><?= $val_2['akun_kode'] ?></td>
                            <td><strong><?= $val_2['akun_nama'] ?></strong></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php 
                            $akun_3 = array_filter($akun['akun_3'], function ($var) use ($val_2) {
                                return $var['index_parent'] == $val_2['index_id'];
                            });
                        ?>

                        <?php if(count($akun_3) > 0) {
                            foreach ($akun_3 as $val_3) { ?>
                            <tr style="line-height: 1;" class="font-weight-bold">
                                <td><?= $val_3['akun_kode'] ?></td>
                                <td><div style="padding-left:10px"><?= $val_3['akun_nama'] ?></div></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php 
                                $akun_5 = array_filter($akun['akun_5'], function ($var) use ($val_3) {
                                    return $var['index_parent'] == $val_3['index_id'];
                                });
                            ?>

                            <?php if(count($akun_5) > 0) {
                                foreach ($akun_5 as $val_5) { ?> 
                                <tr style="line-height: 1;" class="text-primary">
                                    <td><?= $val_5['akun_kode'] ?></td>
                                    <td><div style="padding-left:20px"><?= $val_5['akun_nama'] ?></div></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <?php 
                                    $akun_6 = array_filter($akun['akun_6'], function ($var) use ($val_5) {
                                        return $var['index_parent'] == $val_5['index_id'];
                                    });
                                ?>
                                <?php if(count($akun_6) > 0) {
                                    foreach ($akun_6 as $val_6) { ?>
                                    <tr style="line-height: 1;" class="">
                                        <td><?= $val_6['akun_kode'] ?></td>
                                        <td><div style="padding-left:30px"><?= $val_6['akun_nama'] ?></div></td>
                                        <td class="text-center">
                                            {{ $fisik_total[$val_6['akun_kode']] }}
                                        </td>
                                        <td class="text-center">
                                            {!! $fisik_status[$val_6['akun_kode']] ? '<span class="text-success"><i class="fa-solid fa-circle-check"></i></span>' : '<span class="text-danger"><i class="fa-solid fa-circle-xmark"></i></span>' !!}
                                        </td>
                                        <td class="text-center"><a class="bg-success rounded px-3 py-1 text-light text-decoration-none text-small text-nowrap" href="/ropk-fisik/detail?ref={{ $val_6['akun_id'] }}">Input</a></td>
                                    </tr>

                                
                                <?php } } ?>

                            <?php } } ?>

                        <?php } } ?>
                        
                    <?php  ?>

                <?php  } } ?>


            </tbody>
        </table>
    </div>
</div>