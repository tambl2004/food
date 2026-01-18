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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable()->index();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            $table->string('origin')->nullable();
            $table->text('description')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->nullable();
            $table->unsignedInteger('prep_time')->nullable()->comment('Thời gian chuẩn bị (phút)');
            $table->unsignedInteger('cook_time')->nullable()->comment('Thời gian nấu (phút)');
            $table->unsignedInteger('servings')->nullable()->comment('Khẩu phần');
            $table->unsignedInteger('calories')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Index cho tìm kiếm
            $table->index(['status', 'category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};

