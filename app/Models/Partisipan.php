<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partisipan extends Model
{
    protected $table = 'bps_partisipan';
    protected $primaryKey = 'partisipan_id';
    public $timestamps = false;

    protected $fillable = [
        'partisipan_id',
        'partisipan_iuran_id',
        'partisipan_kategori',
        'partisipan_nama',
        'partisipan_alamat',
        'partisipan_hp',
        'partisipan_admin_id',
        'partisipan_keterangan',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['id'] ?? false, function ($query, $key) {
            return $query->where('partisipan_iuran_id', $key);
        });
        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->where('partisipan_nama', 'LIKE', '%' . $q . '%')->orWhere('partisipan_alamat', 'LIKE', '%' . $q . '%');
        });
        $query->when($filters['kategori'] ?? false, function ($query, $key) {
            return $query->where('partisipan_kategori', $key);
        });
    }

    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'partisipan_iuran_id', 'iuran_id');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'dokumen_partisipan_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pembayaran_partisipan_id');
    }



}
