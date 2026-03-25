<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fisik extends Model
{
    protected $table = 'monev_fisik';
    protected $primaryKey = 'fisik_id';
    public $timestamps = false;

    protected $fillable = [
        'fisik_id',
        'fisik_uid',
        'fisik_instansi_kode',
        'fisik_subkegiatan_kode',
        'fisik_kode',
        'fisik_tahapan',
        'fisik_nomor',
        'fisik_aktivitas',
        'fisik_acuan',
        'fisik_1',
        'fisik_2',
        'fisik_3',
        'fisik_4',
        'fisik_5',
        'fisik_6',
        'fisik_7',
        'fisik_8',
        'fisik_9',
        'fisik_10',
        'fisik_11',
        'fisik_12',
        'fisik_tahun',
        'fisik_sesi_kode',
        'fisik_jenis',
        'fisik_status'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['instansi_kode'] ?? false, function ($query, $q) {
            return $query->where('fisik_instansi_kode', $q);
        });
        $query->when($filters['tahun'] ?? false, function ($query, $q) {
            return $query->where('fisik_tahun', $q);
        });
        $query->when($filters['sesi'] ?? false, function ($query, $q) {
            return $query->where('fisik_sesi_kode', $q);
        });
        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->where('fisik_subkegiatan_kode', 'LIKE', '%' . $q . '%')->orWhereRelation('nomenklatur', 'nomenklatur_nama', 'LIKE', '%' . $q . '%');
        });
    }

    public function lampiran_fisik()
    {
        return $this->hasOne(LampiranFisik::class, 'lampiran_kode', 'fisik_uid');
    }

    public function nomenklatur()
    {
        return $this->belongsTo(NomenklaturPerencanaan::class, 'fisik_subkegiatan_kode', 'nomenklatur_kode');
    }

}
