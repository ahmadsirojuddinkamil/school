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
        Schema::table('siswas', function (Blueprint $table) {
            $table->foreign('user_uuid')->references('uuid')->on('users');
        });

        Schema::table('gurus', function (Blueprint $table) {
            $table->foreign('user_uuid')->references('uuid')->on('users');
            $table->foreign('mata_pelajaran_uuid')->references('uuid')->on('mata_pelajarans');
        });

        Schema::table('absens', function (Blueprint $table) {
            $table->foreign('siswa_uuid')->references('uuid')->on('siswas');
            $table->foreign('guru_uuid')->references('uuid')->on('gurus');
        });

        Schema::table('pivot_mata_pelajaran_siswas', function (Blueprint $table) {
            $table->foreign('siswa_uuid')->references('uuid')->on('siswas');
            $table->foreign('mata_pelajaran_uuid')->references('uuid')->on('mata_pelajarans');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
