<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelayanan extends Model
{
     protected $table = 'pelayanan';

    protected $fillable = [
        'kodpel',
        'nama_pelayanan',
       
    ];
}