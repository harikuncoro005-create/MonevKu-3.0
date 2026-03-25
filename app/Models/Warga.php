<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $table = 'bps_warga';
    protected $primaryKey = 'warga_id';
    public $timestamps = false;

    protected $fillable = [
        'warga_id',
        'warga_nama',
        'warga_no_rumah',
        'warga_hp',
        'warga_status',
        'warga_keterangan',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->where('warga_nama', 'LIKE', '%' . $q . '%')->orWhere('warga_no_rumah', 'LIKE', '%' . $q . '%');
        });

        $query->when($filters['status'] ?? false, function ($query, $q) {
            return $query->where('warga_status', $q);
        });
    }

    public function penyewa()
    {
        return $this->hasMany(Penyewa::class, 'penyewa_warga_id');
    }



}
