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
        CREATE TRIGGER amount_of_codes_trigger AFTER INSERT ON codes_to_validate
        FOR EACH ROW
        BEGIN
            CALL update_amount_of_codes(NEW.id_gymkhana );
        END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `amount_of_codes_trigger`');
    }
};
