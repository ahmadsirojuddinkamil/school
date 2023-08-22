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
        // Schema::table('siswas', function (Blueprint $table) {
        //     $table->foreign('uuid_payment')->references('uuid')->on('payments');
        // });

        // Schema::table('ppdbs', function (Blueprint $table) {
        //     $table->foreign('uuid_payment')->references('uuid')->on('payments');
        // });

        // Schema::table('payments', function (Blueprint $table) {
        //     $table->foreign('uuid_siswa')->references('uuid')->on('siswas');
        //     $table->foreign('uuid_ppdb')->references('uuid')->on('ppdbs');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('ppdbs');
    }
};
