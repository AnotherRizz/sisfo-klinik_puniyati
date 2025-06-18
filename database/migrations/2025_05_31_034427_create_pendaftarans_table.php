<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('pendaftaran', function (Blueprint $table) {
    $table->id();
    $table->bigInteger('noreg')->unique();
    $table->foreignId('pasien_id')->constrained('pasien');
    $table->foreignId('bidan_id')->constrained('bidan');
    $table->date('tgl_daftar');
    $table->time('jam_daftar');
    $table->foreignId('pelayanan_id')->constrained('pelayanan');
    $table->enum('jenis_kunjungan', ['LAMA', 'BARU']);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};