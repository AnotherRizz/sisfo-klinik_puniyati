<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PemeriksaanKiaAnak extends Model
{
    protected $table = 'pemeriksaan_kia_anak';

    protected $fillable = [
        'pendaftaran_id','nomor_periksa',
        'keluhan', 'riw_penyakit', 'riw_imunisasi','alergi_obat',
        'bb', 'tb', 'suhu', 'pb', 'lk',
        'diagnosa', 'intervensi', 'tindak_lnjt', 'tgl_kembali'
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
    public function obat()
{
    return $this->morphToMany(Obat::class, 'pemeriksaanable', 'obat_pemeriksaan')
                ->withPivot('dosis_carkai')
                ->withTimestamps();
}
public function getObatAttribute()
{
    return $this->obatPemeriksaan->map(function ($item) {
        return $item->obat;
    })->filter(); // Pastikan hanya yang ada relasi obat-nya
}


}