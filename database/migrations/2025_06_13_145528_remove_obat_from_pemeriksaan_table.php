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
             // Hapus foreign key constraint dulu
           
            $table->dropColumn('obat_id');
            $table->dropColumn('dosis_carkai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
                  $table->foreignId('obat_id')->constrained('obat');
            $table->text('dosis_carkai')->nullable();
        });
    }
};