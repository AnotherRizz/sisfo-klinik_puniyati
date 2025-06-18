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
     Schema::create('bidan', function (Blueprint $table) {
    $table->id();
    $table->string('kd_bidan', 10)->unique();
    $table->char('nama_bidan', 50);
    $table->string('alamat');
    $table->string('no_telp'); // ubah ke string
    $table->text('jadwal');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidan');
    }
};