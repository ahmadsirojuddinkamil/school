<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gurus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->uuid('uuid');

            $table->string('name');
            $table->integer('nuptk')->unique();
            $table->integer('nip')->nullable();
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('mata_pelajaran');
            $table->string('agama');
            $table->string('jenis_kelamin');
            $table->string('status_perkawinan');
            $table->string('jam_mengajar')->nullable();
            $table->string('pendidikan_terakhir');
            $table->string('nama_tempat_pendidikan');
            $table->integer('ipk');
            $table->integer('tahun_lulus');
            $table->string('alamat_rumah');
            $table->string('provinsi');
            $table->string('kecamatan');
            $table->string('kelurahan');
            $table->integer('kode_pos');
            $table->string('email')->unique();
            $table->integer('no_telpon');
            $table->integer('tahun_daftar');
            $table->integer('tahun_keluar')->nullable();
            $table->string('foto')->nullable();
            $table->string('nama_bank')->nullable();
            $table->string('nama_buku_rekening')->nullable();
            $table->string('no_rekening')->nullable();
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
        Schema::dropIfExists('gurus');
    }
};
