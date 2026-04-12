<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->integer('connections_peak')->default(0);
            $table->bigInteger('messages_count')->default(0);
            $table->timestamp('recorded_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usage_stats');
    }
};
