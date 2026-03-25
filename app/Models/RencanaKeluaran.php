<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RencanaKeluaran extends Model
{
    protected $table = 'monev_rencana_keluaran';
    protected $primaryKey = 'rencana_keluaran_id';
    public $timestamps = false;

    protected $fillable = [
        'rencana_keluaran_id',
        'rencana_keluaran_keluaran_id',
        'rencana_keluaran_instansi_kode',
        'rencana_keluaran_subkegiatan_kode',
        'rencana_keluaran_1',
        'rencana_keluaran_2',
        'rencana_keluaran_3',
        'rencana_keluaran_4',
        'rencana_keluaran_5',
        'rencana_keluaran_6',
        'rencana_keluaran_7',
        'rencana_keluaran_8',
        'rencana_keluaran_9',
        'rencana_keluaran_10',
        'rencana_keluaran_11',
        'rencana_keluaran_12',
        'rencana_keluaran_tahun',
        'rencana_keluaran_sesi_kode',
        'rencana_keluaran_jenis',
        'rencana_keluaran_status',
    ];
}
