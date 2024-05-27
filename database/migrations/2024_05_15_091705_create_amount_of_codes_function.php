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
        CREATE FUNCTION amount_of_codes(p_id_gymkhana INT) RETURNS INT
        READS SQL DATA
        BEGIN
            DECLARE amount_of_codes INT;

            SELECT COUNT(*) INTO amount_of_codes
            FROM codes_to_validate
            WHERE id_gymkhana = p_id_gymkhana
            AND privilege = 1;

            RETURN amount_of_codes;
        END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP FUNCTION IF EXISTS `amount_of_codes`');
    }
};
