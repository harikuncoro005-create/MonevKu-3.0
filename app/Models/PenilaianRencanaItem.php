<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianRencanaItem extends Model
{
    protected $table = 'monev_penilaian_perencanaan_item';
    protected $primaryKey = 'penilaian_rencana_item_id';
    public $timestamps = false;

    protected $fillable = [
        'penilaian_rencana_item_id',
        'penilaian_rencana_item_rencana_id',
        'penilaian_rencana_item_instansi_kode',
        'penilaian_rencana_item_tanggal',
        'penilaian_rencana_item_jumlah',
        'penilaian_rencana_item_nilai',
        'penilaian_rencana_item_lampiran',
    ];

    public function rencana()
    {
        return $this->belongsTo(PenilaianRencana::class, 'penilaian_rencana_item_rencana_id', 'penilaian_rencana_id');
    }
}
