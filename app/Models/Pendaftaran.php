<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'noreg',
        'pasien_id',
        'bidan_id',
        'tgl_daftar',
        'jam_daftar',
        'pelayanan_id',
        'jenis_kunjungan',
    ];

    // Relasi ke pasien
   public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id', 'id');
    }
    public function pembayaran()
{
    return $this->hasOne(Pembayaran::class, 'pendaftaran_id');
}

    public function obat(): BelongsTo
    {
        return $this->belongsTo(Obat::class);
    }

    // Relasi ke bidan
    public function bidan(): BelongsTo
    {
        return $this->belongsTo(Bidan::class);
    }

    // Relasi ke pelayanan
    public function pelayanan(): BelongsTo
    {
        return $this->belongsTo(Pelayanan::class);
    }
    public function pemeriksaanUmum(): HasOne
{
    return $this->hasOne(PemeriksaanUmum::class, 'pendaftaran_id');
}

public function pemeriksaanIbuNifas(): HasOne
{
    return $this->hasOne(PemeriksaanIbuNifas::class, 'pendaftaran_id');
}

public function pemeriksaanKb(): HasOne
{
    return $this->hasOne(PemeriksaanKb::class, 'pendaftaran_id');
}

public function pemeriksaanKiaAnak(): HasOne
{
    return $this->hasOne(PemeriksaanKiaAnak::class, 'pendaftaran_id');
}

public function pemeriksaanKiaIbuHamil(): HasOne
{
    return $this->hasOne(PemeriksaanKiaIbuHamil::class, 'pendaftaran_id');
}

public function pemeriksaan(): MorphTo
{
    return $this->morphTo();
}


}