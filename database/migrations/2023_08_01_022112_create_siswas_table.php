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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid_payment')->nullable();
            $table->uuid('uuid')->unique();

            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('nisn')->unique();
            $table->string('asal_sekolah');
            $table->string('alamat');
            $table->string('telpon_siswa');
            $table->string('jenis_kelamin');
            $table->string('tempat_lahir');
            $table->string('tanggal_lahir');
            $table->string('jurusan');
            $table->string('nama_ayah');
            $table->string('nama_ibu');
            $table->string('telpon_orang_tua');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
