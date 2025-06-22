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
            $table->enum('tindak_lnjt', ['Puskesmas', 'klinik', 'Rumah Sakit'])->nullable();
            $table->integer('nadi')->nullable();
            $table->date('hpht')->nullable();
            $table->date('hpl')->nullable();
            $table->string('gpa')->nullable();
            $table->text('riwayat_kehamilan_kesehatan')->nullable();
            $table->integer('umur_hamil')->nullable();
            $table->integer('lingkar_perut')->nullable();
            $table->integer('tifu')->nullable();
            $table->integer('djj')->nullable();
            $table->text('ltkjanin')->nullable();
            $table->enum('ktrkuterus', ['Ada', 'Tidak Ada'])->nullable();
            $table->text('refla')->nullable();
            $table->text('lab')->nullable();
            $table->text('resti')->nullable();
            $table->string('intervensi')->nullable();
            $table->string('frek_kunjungan')->nullable();
            $table->text('alergi')->nullable();
            $table->text('lochea')->nullable();
            $table->text('payudara')->nullable();
            $table->text('luka_jahit')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->text('tmpt_persalinan')->nullable();
            $table->enum('bantu_persalinan', ['Bidan', 'Dokter'])->nullable();
            $table->enum('jns_persalinan', ['Spontan', 'Cesar', 'Vacum'])->nullable();
            $table->string('besar_rahim')->nullable();
            $table->text('infeksi_kompli')->nullable(); // '_' digunakan sebagai pengganti '/'
            $table->text('edukasi')->nullable();
            $table->integer('jmlh_anak')->nullable();
            $table->date('tgl_pasang')->nullable();
            $table->enum('metode_KB', ['pil', 'Suntik', 'Implan', 'IUD', 'Kondom', 'MOW/MOP'])->nullable();
            $table->text('efek_samping')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemeriksaan', function (Blueprint $table) {
             $table->dropColumn([
                'tindak_lnjt',
                'nadi',
                'hpht',
                'hpl',
                'gpa',
                'riwayat_kehamilan_kesehatan',
                'umur_hamil',
                'lingkar_perut',
                'tifu',
                'djj',
                'ltkjanin',
                'ktrkuterus',
                'refla',
                'lab',
                'resti',
                'intervensi',
                'frek_kunjungan',
                'alergi',
                'lochea',
                'payudara',
                'luka_jahit',
                'tgl_lahir',
                'tmpt_persalinan',
                'bantu_persalinan',
                'jns_persalinan',
                'besar_rahim',
                'infeksi_kompli',
                'edukasi',
                'jmlh_anak',
                'tgl_pasang',
                'metode_KB',
                'efek_samping',
            ]);
        });
    }
};