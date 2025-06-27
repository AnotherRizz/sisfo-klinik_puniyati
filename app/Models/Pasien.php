<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $fillable = [
        'no_rm', 'nik_pasien', 'nama_pasien', 'tempt_lahir', 'tgl_lahir','umur','jenis_kelamin', 'alamat',
        'agama', 'pendidikan', 'new_column', 'pekerjaan', 'penanggungjawab', 'golda', 'no_tlp'
    ];
    // App\Models\Pasien.php
protected $casts = [
    'tgl_lahir' => 'date',
];


    public function pendaftaran() {
        return $this->hasMany(Pendaftaran::class, 'pasien_id');
    }

    public function pemeriksaan() {
        return $this->hasMany(Pemeriksaan::class, 'pasien_id');
    }

    public function pembayaran() {
        return $this->hasMany(Pembayaran::class, 'pasien_id');
    }
}