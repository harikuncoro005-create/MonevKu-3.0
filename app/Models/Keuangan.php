<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table = 'monev_keuangan';
    protected $primaryKey = 'keuangan_id';
    public $timestamps = false;

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['instansi_kode'] ?? false, function ($query, $q) {
            return $query->where('keuangan_instansi_kode', $q);
        });
        $query->when($filters['tahun'] ?? false, function ($query, $q) {
            return $query->where('keuangan_tahun', $q);
        });
        $query->when($filters['sesi'] ?? false, function ($query, $q) {
            return $query->where('keuangan_sesi_kode', $q);
        });
        $query->when($filters['jenis'] ?? false, function ($query, $q) {
            return $query->where('keuangan_jenis', $q);
        });
        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->where('keuangan_subkegiatan_kode', 'LIKE', '%' . $q . '%')->orWhereRelation('nomenklatur', 'nomenklatur_nama', 'LIKE', '%' . $q . '%');
        });
    }

    public function nomenklatur()
    {
        return $this->hasMany(NomenklaturPerencanaan::class, 'nomenklatur_kode', 'keuangan_subkegiatan_kode');
    }


    // public function nomenklatur($subkegiatan, $tahun)
    // {
    //     $query->when($filters['q'] ?? false, function ($query, $q) {
    //         return $query->where('nomeklatur_nama', 'LIKE', '%' . $q . '%');
    //     });
    // }
}
