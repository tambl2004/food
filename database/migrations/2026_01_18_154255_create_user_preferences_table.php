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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->json('favorite_categories')->nullable();
            $table->string('diet_type')->nullable();
            $table->integer('spicy_level')->default(0)->comment('0=Không cay, 1=Cay nhẹ, 2=Cay vừa, 3=Cay nhiều');
            $table->json('disliked_ingredients')->nullable();
            $table->string('health_goal')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
