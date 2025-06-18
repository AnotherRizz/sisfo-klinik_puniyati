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
       Schema::table('pembayaran', function (Blueprint $table) {
    $table->dropForeign(['pasien_id']);
    $table->dropColumn('pasien_id');

    $table->dropForeign(['bidan_id']);
    $table->dropColumn('bidan_id');

    $table->dropForeign(['obat_id']);
    $table->dropColumn('obat_id');
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