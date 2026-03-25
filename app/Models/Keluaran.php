<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluaran extends Model
{
    protected $table = 'monev_keluaran';
    protected $primaryKey = 'keluaran_id';
    public $timestamps = false;

    protected $fillable = [
        'keluaran_id',
        'keluaran_uid',
        'keluaran_instansi_kode',
        'keluaran_subkegiatan_kode',
        'keluaran_tipe',
        'keluaran_kode',
        'keluaran_nama',
        'keluaran_satuan',
        'keluaran_target',
        'keluaran_1',
        'keluaran_2',
        'keluaran_3',
        'keluaran_4',
        'keluaran_5',
        'keluaran_6',
        'keluaran_7',
        'keluaran_8',
        'keluaran_9',
        'keluaran_10',
        'keluaran_11',
        'keluaran_12',
        'keluaran_tahun',
        'keluaran_sesi_kode',
        'keluaran_jenis',
        'keluaran_status'
    ];

    public function lampiran_keluaran()
    {
        return $this->hasOne(LampiranKeluaran::class, 'lampiran_kode', 'keluaran_uid');
    }

}
