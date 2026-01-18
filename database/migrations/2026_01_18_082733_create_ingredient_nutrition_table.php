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
        Schema::create('ingredient_nutrition', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->onDelete('cascade');
            $table->float('calories')->nullable()->comment('Kcal / 100g');
            $table->float('protein')->nullable()->comment('Protein (g)');
            $table->float('fat')->nullable()->comment('Chất béo (g)');
            $table->float('carbs')->nullable()->comment('Carb (g)');
            $table->float('fiber')->nullable()->comment('Chất xơ (g)');
            $table->text('vitamins')->nullable()->comment('Vitamin');
            $table->timestamps();
            
            // Mỗi nguyên liệu chỉ có một bản ghi dinh dưỡng
            $table->unique('ingredient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_nutrition');
    }
};
