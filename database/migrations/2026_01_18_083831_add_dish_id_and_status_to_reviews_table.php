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
        Schema::table('reviews', function (Blueprint $table) {
            // Thêm dish_id (nullable để hỗ trợ cả Product và Dish reviews)
            $table->foreignId('dish_id')->nullable()->after('product_id')->constrained('dishes')->onDelete('cascade');
            
            // Thêm status cho Dish reviews (visible/hidden)
            $table->enum('status', ['visible', 'hidden'])->nullable()->after('is_approved')->default('visible');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['dish_id']);
            $table->dropColumn(['dish_id', 'status']);
        });
    }
};
