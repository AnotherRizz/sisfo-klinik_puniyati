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
      Schema::create('obat', function (Blueprint $table) {
    $table->id();
    $table->string('kd_obat', 10)->unique();
    $table->string('nama_obat');
    $table->string('jenis_obat');
    $table->integer('stok_obat');
    $table->integer('harga_beli');
    $table->integer('harga_jual');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};