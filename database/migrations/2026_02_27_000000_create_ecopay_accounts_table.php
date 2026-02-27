<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure pgcrypto extension exists for gen_random_uuid()
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');

        Schema::create('ecopay_accounts', function (Blueprint $table) {
            // uuid primary key using gen_random_uuid()
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->string('email')->unique();
            $table->integer('overall_bottles')->default(0);
            $table->integer('plastic_bottles')->default(0);
            $table->integer('metal_bottles')->default(0);
            $table->integer('coins_available')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecopay_accounts');
    }
};