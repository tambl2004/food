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
        Schema::table('user_ingredients', function (Blueprint $table) {
            $table->timestamp('added_at')->nullable()->after('unit')->comment('Ngày thêm vào tủ lạnh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ingredients', function (Blueprint $table) {
            $table->dropColumn('added_at');
        });
    }
};
