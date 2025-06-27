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
        Schema::create('pemeriksaan_kb', function (Blueprint $table) {
             $table->id();
              $table->string('nomor_periksa', 10)->unique();
          $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->text('keluhan')->nullable();
            $table->text('riw_penyakit')->nullable();
            $table->integer('td')->nullable();
            $table->integer('bb')->nullable();
            $table->integer('tb')->nullable();
            $table->integer('suhu')->nullable();
            $table->integer('saturasiOx')->nullable();
            $table->text('alergi')->nullable();
            $table->date('hpht')->nullable();
            $table->integer('jmlhanak')->nullable();
            $table->date('tglpasang')->nullable();
            $table->enum('metode_kb', ['pil', 'Suntik', 'Implan', 'IUD', 'Kondom', 'MOW/MOP'])->nullable();
            $table->text('edukasi')->nullable();
            $table->text('intervensi')->nullable();
            $table->text('efek_samping')->nullable();
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
        Schema::dropIfExists('pemeriksaan_kb');
    }
};