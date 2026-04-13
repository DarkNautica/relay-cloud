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
        Schema::table('user_webhooks', function (Blueprint $table) {
            $table->integer('failure_count')->default(0);
            $table->timestamp('last_failed_at')->nullable();
            $table->boolean('is_paused')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('user_webhooks', function (Blueprint $table) {
            $table->dropColumn(['failure_count', 'last_failed_at', 'is_paused']);
        });
    }
};
