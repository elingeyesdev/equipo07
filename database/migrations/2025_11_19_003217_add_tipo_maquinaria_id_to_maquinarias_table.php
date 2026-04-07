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
        Schema::table('maquinarias', function (Blueprint $table) {
            // Agregar tipo_maquinaria_id
            $table->foreignId('tipo_maquinaria_id')->nullable()->after('nombre')->constrained('tipo_maquinarias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropForeign(['tipo_maquinaria_id']);
            $table->dropColumn('tipo_maquinaria_id');
        });
    }
};
