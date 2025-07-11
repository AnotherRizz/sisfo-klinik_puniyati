<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PemeriksaanUmum extends Model
{
    protected $table = 'pemeriksaan_umum';

    protected $fillable = [
        'pendaftaran_id','nomor_periksa',
        'keluhan', 'riw_penyakit', 'riw_alergi','pemeriksaan_penunjang',
        'td', 'bb', 'tb', 'suhu', 'saturasiOx',
        'diagnosa', 'tindakan', 'tindak_lnjt', 'tgl_kembali'
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pembayaran(): MorphOne
    {
        return $this->morphOne(Pembayaran::class, 'pemeriksaanable');
    }

    public function obatPemeriksaan(): MorphMany
    {
        return $this->morphMany(ObatPemeriksaan::class, 'pemeriksaanable');
    }
//     public function obat()
// {
//     return $this->belongsToMany(Obat::class, 'obat_pemeriksaan', 'pemeriksaan_id', 'obat_id');
// }
public function getObatAttribute()
{
    return $this->obatPemeriksaan->map(function ($item) {
        return $item->obat;
    })->filter(); // Pastikan hanya yang ada relasi obat-nya
}
public function obat()
{
    return $this->morphToMany(Obat::class, 'pemeriksaanable', 'obat_pemeriksaan')
        ->withPivot('dosis_carkai', 'jumlah_obat')
        ->withTimestamps();
}



}