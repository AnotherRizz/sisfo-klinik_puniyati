<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemeriksaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemeriksaan', function (Blueprint $table) {
            $table->id();
            $table->string('no_periksa')->unique();
            $table->foreignId('pendaftaran_id')->constrained('pendaftaran')->onDelete('cascade');
            $table->string('keluhan');
            $table->string('riw_penyakit');
            $table->string('riw_imunisasi')->nullable();
            $table->string('riw_alergi')->nullable();
            $table->string('td');
            $table->integer('bb'); // Berat badan dengan format desimal
            $table->integer('tb'); // Tinggi badan dengan format desimal
            $table->integer('suhu'); // Suhu dalam format desimal
            $table->integer('saturasiOx');
            $table->integer('nadi')->nullable();
            $table->integer('lila')->nullable();
            $table->date('hpht')->nullable();
            $table->date('hpl')->nullable();
            $table->string('gpa')->nullable();
            $table->text('riwayat_kehamilan_kesehatan')->nullable();
            $table->integer('umur_hamil')->nullable();
            $table->integer('lingkar_perut')->nullable();
            $table->integer('tifu')->nullable();
            $table->integer('djj')->nullable();
            $table->string('ltkjanin')->nullable();
            $table->string('ktrkuterus')->nullable();
            $table->string('refla')->nullable();
            $table->string('lab')->nullable();
            $table->string('resti')->nullable();
            $table->text('intervensi')->nullable();
            $table->integer('frek_kunjungan')->nullable();
            $table->string('lochea')->nullable();
            $table->string('payudara')->nullable();
            $table->string('luka_jahit')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('tmpt_persalinan')->nullable();
            $table->string('bantu_persalinan')->nullable();
            $table->string('jns_persalinan')->nullable();
            $table->string('besar_rahim')->nullable();
            $table->string('infeksi_kompli')->nullable();
            $table->text('edukasi')->nullable();
            $table->integer('jmlh_anak')->nullable();
            $table->date('tgl_pasang')->nullable();
            $table->string('metode_KB')->nullable();
            $table->string('efek_samping')->nullable();
            $table->string('diagnosa');
            $table->string('tindakan');
            $table->date('tgl_kembali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pemeriksaan');
    }
}