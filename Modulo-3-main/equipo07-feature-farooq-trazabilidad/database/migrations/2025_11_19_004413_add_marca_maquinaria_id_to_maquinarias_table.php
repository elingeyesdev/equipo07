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
            // Agregar marca_maquinaria_id
            $table->foreignId('marca_maquinaria_id')->nullable()->after('tipo_maquinaria_id')->constrained('marcas_maquinarias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropForeign(['marca_maquinaria_id']);
            $table->dropColumn('marca_maquinaria_id');
        });
    }
};
