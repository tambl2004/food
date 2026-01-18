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
        Schema::table('user_preferences', function (Blueprint $table) {
            // Thêm trường origins (nguồn gốc món ăn) - JSON array
            if (!Schema::hasColumn('user_preferences', 'origins')) {
                $table->json('origins')->nullable()->after('favorite_categories');
            }
            
            // Thêm trường allergies (dị ứng) - JSON array (nếu cần tách riêng với disliked_ingredients)
            if (!Schema::hasColumn('user_preferences', 'allergies')) {
                $table->json('allergies')->nullable()->after('disliked_ingredients');
            }
            
            // Thêm trường diet_types (chế độ ăn) - JSON array (để hỗ trợ nhiều chế độ)
            if (!Schema::hasColumn('user_preferences', 'diet_types')) {
                $table->json('diet_types')->nullable()->after('diet_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_preferences', function (Blueprint $table) {
            if (Schema::hasColumn('user_preferences', 'diet_types')) {
                $table->dropColumn('diet_types');
            }
            if (Schema::hasColumn('user_preferences', 'allergies')) {
                $table->dropColumn('allergies');
            }
            if (Schema::hasColumn('user_preferences', 'origins')) {
                $table->dropColumn('origins');
            }
        });
    }
};
