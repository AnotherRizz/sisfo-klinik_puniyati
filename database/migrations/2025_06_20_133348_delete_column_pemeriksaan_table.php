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
            $table->dropColumn(['pemeriksaan_ibu_hamil', 'pemeriksaan_ibu_nifas_kb']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
            $table->text('pemeriksaan_ibu_hamil')->nullable();
            $table->text('pemeriksaan_ibu_nifas_kb')->nullable();
        });
    }
};