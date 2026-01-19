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
        // Bước 1: Xóa foreign key constraint bằng SQL trực tiếp
        // Tìm tên foreign key thực tế
        $foreignKeys = \DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'reviews' 
            AND COLUMN_NAME = 'product_id' 
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        
        foreach ($foreignKeys as $fk) {
            try {
                \DB::statement("ALTER TABLE reviews DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            } catch (\Exception $e) {
                // Bỏ qua nếu không tìm thấy
            }
        }
        
        // Bước 2: Xóa unique constraint bằng SQL trực tiếp (sau khi đã xóa foreign key)
        try {
            \DB::statement("ALTER TABLE reviews DROP INDEX `reviews_user_id_product_id_unique`");
        } catch (\Exception $e) {
            // Thử với tên khác có thể có
            try {
                \DB::statement("ALTER TABLE reviews DROP INDEX `reviews_user_id_product_id_unique`");
            } catch (\Exception $e2) {
                // Bỏ qua nếu không tìm thấy
            }
        }
        
        // Bước 3: Làm product_id nullable
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->change();
        });
        
        // Bước 4: Thêm lại foreign key constraint (không có unique)
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Bước 1: Xóa foreign key constraint
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
        });
        
        // Bước 2: Xóa các review có product_id null trước khi khôi phục constraint
        \DB::table('reviews')->whereNull('product_id')->delete();
        
        // Bước 3: Khôi phục product_id không nullable
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable(false)->change();
        });
        
        // Bước 4: Thêm lại unique constraint
        Schema::table('reviews', function (Blueprint $table) {
            $table->unique(['user_id', 'product_id']);
        });
        
        // Bước 5: Thêm lại foreign key constraint
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }
};
