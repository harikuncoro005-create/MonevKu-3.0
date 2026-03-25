<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permasalahan extends Model
{
    protected $table = 'monev_permasalahan';
    protected $primaryKey = 'permasalahan_id';
    public $timestamps = false;

    protected $fillable = [
        'permasalahan_id',
        'permasalahan_kode',
        'permasalahan_instansi_kode',
        'permasalahan_subkegiatan_kode',
        'permasalahan_bulan',
        'permasalahan_deskripsi',
        'permasalahan_tindaklanjut',
        'permasalahan_verfikasi',
        'permasalahan_catatan',
        'permasalahan_tahun',
        'permasalahan_sesi_kode',
        'permasalahan_status'
    ];
}
