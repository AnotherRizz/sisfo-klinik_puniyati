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
         Schema::create('pasien', function (Blueprint $table) {
            $table->id();
          $table->string('no_rm', 8)->unique();
            $table->char('nik_pasien', 16);
            $table->string('nama_pasien',50);
            $table->string('tempt_lahir',20);
            $table->date('tgl_lahir');
            $table->text('alamat',100);
            $table->enum('agama', ['islam', 'kristen', 'katholik', 'hindu', 'budha', 'aliran kepercayaan']);
            $table->enum('pendidikan', ['belum sekolah','SD','SMP/SLTP','SMA/SLTA','Diploma I/II/III','S1/S2/S3','lain-lain']);
            $table->enum('pekerjaan', ['Wiraswasta', 'PNS', 'Ibu Rumah Tangga', 'Pelajar', 'Mahasiswa', 'Petani', 'Pedagang', 'Tidak Bekerja']);
            $table->string('penanggungjawab',50);
            $table->enum('golda', ['A', 'B', 'AB', 'O']);
            $table->varchar('no_tlp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};