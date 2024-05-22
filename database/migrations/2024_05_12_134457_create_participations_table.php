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
        Schema::create('participations', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_gymkhana');
            $table->tinyInteger('privilege');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_gymkhana')->references('id')->on('gymkhanas');
            $table->unique(['id_user', 'id_gymkhana']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};
