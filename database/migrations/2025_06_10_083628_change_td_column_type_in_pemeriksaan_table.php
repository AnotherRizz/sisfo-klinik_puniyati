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
       Schema::table('pemeriksaan', function (Blueprint $table) {
            // Hapus kolom td dulu
            $table->dropColumn('td');
        });

        Schema::table('pemeriksaan', function (Blueprint $table) {
            // Tambahkan kembali sebagai string, setelah riw_imunisasi
            $table->string('td')->nullable()->after('riw_imunisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('pemeriksaan', function (Blueprint $table) {
            // Hapus kolom td dulu
            $table->dropColumn('td');
        });

        Schema::table('pemeriksaan', function (Blueprint $table) {
            // Tambahkan kembali sebagai integer, setelah riw_imunisasi
            $table->integer('td')->nullable()->after('riw_imunisasi');
        });
    }
};