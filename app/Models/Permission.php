<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'monev_auth';
    protected $primaryKey = 'auth_id';
    public $timestamps = false;

    protected $fillable = [
        'auth_id',
        'auth_uid',
        'auth_instansi_kode',
        'auth_tahun',
        'auth_1',
        'auth_2',
        'auth_3',
        'auth_4',
        'auth_5',
        'auth_6',
        'auth_7',
        'auth_8',
        'auth_9',
        'auth_10',
        'auth_11',
        'auth_12',
    ];
}
