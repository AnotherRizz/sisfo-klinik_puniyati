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
            // Hapus foreign key terlebih dahulu jika ada
            if (Schema::hasColumn('pemeriksaan_kia_ibu_hamil', 'vitamin_suplemen')) {
                $table->dropForeign(['vitamin_suplemen']);
            }

            // Hapus kolom
            $table->dropColumn(['vitamin_suplemen', 'dosis', 'jumlah']);
        });   
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};