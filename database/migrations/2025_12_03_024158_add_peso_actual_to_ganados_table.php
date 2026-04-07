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
            $table->decimal('peso_actual', 10, 2)->nullable()->after('tipo_peso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->dropColumn('peso_actual');
        });
    }
};
