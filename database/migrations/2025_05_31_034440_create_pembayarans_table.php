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
    $table->string('kd_bayar');
    $table->foreignId('pemeriksaan_id')->constrained('pemeriksaan');
    $table->date('tgl_bayar');
    $table->text('administrasi')->nullable();
    $table->string('biaya_administrasi')->nullable();
    $table->string('tindakan')->nullable();
    $table->string('biaya_tindakan')->nullable();
    $table->enum('jenis_bayar', ['Tunai', 'Transfer']);
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