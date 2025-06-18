<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ObatPemeriksaan extends Pivot
{
    protected $table = 'obat_pemeriksaan';

    protected $fillable = [
        'pemeriksaan_id',
        'obat_id',
        'dosis_carkai',
    ];
}