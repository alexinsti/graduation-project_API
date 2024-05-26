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
        Schema::create('gymkhanas', function (Blueprint $table) {
            $table->id()->primary();
            $table->String('name');
            $table->String('password')->nullable();
            $table->text('description')->nullable();
            $table->Integer('amount_of_codes');
            $table->geometry('starting_point', subtype: 'point', srid: 4326);
            $table->longtext('gymkhana_pic')->charset('binary');//LONGBLOB;
            $table->tinyInteger('state');
            $table->tinyInteger('availability');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gymkhanas');
    }
};
