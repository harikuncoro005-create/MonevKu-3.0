<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iuran extends Model
{
    protected $table = 'bps_iuran';
    protected $primaryKey = 'iuran_id';
    public $timestamps = false;

    protected $fillable = [
        'iuran_id',
        'iuran_nama',
        'iuran_keterangan',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->where('iuran_nama', 'LIKE', '%' . $q . '%');
        });
    }

    public function partisipan()
    {
        return $this->hasMany(Partisipan::class, 'partisipan_iuran_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pembayaran_iuran_id');
    }



}
