<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesiRenstra extends Model
{
    protected $table = 'monev_sesi_renstra';
    protected $primaryKey = 'sesi_renstra_id';
    public $timestamps = false;

    protected $fillable = [
        'sesi_renstra_id',
        'sesi_renstra_kode',
        'sesi_renstra_nama',
        'sesi_renstra_tahun_mulai',
        'sesi_renstra_tahun_selesai',
        'sesi_renstra_keterangan',
        'sesi_renstra_status'
    ];
}
