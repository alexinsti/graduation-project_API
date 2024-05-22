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
        CREATE TRIGGER update_amount_of_codes_trigger AFTER UPDATE ON codes_to_validate
        FOR EACH ROW
        BEGIN
            IF OLD.id_gymkhana  != NEW.id_gymkhana THEN
                CALL update_amount_of_codes(OLD.id_gymkhana );
                CALL update_amount_of_codes(NEW.id_gymkhana );
            END IF;
        END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `update_amount_of_codes_trigger`');
    }
};
