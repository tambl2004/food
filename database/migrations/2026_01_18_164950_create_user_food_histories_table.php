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
        Schema::create('user_food_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('dish_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['viewed', 'cooked'])->default('viewed');
            $table->timestamp('action_at')->useCurrent();
            $table->timestamps();
            
            // Index để tối ưu query
            $table->index(['user_id', 'action', 'action_at']);
            $table->index(['dish_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_food_histories');
    }
};
