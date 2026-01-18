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
        Schema::create('dish_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dish_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->string('quantity')->nullable()->comment('Số lượng (ví dụ: 200, 1.5)');
            $table->string('unit')->nullable()->comment('Đơn vị (ví dụ: g, kg, ml, cup)');
            $table->boolean('is_required')->default(true)->comment('Bắt buộc hay tùy chọn');
            $table->timestamps();
            
            // Đảm bảo không trùng lặp nguyên liệu trong cùng một món
            $table->unique(['dish_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_ingredients');
    }
};

