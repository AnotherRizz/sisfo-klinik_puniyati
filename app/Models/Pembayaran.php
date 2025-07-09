<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'kd_bayar',
        'pemeriksaanable_id',
        'pemeriksaanable_type',
        'tgl_bayar',
        'administrasi',
        'biaya_administrasi',
        'biaya_konsultasi',
        'biaya_tindakan',
        'tindakan',
        'jenis_bayar',
    ];

    /**
     * Relasi polymorphic ke berbagai jenis pemeriksaan (umum, kb, kia, dll)
     */
    public function pemeriksaanable(): MorphTo
    {
        return $this->morphTo();
    }

  
}