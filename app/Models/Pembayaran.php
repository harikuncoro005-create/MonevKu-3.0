<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'bps_pembayaran';
    protected $primaryKey = 'pembayaran_id';
    public $timestamps = false;

    protected $fillable = [
        'pembayaran_id',
        'pembayaran_iuran_id',
        'pembayaran_partisipan_id',
        'pembayaran_tipe',
        'pembayaran_jumlah',
        'pembayaran_tanggal',
        'pembayaran_dokumen',
        'pembayaran_keterangan',
        'pembayaran_admin_id',
    ];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['bulan'] ?? false, function ($query, $q) {
            return $query->whereMonth('pembayaran_tanggal', $q);
        });

        $query->when($filters['tahun'] ?? false, function ($query, $q) {
            return $query->whereYear('created_at', $q);
        });

        $query->when($filters['q'] ?? false, function ($query, $q) {
            return $query->whereRelation('partisipan', 'partisipan_nama', 'LIKE', '%' . $q . '%')->orWhereRelation('partisipan', 'partisipan_alamat', 'LIKE', '%' . $q . '%');
        });
    }

    public function iuran()
    {
        return $this->belongsTo(Iuran::class, 'pembayaran_iuran_id', 'iuran_id');
    }

    public function partisipan()
    {
        return $this->belongsTo(Partisipan::class, 'pembayaran_partisipan_id', 'partisipan_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'pembayaran_admin_id', 'admin_id');
    }

}
