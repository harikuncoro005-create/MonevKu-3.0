<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LampiranFisik extends Model
{
    protected $table = 'monev_lampiran_fisik';
    protected $primaryKey = 'lampiran_id';
    public $timestamps = false;

    protected $casts = [
        'lampiran_1' => 'array',
        'lampiran_2' => 'array',
        'lampiran_3' => 'array',
        'lampiran_4' => 'array',
        'lampiran_5' => 'array',
        'lampiran_6' => 'array',
        'lampiran_7' => 'array',
        'lampiran_8' => 'array',
        'lampiran_9' => 'array',
        'lampiran_10' => 'array',
        'lampiran_11' => 'array',
        'lampiran_12' => 'array'
    ];

    protected $fillable = [
        'lampiran_id',
        'lampiran_kode',
        'lampiran_1',
        'lampiran_2',
        'lampiran_3',
        'lampiran_4',
        'lampiran_5',
        'lampiran_6',
        'lampiran_7',
        'lampiran_8',
        'lampiran_9',
        'lampiran_10',
        'lampiran_11',
        'lampiran_12',
        'lampiran_tahun',
        'lampiran_sesi_kode',
    ];
}
