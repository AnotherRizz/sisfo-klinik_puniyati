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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('kd_bayar', 10)->unique();
            $table->unsignedBigInteger('pemeriksaanable_id');
            $table->string('pemeriksaanable_type'); // model pemeriksaan
            $table->date('tgl_bayar')->nullable();
            $table->string('administrasi')->nullable();
            $table->string('biaya_administrasi')->nullable();
            $table->string('biaya_konsultasi')->nullable();
            $table->string('biaya_tindakan')->nullable();
            $table->string('tindakan')->nullable();
            $table->enum('jenis_bayar',["Tunai","Transfer"])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};