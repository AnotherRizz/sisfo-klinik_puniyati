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
        Schema::table('pemeriksaan_kia_ibu_hamil', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn(['tablet_tambah_darah', 'vitamin_mineral', 'asam_folat']);

            // Tambah kolom baru
            $table->foreignId('vitamin_suplemen')->nullable()->constrained('obat')->onDelete('cascade');
            $table->string('dosis')->nullable();
            $table->integer('jumlah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan_kia_ibu_hamil', function (Blueprint $table) {
            // Drop kolom baru
            $table->dropForeign(['vitamin_suplemen']);
            $table->dropColumn(['vitamin_suplemen', 'dosis', 'jumlah']);

            // Kembalikan kolom lama
            $table->boolean('tablet_tambah_darah')->nullable();
            $table->string('vitamin_mineral')->nullable();
            $table->string('asam_folat')->nullable();
        });
    }
};