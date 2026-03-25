<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenilaianRencana extends Model
{
    protected $table = 'monev_penilaian_perencanaan';
    protected $primaryKey = 'penilaian_rencana_id';
    public $timestamps = false;

    protected $fillable = [
        'penilaian_rencana_id',
        'penilaian_rencana_nama',
        'penilaian_rencana_bulan',
        'penilaian_rencana_deadline',
        'penilaian_rencana_tahun',
    ];

    public function item()
    {
        return $this->hasMany(PenilaianRencanaItem::class, 'penilaian_rencana_item_rencana_id', 'penilaian_rencana_id');
    }
}
