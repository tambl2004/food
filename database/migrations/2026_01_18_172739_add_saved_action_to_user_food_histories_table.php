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
        // MySQL không hỗ trợ ALTER COLUMN trực tiếp cho ENUM, nên dùng raw SQL
        \DB::statement("ALTER TABLE user_food_histories MODIFY COLUMN action ENUM('viewed', 'saved', 'cooked') NOT NULL DEFAULT 'viewed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert về enum ban đầu (chỉ có 'viewed', 'cooked')
        \DB::statement("ALTER TABLE user_food_histories MODIFY COLUMN action ENUM('viewed', 'cooked') NOT NULL DEFAULT 'viewed'");
    }
};
