<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ensure pgcrypto extension in case migrations run separately
        DB::statement('CREATE EXTENSION IF NOT EXISTS "pgcrypto";');

        Schema::create('ecopay_activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
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
    }

    public function down(): void
    {
        Schema::dropIfExists('ecopay_activity_logs');
    }
};