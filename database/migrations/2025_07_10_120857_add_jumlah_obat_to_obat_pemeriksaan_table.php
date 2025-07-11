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
        Schema::table('obat_pemeriksaan', function (Blueprint $table) {
            $table->integer('jumlah_obat')
                  ->after('dosis_carkai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat_pemeriksaan', function (Blueprint $table) {
             $table->dropColumn('jumlah_obat');
        });
    }
};