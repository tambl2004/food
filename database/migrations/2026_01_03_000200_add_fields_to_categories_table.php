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
            if (!Schema::hasColumn('categories', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('categories', 'meal_time')) {
                $table->json('meal_time')->nullable()->after('description');
            }
            if (!Schema::hasColumn('categories', 'origin')) {
                $table->string('origin')->nullable()->after('meal_time');
            }
            if (!Schema::hasColumn('categories', 'status')) {
                $table->tinyInteger('status')->default(1)->after('origin');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('categories', 'origin')) {
                $table->dropColumn('origin');
            }
            if (Schema::hasColumn('categories', 'meal_time')) {
                $table->dropColumn('meal_time');
            }
            if (Schema::hasColumn('categories', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
