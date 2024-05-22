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
        Schema::create('codes_to_validate', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_gymkhana');
            $table->unsignedBigInteger('id_code');
            $table->tinyInteger('privilege');
            $table->foreign('id_user')->references('id')->on('users');
            $table->foreign('id_gymkhana')->references('id')->on('gymkhanas');
            $table->foreign('id_code')->references('id')->on('codes');
            $table->unique(['id_user', 'id_gymkhana', 'id_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codes_to_validate');
    }
};
