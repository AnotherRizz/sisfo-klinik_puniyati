<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Obat extends Model
{
    protected $table = 'obat';

    protected $fillable = [
        'kd_obat',
        'nama_obat',
        'jenis_obat',
        'stok_obat',
        'harga_beli',
        'harga_jual'
    ];

    /**
     * Obat digunakan di banyak pemeriksaan (relasi many-to-many)
     */
    public function pemeriksaans(): BelongsToMany
    {
        return $this->belongsToMany(Pemeriksaan::class, 'obat_pemeriksaan')
                    ->withPivot('dosis')
                    ->withTimestamps();
    }
}