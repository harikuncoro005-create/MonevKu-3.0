<div>
    <div class="my-3 d-flex justify-content-start">
        <form action="{{ $url_export }}" method="POST" target="_blank">
            @csrf
            <input type="hidden" name="pd" value="{{ $instansi->instansi_kode }}">
            <button type="submit" class="btn btn-sm btn-success text-nowrap btn-export" style="width: 8rem" title="Export Laporan APBD Bulanan"><i class="fa-regular fa-file-excel"></i> Export Excel</button>
        </form>
    </div>
    
    <div class="table-responsive" style="max-height: 45em; overflow-y: auto">
        <table class="table table-sm table-hover table-bordered">
            <thead style="font-weight: 500; font-size: 0.9rem; line-height: 1rem; background-color: #f9fafb">
                <tr class="text-center small">
                    <td rowspan="3" style="vertical-align: middle; min-width: 8rem;">NO</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 8rem;">SASARAN</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 8rem;">KODE</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 18rem;">PROGRAM / KEGIATAN / SUB KEGIATAN</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 18rem;">INDIKATOR KINERJA</td>
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 4rem;">TARGET RENSTRA PD PADA TAHUN {{ session('session_renstra.sesi_renstra_tahun_selesai') }}</td>
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 6rem;">REALISASI CAPAIAN KINERJA RENSTRA S.D. RENJA TAHUN {{ (session('session_tahun')-1) }}</td>
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 6rem;">TARGET KINERJA & ANGGARAN RENJA {{ session('session_tahun') }}</td>
                    <td colspan="8" style="vertical-align: middle; min-width: 24rem;">REALISASI KINERJA PADA TRIWULAN</td>
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 6rem;">REALISASI CAPAIAN KINERJA & ANGGARAN RENJA S.D. {{ $triwulan['nama'] }} TAHUN {{ session('session_tahun') }}</td>
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 6rem;">REALISASI KINERJA & ANGGARAN RENSTRA S.D. {{ $triwulan['nama'] }} TAHUN {{ session('session_tahun') }}</td>
                    <td rowspan="2" colspan="2" style="vertical-align: middle; min-width: 6rem;">TINGKAT CAPAIAN KINERJA & ANGGARAN RENSTRA S.D. {{ $triwulan['nama'] }} TAHUN {{ session('session_tahun') }}</td>
                    <td rowspan="3" style="vertical-align: middle; min-width: 6rem;">UNIT PERANGKAT DAERAH PENANGGUNG JAWAB</td>
                </tr>
                <tr class="text-center small">
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">I</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">II</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">III</td>
                    <td colspan="2" style="vertical-align: middle; min-width: 6rem;">IV</td>
                </tr>
                <tr class="text-center small">
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                    <td style="vertical-align: middle; min-width: 2rem;">K</td>
                    <td style="vertical-align: middle; min-width: 4rem;">Rp</td>
                </tr>
                <tr class="text-center small">
                    <td style="vertical-align:">1</td>
                    <td style="vertical-align:">2</td>
                    <td style="vertical-align:"></td>
                    <td style="vertical-align:">3</td>
                    <td style="vertical-align:">4</td>
                    <td colspan="2" style="vertical-align:">5</td>
                    <td colspan="2" style="vertical-align:">6</td>
                    <td colspan="2" style="vertical-align:">7</td>
                    <td colspan="2" style="vertical-align:">8</td>
                    <td colspan="2" style="vertical-align:">9</td>
                    <td colspan="2" style="vertical-align:">10</td>
                    <td colspan="2" style="vertical-align:">11</td>
                    <td colspan="2" style="vertical-align:">12</td>
                    <td colspan="2" style="vertical-align:">13=6+12</td>
                    <td colspan="2" style="vertical-align:">14=13/5x100%</td>
                    <td style="vertical-align:">15</td>
                </tr>
            </thead>
            <tbody class="text-small text-gray-500" style="line-height: 1;">
                
                <?php if(count($akun['akun_2']) == 0) { ?>
                <tr class="">
                    <td class="text-center text-small text-gray-400">Tidak Ditemukan</td>
                </tr>
                <?php } else {
                    foreach ($akun['akun_2'] as $val_2) { ?>
                        <tr style="line-height: 1;" class="alert-success font-weight-bold small">
                            <td></td>
                            <td></td>
                            <td><?= $val_2['akun_kode'] ?></td>
                            <td><strong><?= $val_2['akun_nama'] ?></strong></td>
                            <td class="text-right" style="vertical-align: middle;">
                            
                            </td>
                        </tr>
                        <?php 
                            $akun_3 = array_filter($akun['akun_3'], function ($var) use ($val_2) {
                                return $var['index_parent'] == $val_2['index_id'];
                            });
                        ?>

                        <?php if(count($akun_3) > 0) {
                            foreach ($akun_3 as $val_3) { ?>
                            <tr style="line-height: 1;" class="font-weight-bold small">
                                <td></td>
                                <td></td>
                                <td><?= $val_3['akun_kode'] ?></td>
                                <td><div><?= $val_3['akun_nama'] ?></div></td>
                                <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                <td class="text-right">
                                    @php
                                        $keuangan_3 = array_filter($keuangan->toArray(), function ($var) use ($val_3) {
                                            return $var['keuangan_program_kode'] == $val_3['akun_kode'];
                                        });
                                        
                                        $program = collect($keuangan_3)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($program));
                
                                    @endphp
                                </td>
                                {{-- <td></td> --}}
                                {{-- <td class="text-right">{{ str_replace(',','.', number_format((isset($keuangan_realisasi_bulan_program[$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan_program[$val_3['akun_kode']]) : 0))) }}</td> --}}
                                @if ($triwulan['id'] == 1)
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                @elseif ($triwulan['id'] == 2)
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_2'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_2'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>

                                @elseif ($triwulan['id'] == 3)
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_2'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_2'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_3'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_3'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td></td>

                                @elseif ($triwulan['id'] == 4)
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_1'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_2'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_2'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_3'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_3'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_4'][$val_3['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_program_4'][$val_3['akun_kode']]) : 0)) }}
                                    </td>
                                @else
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @endif

                                <td></td>
                                <td class="text-right">
                                    {{ str_replace(',','.', number_format($keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_program'][$val_3['akun_kode']] ?? 0)) }}
                                </td>

                            </tr>
                            <?php 
                                $akun_5 = array_filter($akun['akun_5'], function ($var) use ($val_3) {
                                    return $var['index_parent'] == $val_3['index_id'];
                                });
                            ?>

                            <?php if(count($akun_5) > 0) {
                                foreach ($akun_5 as $val_5) { ?> 
                                <tr style="line-height: 1;" class="text-primary small">
                                    <td></td>
                                    <td></td>
                                    <td><?= $val_5['akun_kode'] ?></td>
                                    <td><div><?= $val_5['akun_nama'] ?></div></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-right">
                                        @php
                                            $keuangan_5 = array_filter($keuangan->toArray(), function ($var) use ($val_5) {
                                                return $var['keuangan_kegiatan_kode'] == $val_5['akun_kode'];
                                            });
                                            
                                            $kegiatan = collect($keuangan_5)->pluck('keuangan_pagu')->sum(); echo str_replace(',','.', number_format($kegiatan));
                                        
                                        @endphp
                                    </td>
                                    {{-- <td></td> --}}
                                    {{-- <td>{{ array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1']['5.01.01.2.02']) }}</td> --}}
                                    {{-- <td class="text-right">{{ str_replace(',','.', number_format((isset($keuangan_realisasi_bulan_kegiatan[$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan_kegiatan[$val_5['akun_kode']]) : 0))) }}</td> --}}

                                    @if ($triwulan['id'] == 1)
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    @elseif ($triwulan['id'] == 2)
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_2'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_2'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>

                                    @elseif ($triwulan['id'] == 3)
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                           {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_2'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_2'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                           {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_3'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_3'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td></td>

                                    @elseif ($triwulan['id'] == 4)
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_1'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_2'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_2'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_3'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_3'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format(isset($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_4'][$val_5['akun_kode']]) ? array_sum($keuangan_realisasi_bulan['keuangan_realisasi_bulan_kegiatan_4'][$val_5['akun_kode']]) : 0)) }}
                                        </td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif

                                    <td></td>
                                    <td class="text-right">
                                        {{ str_replace(',','.', number_format($keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_kegiatan'][$val_5['akun_kode']] ?? 0)) }}
                                    </td>

                                </tr>
                                <?php 
                                    $akun_6 = array_filter($akun['akun_6'], function ($var) use ($val_5) {
                                        return $var['index_parent'] == $val_5['index_id'];
                                    });
                                ?>
                                <?php if(count($akun_6) > 0) {
                                    foreach ($akun_6 as $val_6) { ?>
                                    <tr style="line-height: 1;" class="small">
                                        <td></td>
                                        <td></td>
                                        <td style="white-space: nowrap;">
                                            <?= $val_6['akun_kode'] ?>
                                        </td>
                                        <td>
                                            <em><?= $val_6['akun_nama'] ?></em>
                                        </td>

                                        <td>
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_nama : '' }}
                                        </td>
                                        <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                        <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                        <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                        <td class="text-center text-gray-300"><em>Belum Tersedia</em></td>
                                        <td class="text-center">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_target : '' }}
                                        </td>
                                        <td class="text-right">{{ str_replace(',','.', number_format($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_pagu)) }}</td>

                                        @if ($triwulan['id'] == 1)
                                            <td class="text-center">
                                                {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                            </td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_1'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        @elseif ($triwulan['id'] == 2)
                                            <td></td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_1'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td class="text-center">
                                                {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                            </td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_2'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                        @elseif ($triwulan['id'] == 3)
                                            <td></td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_1'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_2'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td class="text-center">
                                                {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                            </td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_3'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td></td>
                                            <td></td>

                                        @elseif ($triwulan['id'] == 4)
                                            <td></td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_1'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_2'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_3'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                            <td class="text-center">
                                                {{ array_key_exists($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode, $keluaran_realisasi_bulan) ? $keluaran_realisasi_bulan[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode]:0}}
                                            </td>
                                            <td class="text-right">
                                                {{ str_replace(',','.', number_format($keuangan_realisasi_bulan['keuangan_realisasi_bulan_4'][$val_6['akun_kode']] ?? 0)) }}
                                            </td>
                                        @else
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        @endif

                                        <td></td>
                                        <td class="text-right">
                                            {{ str_replace(',','.', number_format($keuangan_realisasi_kumulatif_bulan['realisasi_keuangan_subkegiatan'][$val_6['akun_kode']] ?? 0)) }}
                                        </td>

                                        

                                        {{-- <td class="text-center">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_target : '' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_satuan : '' }}
                                        </td>

                                        @foreach ($bulan as $index => $value)
                                            <td class="text-center" style="vertical-align:">{{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->{'keluaran_'.$index} : 0 }}</td>
                                            <td class="text-center" style="vertical-align:">{{ str_replace(".", ",", sprintf('%.2f', $fisik[$val_6['akun_kode']][$index] ?? 0)) }}</td>
                                            <td class="text-right" style="vertical-align:">{{ str_replace(',','.', number_format($keuangan->has($val_6['akun_kode']) ? $keuangan->get($val_6['akun_kode'])->{'keuangan_'.$index} : 0)) }}</td>
                                        @endforeach

                                        <td class="text-center font-weight-bold" style="vertical-align: middle;">
                                            {{ $keluaran->has($val_6['akun_kode']) ? $keluaran->get($val_6['akun_kode'])->keluaran_target : 0 }}
                                        </td>

                                        <td class="text-center font-weight-bold" style="vertical-align: middle;">
                                            {{ str_replace(".", ",", sprintf('%.2f', isset($fisik[$val_6['akun_kode']]) ? collect($fisik[$val_6['akun_kode']])->sum() : 0)) }}
                                        </td>

                                        <td class="text-right font-weight-bold" style="vertical-align: middle;">
                                            {{ str_replace(',','.', number_format($keuangan->has($val_6['akun_kode']) ? $keuangan->get($val_6['akun_kode'])->keuangan_pagu : 0)) }}
                                        </td> --}}
                                        
                                    </tr>
                                    {{-- @if($keluaran_riil->has($keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode))
                                        @foreach ($keluaran_riil[$keuangan->keyBy('keuangan_subkegiatan_kode')[$val_6['akun_kode']]->keuangan_subkegiatan_kode] as $item)
                                            <tr style="line-height: 1;" class="small">
                                                <td><em>{{ $item->keluaran_nama??'' }}</em></td>
                                                <td class="text-center"><em>{{ $item->keluaran_target??'' }}</em></td>
                                                <td class="text-center"><em>{{ $item->keluaran_satuan??'' }}</em></td>
                                                <td colspan="39"></td>
                                            </tr>
                                        @endforeach                              
                                    @endif                               --}}
                                <?php } } ?>

                            <?php } } ?>

                        <?php } } ?>
                
                <?php  } } ?>
            </tbody> 

        </table>
    </div>
</div>