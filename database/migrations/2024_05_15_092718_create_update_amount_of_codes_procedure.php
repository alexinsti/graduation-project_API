<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Iluminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE PROCEDURE update_amount_of_codes(id_gymkhana  INT)
        BEGIN
            UPDATE gymkhanas
            SET amount_of_codes = amount_of_codes(id_gymkhana )
            WHERE id = id_gymkhana ;
        END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS `update_amount_of_codes`');
    }
};
