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
         Schema::table('pemeriksaan_umum', function (Blueprint $table) {
            $table->text('pemeriksaan_penunjang')->nullable()->after('tindakan');
        });
         Schema::table('pemeriksaan_kia_ibu_hamil', function (Blueprint $table) {
            $table->string('riwayat_TT',50)->nullable()->after('riw_penyakit');
            $table->string('tablet_tambah_darah',30)->nullable()->after('gpa');
            $table->string('vitamin_mineral',30)->nullable()->after('tablet_tambah_darah');
            $table->string('asam_folat',30)->nullable()->after('vitamin_mineral');
        });
         Schema::table('pemeriksaan_kia_anak', function (Blueprint $table) {
            $table->text('alergi_obat',50)->nullable()->after('lk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
           Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('harga_beli');
        });
           Schema::table('pemeriksaan_kia_ibu_hamil', function (Blueprint $table) {
            $table->dropColumn('ling_perut');
        });
           Schema::table('pemeriksaan_ibu_nifas', function (Blueprint $table) {
            $table->dropColumn('besarrahim');
        });
           Schema::table('pemeriksaan_kb', function (Blueprint $table) {
            $table->dropColumn(['efek_samping','tb','suhu','saturasiOx']);
        });

    }
};