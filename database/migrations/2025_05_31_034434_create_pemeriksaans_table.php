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
        Schema::create('pemeriksaan', function (Blueprint $table) {
    $table->id();
    $table->bigInteger('noreg')->unique();
    $table->foreignId('pendaftaran_id')->constrained('pendaftaran');
    $table->text('keluhan')->nullable();
    $table->text('riw_penyakit')->nullable();
    $table->string('riw_imunisasi')->nullable();
    $table->integer('td')->nullable();
    $table->integer('bb')->nullable();
    $table->integer('tb')->nullable();
    $table->integer('suhu')->nullable();
    $table->integer('saturasiOx')->nullable();
    $table->integer('lila')->nullable();
    $table->text('pemeriksaan_ibu_hamil')->nullable();
    $table->text('pemeriksaan_ibu_nifas_kb')->nullable();
    $table->string('diagnosa')->nullable();
    $table->string('tindakan')->nullable();
    $table->foreignId('obat_id')->constrained('obat');
    $table->text('dosis_carkai')->nullable();
    $table->date('tgl_kembali')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan');
    }
};