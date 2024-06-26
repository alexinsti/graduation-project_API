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
        $default_profile_pic=base64_decode("iVBORw0KGgoAAAANSUhEUgAABIAAAAKICAIAAACHSRZaAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAAEnQAABJ0Ad5mH3gAAA2zSURBVHhe7dcxAQAADMOg+TfducgFLrgBAACQEDAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAIgIGAAAQETAAAICIgAEAAEQEDAAAICJgAAAAEQEDAACICBgAAEBEwAAAACICBgAAEBEwAACAiIABAABEBAwAACAiYAAAABEBAwAAiAgYAABARMAAAAAiAgYAABARMAAAgIiAAQAARAQMAAAgImAAAAARAQMAAIgIGAAAQETAAAAAEtsD9S7Spuq5RpkAAAAASUVORK5CYII=");
        Schema::create('users', function (Blueprint $table) use ($default_profile_pic) {
            $table->id()->primary();
            $table->String('username')->unique();
            $table->string('nickname');
            $table->string('email')->unique();
            $table->longtext('profile_pic')->charset('binary')->default($default_profile_pic);//LONGBLOB
            $table->tinyInteger('reported')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
