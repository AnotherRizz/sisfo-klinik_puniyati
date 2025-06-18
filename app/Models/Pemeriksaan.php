<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';

    protected $fillable = [
        'no_periksa', 'pendaftaran_id', 'keluhan', 'riw_penyakit',
        'riw_imunisasi', 'td', 'bb', 'tb', 'suhu', 'saturasiOx', 'lila',
        'pemeriksaan_ibu_hamil', 'pemeriksaan_ibu_nifas_kb', 'diagnosa', 'tindakan',
        'tgl_kembali',
    ];

    /**
     * Pemeriksaan milik satu pendaftaran
     */
    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Relasi many-to-many ke obat melalui pivot table obat_pemeriksaan
     */
  public function obat(): BelongsToMany
{
    return $this->belongsToMany(Obat::class, 'obat_pemeriksaan')
                ->withPivot('dosis_carkai')
                ->withTimestamps();
}


    /**
     * Pemeriksaan punya satu pembayaran
     */
    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pemeriksaan_id');
    }

    /**
     * Relasi tambahan ke pasien, bidan, dan pelayanan jika memang diperlukan
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }
//     public function obat()
// {
//     return $this->belongsToMany(Obat::class, 'obat_pemeriksaan');
// }

    public function bidan(): BelongsTo
    {
        return $this->belongsTo(Bidan::class);
    }

    public function pelayanan(): BelongsTo
    {
        return $this->belongsTo(Pelayanan::class);
    }
}