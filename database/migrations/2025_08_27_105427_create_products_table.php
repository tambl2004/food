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
    Schema::create('products', function (Blueprint $table) {
        $table->id(); // Cột ID tự động tăng, là khóa chính
        $table->string('name'); // Tên sản phẩm, kiểu chuỗi ký tự
        $table->text('description'); // Mô tả chi tiết, kiểu văn bản dài
        $table->decimal('price', 10, 2); // Giá sản phẩm, ví dụ: 99999999.99
        $table->string('image')->nullable(); // Đường dẫn hình ảnh, nullable nghĩa là có thể để trống
        $table->integer('stock')->default(0); // Số lượng tồn kho, kiểu số nguyên, mặc định là 0
        $table->timestamps(); // Tự động tạo 2 cột: created_at và updated_at
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
