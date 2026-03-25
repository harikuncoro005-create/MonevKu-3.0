<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'bps_dokumen';
    protected $primaryKey = 'dokumen_id';
    public $timestamps = false;

    protected $fillable = [
        'dokumen_id',
        'dokumen_partisipan_id',
        'dokumen_nama',
        'dokumen_file',
    ];
}
