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
         Schema::create('pemeriksaan_kia_anak', function (Blueprint $table) {
            $table->id();
             $table->string('nomor_periksa', 10)->unique();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->text('keluhan')->nullable();
            $table->text('riw_penyakit')->nullable();
            $table->string('riw_imunisasi', 255)->nullable();
            $table->integer('bb')->nullable();
            $table->integer('tb')->nullable();
            $table->integer('suhu')->nullable();
            $table->integer('pb')->nullable();
            $table->integer('lk')->nullable();
            $table->string('diagnosa', 255)->nullable();
            $table->string('intervensi', 255)->nullable();
            $table->enum('tindak_lnjt', ['Puskesmas', 'klinik', 'Rumah Sakit','Tidak Dirujuk'])->nullable();
            $table->date('tgl_kembali')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_kia_anak');
    }
};