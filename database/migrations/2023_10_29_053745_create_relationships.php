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
        });

        Schema::table('absens', function (Blueprint $table) {
            $table->foreign('siswa_uuid')->references('uuid')->on('siswas');
            $table->foreign('guru_uuid')->references('uuid')->on('gurus');
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
