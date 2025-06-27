<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePembayaranReplacePolymorphId extends Migration
{
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Tambahkan kolom baru
            $table->string('nomor_periksa')->nullable()->after('kd_bayar');
            $table->string('pemeriksaanable_type')->nullable()->change(); // agar tetap bisa null dulu
        });

        // Optional: Migrasi data nomor_periksa jika kamu ingin isi dari model lama
        // Tapi perlu manual mapping ID ke nomor_periksa

        // Hapus kolom pemeriksaanable_id lama
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('pemeriksaanable_id');
        });

        // Tambahkan index untuk performa
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->index(['nomor_periksa', 'pemeriksaanable_type'], 'pembayaran_nomor_type_index');
        });
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Rollback: tambahkan kembali pemeriksaanable_id
            $table->unsignedBigInteger('pemeriksaanable_id')->nullable()->after('kd_bayar');
            $table->dropColumn('nomor_periksa');
            $table->dropIndex('pembayaran_nomor_type_index');
        });
    }
}