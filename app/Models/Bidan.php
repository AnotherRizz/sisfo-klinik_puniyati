<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bidan extends Model
{
    protected $table = 'bidan';

    protected $fillable = [
        'kd_bidan',
        'nama_bidan',
        'alamat',
        'no_telp',
        'jadwal',
    ];
}