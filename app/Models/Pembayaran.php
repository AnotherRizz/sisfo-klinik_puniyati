<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pembayaran extends Model
{
    use HasFactory;
     protected $table = 'pembayaran';

    protected $fillable = [
        'kd_bayar', 'pemeriksaan_id', 
        'tgl_bayar', 'administrasi', 'biaya_administrasi','biaya_konsultasi',
        'biaya_tindakan','tindakan',  'jenis_bayar'
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

  /* many-to-one (balikannya) */
    public function pemeriksaan(): BelongsTo
    {
        return $this->belongsTo(Pemeriksaan::class, 'pemeriksaan_id');
    }

public function pendaftaran()
{
    return $this->belongsTo(Pendaftaran::class);
}



    public function bidan()
    {
        return $this->belongsTo(Bidan::class);
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}