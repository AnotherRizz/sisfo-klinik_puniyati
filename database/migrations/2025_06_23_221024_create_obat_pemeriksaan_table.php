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
      Schema::create('obat_pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemeriksaanable_id');
            $table->string('pemeriksaanable_type'); // model: PemeriksaanUmum, PemeriksaanKiaIbu, dll
            $table->foreignId('obat_id')->constrained('obat')->onDelete('cascade');
            $table->string('dosis_carkai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat_pemeriksaan');
    }
};