<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Add recipe-related fields
            $table->string('difficulty', 20)->nullable()->after('video_url');
            $table->unsignedInteger('prep_time')->nullable()->after('difficulty'); // minutes
            $table->unsignedInteger('cook_time')->nullable()->after('prep_time'); // minutes
            $table->unsignedInteger('servings')->nullable()->after('cook_time');

            // Remove price and stock columns (if present)
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Recreate price and stock as nullable (best-effort)
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 12, 2)->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('price');
            }

            // Drop recipe fields
            if (Schema::hasColumn('products', 'servings')) {
                $table->dropColumn('servings');
            }
            if (Schema::hasColumn('products', 'cook_time')) {
                $table->dropColumn('cook_time');
            }
            if (Schema::hasColumn('products', 'prep_time')) {
                $table->dropColumn('prep_time');
            }
            if (Schema::hasColumn('products', 'difficulty')) {
                $table->dropColumn('difficulty');
            }
        });
    }
};
