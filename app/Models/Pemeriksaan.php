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
    'no_periksa', 
    'pendaftaran_id', 
    'keluhan', 
    'riw_penyakit', 
    'riw_imunisasi', 
    'riw_alergi', 
    'td', 
    'bb', 
    'tb', 
    'suhu', 
    'saturasiOx', 
    'nadi', 
    'lila', 
    'hpht', 
    'hpl', 
    'gpa', 
    'riwayat_kehamilan_kesehatan', 
    'umur_hamil', 
    'lingkar_perut', 
    'tifu', 
    'djj', 
    'ltkjanin', 
    'ktrkuterus', 
    'refla', 
    'lab', 
    'resti', 
    'intervensi', 
    'frek_kunjungan', 
    'riw_alergi', 
    'lochea', 
    'payudara', 
    'luka_jahit', 
    'tgl_lahir', 
    'tmpt_persalinan', 
    'bantu_persalinan', 
    'jns_persalinan', 
    'besar_rahim', 
    'infeksi_kompli', 
    'edukasi', 
    'jmlh_anak', 
    'tgl_pasang', 
    'metode_KB', 
    'efek_samping', 
    'diagnosa', 
    'tindakan', 
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