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
        Schema::create('pemeriksaan_ibu_nifas', function (Blueprint $table) {
           $table->id();
           $table->string('nomor_periksa', 10)->unique();
             $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->text('keluhan')->nullable();
            $table->text('riw_penyakit')->nullable();
            $table->string('frek_kunjungan', 100)->nullable();
            $table->integer('td')->nullable();
            $table->integer('bb')->nullable();
            $table->integer('tb')->nullable();
            $table->integer('suhu')->nullable();
            $table->integer('saturasiOx')->nullable();
            $table->text('alergi')->nullable();
            $table->integer('tifu')->nullable();
            $table->text('lochea')->nullable();
            $table->text('payudara')->nullable();
            $table->text('lukajahit')->nullable();
            $table->date('tgllahir')->nullable();
            $table->text('tmptpersalinan')->nullable();
            $table->enum('bantupersalinan', ['Bidan', 'Dokter'])->nullable();
            $table->enum('jnspersalinan', ['Spontan', 'Cesar', 'Vacum'])->nullable();
            $table->string('besarrahim', 5)->nullable();
            $table->text('infeksi_kompli')->nullable();
            $table->text('edukasi')->nullable();
            $table->text('intervensi')->nullable();
            $table->string('diagnosa', 255)->nullable();
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
        Schema::dropIfExists('pemeriksaan_ibu_nifas');
    }
};