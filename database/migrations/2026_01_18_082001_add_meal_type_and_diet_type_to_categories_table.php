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
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'meal_type')) {
                $table->string('meal_type')->nullable()->after('status');
            }
            if (!Schema::hasColumn('categories', 'diet_type')) {
                $table->string('diet_type')->nullable()->after('meal_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'diet_type')) {
                $table->dropColumn('diet_type');
            }
            if (Schema::hasColumn('categories', 'meal_type')) {
                $table->dropColumn('meal_type');
            }
        });
    }
};
