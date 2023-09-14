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
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable();

            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('nisn')->unique();
            $table->string('asal_sekolah');
            $table->integer('kelas');
            $table->string('alamat');
            $table->string('telpon_siswa');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('tahun_daftar');
            $table->string('jurusan');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('telpon_orang_tua');
            $table->string('foto')->nullable();
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
