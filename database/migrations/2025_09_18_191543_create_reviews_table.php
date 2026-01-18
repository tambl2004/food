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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết với user
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Liên kết với product
            $table->integer('rating')->unsigned(); // Số sao từ 1-5
            $table->text('comment')->nullable(); // Bình luận đánh giá
            $table->boolean('is_approved')->default(true); // Trạng thái duyệt
            $table->timestamps();
            
            // Ràng buộc: mỗi user chỉ được đánh giá 1 lần cho 1 sản phẩm
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
