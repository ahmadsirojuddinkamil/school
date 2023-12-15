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
        Schema::create('mata_pelajarans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('jam_awal');
            $table->string('jam_akhir');
            $table->string('kelas');
            $table->string('name_guru')->nullable();
            $table->string('materi_pdf')->nullable();
            $table->string('materi_ppt')->nullable();
            $table->string('video')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('pivot_mata_pelajaran_siswas', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('siswa_uuid')->nullable();
            $table->foreignUuid('mata_pelajaran_uuid')->nullable();
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
        Schema::dropIfExists('mata_pelajarans');
        Schema::dropIfExists('pivot_siswa_mata_pelajarans');
    }
};
