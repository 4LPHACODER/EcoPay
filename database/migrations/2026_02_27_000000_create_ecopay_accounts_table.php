<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure pgcrypto extension exists only for PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');
        }

        Schema::create('ecopay_accounts', function (Blueprint $table) {
            // Use Laravel's uuid() which works on all databases
            // For PostgreSQL, we use gen_random_uuid() as the default
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->integer('overall_bottles')->default(0);
            $table->integer('plastic_bottles')->default(0);
            $table->integer('metal_bottles')->default(0);
            $table->integer('coins_available')->default(0);
            $table->timestamps();
        });

        // Set default UUIDs using raw SQL based on driver
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE ecopay_accounts ALTER COLUMN id SET DEFAULT gen_random_uuid();');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ecopay_accounts');
    }
};
