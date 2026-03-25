<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Admin extends Authenticatable
{
    protected $table = 'monev_admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false;

    protected $casts = [
        'admin_otorisasi' => 'array',
    ];

    protected $fillable = [
        'admin_id',
        'username',
        'password',
        'admin_nama',
        'admin_otorisasi',
        'admin_role',
        'admin_aktif',
    ];
}
