<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lampiran extends Model
{
    protected $table = 'monev_lampiran';
    protected $primaryKey = 'lampiran_id';
    public $timestamps = false;

    protected $fillable = [
        'lampiran_fisik_id',
        'lampiran_keluaran_id',
        'lampiran_tipe',
        'lampiran_filename',
        'lampiran_bulan'
    ];

}
