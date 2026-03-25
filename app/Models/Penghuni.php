<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penghuni extends Model
{
    protected $table = 'bps_penghuni';
    protected $primaryKey = 'penghuni_id';
    public $timestamps = false;

    protected $fillable = [
        'penghuni_id',
        'penghuni_warga_id',
        'penghuni_penyewa_id',
        'penghuni_nama',
        'penghuni_nik',
        'penghuni_tempat_lahir',
        'penghuni_tanggal_lahir',
        'penghuni_status',
        'penghuni_kondisi'
    ];

    public function penyewa()
    {
        return $this->belongsTo(Penyewa::class, 'penghuni_penyewa_id', 'penyewa_id');
    }
}
