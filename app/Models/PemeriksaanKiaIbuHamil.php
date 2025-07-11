<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class PemeriksaanKiaIbuHamil extends Model
{
    protected $table = 'pemeriksaan_kia_ibu_hamil';

    protected $fillable = [
        'pendaftaran_id','nomor_periksa',
        'keluhan', 'riw_penyakit', 'td', 'bb', 'tb', 'suhu', 'saturasiOx', 'nadi', 'lila',
        'hpht', 'hpl', 'gpa', 'riwayatkehamilankesehatan', 'umr_hamil', 'riwayat_TT', 'tablet_tambah_darah', 'vitamin_mineral', 'asam_folat', 
        'tifu', 'djj', 'ltkjanin', 'ktrkuterus', 'refla', 'lab', 'resti', 'diagnosa',
        'intervensi', 'tindak_lnjt', 'tgl_kembali'
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class);
    }
  public function obat()
{
    return $this->morphToMany(Obat::class, 'pemeriksaanable', 'obat_pemeriksaan')
                ->withPivot('dosis_carkai', 'jumlah_obat')
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
    public function getObatAttribute()
{
    return $this->obatPemeriksaan->map(function ($item) {
        return $item->obat;
    })->filter(); // Pastikan hanya yang ada relasi obat-nya
}

}