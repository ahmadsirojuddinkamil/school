<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('mata_pelajaran_id')->nullable();
            $table->uuid('uuid')->unique();

            $table->string('name');
            $table->string('nisn')->unique();
            $table->string('kelas')->nullable();
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('agama')->nullable();
            $table->string('jenis_kelamin');
            $table->string('asal_sekolah');
            $table->string('nem')->nullable();
            $table->string('tahun_lulus')->nullable();
            $table->string('alamat_rumah')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('email')->unique();
            $table->string('no_telpon');
            $table->string('tahun_daftar');
            $table->string('tahun_keluar')->nullable();
            $table->string('foto')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nama_buku_rekening')->nullable();
            $table->string('no_rekening')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('telpon_orang_tua');
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
        Schema::dropIfExists('siswas');
    }
};
