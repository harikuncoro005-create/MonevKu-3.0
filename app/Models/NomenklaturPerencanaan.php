<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomenklaturPerencanaan extends Model
{
    protected $table = 'monev_nomenklatur_perencanaan';
    protected $primaryKey = 'nomenklatur_id';
    public $timestamps = false;

    protected $fillable = [
        'nomenklatur_id',
        'nomenklatur_kode',
        'nomenklatur_nama',
        'nomenklatur_tahun'
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->where('nomenklatur_kode', 'LIKE', '%' . $q . '%')->orWhere('nomenklatur_nama', 'LIKE', '%' . $q . '%');
        });

    }
}
