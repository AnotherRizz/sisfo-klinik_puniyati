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
        Schema::create('pemeriksaan_kia_ibu_hamil', function (Blueprint $table) {
            $table->id(); // nomor_periksa
           $table->string('nomor_periksa', 10)->unique();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->text('keluhan')->nullable();
            $table->text('riw_penyakit')->nullable();
            $table->integer('td')->nullable();
            $table->integer('bb')->nullable();
            $table->integer('tb')->nullable();
            $table->integer('suhu')->nullable();
            $table->integer('saturasiOx')->nullable();
            $table->integer('nadi')->nullable();
            $table->integer('lila')->nullable();
            $table->date('hpht')->nullable();
            $table->date('hpl')->nullable();
            $table->string('gpa', 10)->nullable();
            $table->text('riwayatkehamilankesehatan')->nullable();
            $table->integer('umr_hamil')->nullable();
            $table->integer('ling_perut')->nullable();
            $table->integer('tifu')->nullable();
            $table->integer('djj')->nullable();
            $table->text('ltkjanin')->nullable();
            $table->enum('ktrkuterus', ['Ada', 'Tidak Ada'])->nullable();
            $table->text('refla')->nullable();
            $table->text('lab')->nullable();
            $table->text('resti')->nullable();
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
        Schema::dropIfExists('pemeriksaan__kia_ibu_hamil');
    }
};