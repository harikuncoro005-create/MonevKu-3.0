
<div class="page-header mb-3 py-2 px-3 rounded shadow-sm" 
    style="background: #ffffff; border: 1px solid #e3e6f0; border-left: 4px solid #4e73df;">
    <div class="d-flex align-items-center justify-content-between">
        
        <div class="d-flex align-items-center">
            <div class="icon-box-slim mr-3 d-flex align-items-center justify-content-center" 
                style="width: 35px; height: 35px; background: rgba(78, 115, 223, 0.1); border-radius: 8px;">
                <i class="fa-solid fa-layer-group text-primary" style="font-size: 1.1rem;"></i>
            </div>
            
            <div>
                <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 1rem; letter-spacing: 0.5px;">
                    RENCANA KELUARAN
                </h5>
                <span class="text-muted" style="font-size: 0.7rem;">Tahun Anggaran {{ session('session_tahun') }}</span>
            </div>
        </div>
    </div>
</div>
<br>
<div class="table-responsive rounded" style="max-height: 40rem; overflow-y: auto">
    <table class="table table-hover mb-0">
        <thead style="font-weight: 500; font-size: 0.75rem; line-height: 1rem; background-color: #f9fafb">
            <tr class="text-center font-weight-bold">
                <th colspan="2" style="vertical-align: middle; min-width: 15rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</th>
                <th style="vertical-align: middle; min-width: 5rem">STATUS</th>
                <th style="vertical-align: middle;">AKSI</th>
            </tr>
            <tr style="line-height: 1;" class="bg-light font-weight-bold">
                <th colspan="2"><i class="fa-solid fa-building mr-2"></i> {{ $instansi->instansi_nama }}</th>
                <th></th>
                <th></th>
            </tr>  
        </thead>
        <tbody id="view-dashboard" class="text-small text-gray-500" style="line-height: 1; font-size: 0.75rem;">
            
            <?php if(count($akun['akun_2']) == 0) { ?>
            <tr>
                <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
            </tr>
            <?php } else {
                foreach ($akun['akun_2'] as $val_2) { ?>
                    <tr style="line-height: 1;" class="font-weight-bold">
                        <td><?= $val_2['akun_kode'] ?></td>
                        <td><strong><?= $val_2['akun_nama'] ?></strong></td>
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
                        <tr style="line-height: 1;" class="font-weight-bold bg-light">
                            <td><?= $val_3['akun_kode'] ?></td>
                            <td><div style="padding-left:10px"><?= $val_3['akun_nama'] ?></div></td>
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
                            <tr style="line-height: 1;" class="text-primary bg-light">
                                <td><?= $val_5['akun_kode'] ?></td>
                                <td><div style="padding-left:20px"><?= $val_5['akun_nama'] ?></div></td>
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
                                        @if ($keluaran->has($val_6['akun_kode']) && $keluaran->get($val_6['akun_kode'])->keluaran_status)
                                            <div class="d-inline-flex align-items-center status-badge-success">
                                                <div class="status-icon-wrapper-success">
                                                    <i class="fa-solid fa-check"></i>
                                                </div>
                                                <span class="status-text">Selesai</span>
                                            </div>
                                        @else
                                            <div class="d-inline-flex align-items-center status-badge-warning">
                                                <div class="status-icon-wrapper-warning">
                                                    <i class="fa-solid fa-spinner fa-spin-slow"></i>
                                                </div>
                                                <span class="status-text">Proses</span>
                                            </div>
                                        @endif
                                        {{-- <div class="d-inline-flex align-items-center status-badge-success">
                                            <div class="status-icon-wrapper-success">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <span class="status-text">Selesai</span>
                                        </div> --}}

                                        {{-- <div class="d-inline-flex align-items-center status-badge-danger">
                                            <div class="status-icon-wrapper-danger">
                                                <i class="fa-solid fa-xmark"></i>
                                            </div>
                                            <span class="status-text">Kosong</span>
                                        </div>

                                        <div class="d-inline-flex align-items-center status-badge-warning">
                                            <div class="status-icon-wrapper-warning">
                                                <i class="fa-solid fa-spinner fa-spin-slow"></i>
                                            </div>
                                            <span class="status-text">Proses</span>
                                        </div> --}}


                                        {{-- {!! $keluaran->has($val_6['akun_kode']) ? ($keluaran->get($val_6['akun_kode'])->keluaran_status ? '<span class="text-success"><i class="fa-solid fa-circle-check"></i></span>'  :  '<span class="text-danger"><i class="fa-solid fa-circle-xmark"></i></span>') : '<span class="text-danger"><i class="fa-solid fa-circle-xmark"></i></span>' !!} --}}
                                    </td>
                                    <td class="text-center">
                                        <a href="/rencana-keluaran/detail?ref={{ $val_6['akun_id'] }}" 
                                            class="btn btn-sm btn-action btn-primary" 
                                            title="Input Indikator">
                                                <i class="fa-solid fa-indent mr-1"></i> 
                                                <span>Detail</span>
                                            </a>
                                        {{-- <a class="bg-success rounded px-3 py-1 text-light text-decoration-none text-small text-nowrap" href="/rencana-keluaran/detail?ref={{ $val_6['akun_id'] }}">Input</a> --}}
                                    </td>
                                </tr>

                            
                            <?php } } ?>

                        <?php } } ?>

                    <?php } } ?>
                    
                <?php  ?>

            <?php  } } ?>


        </tbody>
    </table>
</div>
