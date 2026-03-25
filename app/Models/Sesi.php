<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesi extends Model
{
    protected $table = 'monev_sesi';
    protected $primaryKey = 'sesi_id';
    public $timestamps = false;

    protected $fillable = [
        'sesi_id',
        'sesi_kode',
        'sesi_nama',
        'sesi_tanggal',
        'sesi_tahun',
        'sesi_keterangan',
        'sesi_status'
    ];
}
