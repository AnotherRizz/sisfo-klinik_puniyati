<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ObatPemeriksaan extends Pivot
{
    protected $table = 'obat_pemeriksaan';

      protected $fillable = [
        'pemeriksaanable_id',
        'pemeriksaanable_type',
        'obat_id',
        'dosis_carkai',
    ];

    public function pemeriksaanable(): MorphTo
    {
        return $this->morphTo();
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}