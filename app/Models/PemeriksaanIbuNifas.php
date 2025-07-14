<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PemeriksaanIbuNifas extends Model
{
    protected $table = 'pemeriksaan_ibu_nifas';

    protected $fillable = [
        'pendaftaran_id','nomor_periksa',
        'keluhan', 'riw_penyakit', 'frek_kunjungan', 'td', 'bb', 'tb', 'suhu', 'saturasiOx',
        'alergi', 'tifu', 'lochea', 'payudara', 'lukajahit',
        'tgllahir', 'tmptpersalinan', 'bantupersalinan', 'jnspersalinan', 
        'infeksi_kompli', 'edukasi', 'intervensi', 'diagnosa', 'tindak_lnjt', 'tgl_kembali'
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
    public function getObatAttribute()
{
    return $this->obatPemeriksaan->map(function ($item) {
        return $item->obat;
    })->filter(); // Pastikan hanya yang ada relasi obat-nya
}
public function obat()
{
    return $this->morphToMany(Obat::class, 'pemeriksaanable', 'obat_pemeriksaan')
                ->withPivot('dosis_carkai', 'jumlah_obat','vitamin_suplemen')
                ->withTimestamps();
}



    public function pembayaran(): MorphOne
    {
        return $this->morphOne(Pembayaran::class, 'pemeriksaanable');
    }

    public function obatPemeriksaan(): MorphMany
    {
        return $this->morphMany(ObatPemeriksaan::class, 'pemeriksaanable');
    }
}