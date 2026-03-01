<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure pgcrypto extension exists only for PostgreSQL
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');
        }

        Schema::create('ecopay_activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id');
            $table->enum('bottle_type', ['plastic', 'metal']);
            $table->integer('coins_earned')->default(0);
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('account_id')
                ->references('id')
                ->on('ecopay_accounts')
                ->onDelete('cascade');
        });

        // Set default UUIDs using raw SQL based on driver
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE ecopay_activity_logs ALTER COLUMN id SET DEFAULT gen_random_uuid();');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ecopay_activity_logs');
    }
};
