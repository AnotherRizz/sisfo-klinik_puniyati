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

    // Relasi di bawah ini sebaiknya DIHAPUS, karena sudah tersedia lewat pemeriksaanable

    // public function pasien() {
    //     return $this->belongsTo(Pasien::class);
    // }

    // public function pendaftaran() {
    //     return $this->belongsTo(Pendaftaran::class);
    // }

    // public function bidan() {
    //     return $this->belongsTo(Bidan::class);
    // }

    // public function obat() {
    //     return $this->belongsTo(Obat::class);
    // }
}