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
        Schema::table('ganados', function (Blueprint $table) {
            $table->foreignId('dato_sanitario_id')->nullable()->after('categoria_id')->constrained('datos_sanitarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->dropForeign(['dato_sanitario_id']);
            $table->dropColumn('dato_sanitario_id');
        });
    }
};
