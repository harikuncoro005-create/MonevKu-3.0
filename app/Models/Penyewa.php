<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    protected $table = 'bps_penyewa';
    protected $primaryKey = 'penyewa_id';
    public $timestamps = false;

    protected $fillable = [
        'penyewa_id',
        'penyewa_warga_id',
        'penyewa_kepemilikan',
        'penyewa_kedudukan',
        'penyewa_status',
        'penyewa_awal',
        'penyewa_akhir',
        'penyewa_nama',
        'penyewa_hp',
        'penyewa_keterangan',
        'penyewa_dokumen',
        'penyewa_aktif',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class, 'penyewa_warga_id', 'warga_id');
    }

    public function penghuni()
    {
        return $this->hasMany(Penghuni::class, 'penghuni_penyewa_id');
    }


}
