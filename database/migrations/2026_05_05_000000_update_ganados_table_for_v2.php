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
            // 1. Soltar la restricción de llave foránea primero (Previene errores SQL)
            $table->dropForeign(['tipo_peso_id']);
            $table->dropForeign(['dato_sanitario_id']);
            
            // 2. Ahora sí, eliminar las columnas
            $table->dropColumn(['tipo_peso_id', 'dato_sanitario_id']);

            // 3. Agregar nuevas columnas
            $table->string('tipo_venta')->nullable()->after('stock');
            $table->string('tipo_precio')->nullable()->after('tipo_venta');
            $table->boolean('tiene_sanidad')->default(false)->after('tipo_precio');
            $table->string('archivo_sanidad')->nullable()->after('tiene_sanidad');
            $table->string('archivo_genetica')->nullable()->after('es_campeon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            // Restaurar columnas viejas
            $table->foreignId('tipo_peso_id')->nullable()->constrained('tipo_pesos');
            $table->foreignId('dato_sanitario_id')->nullable()->constrained('datos_sanitarios');

            // Eliminar todas las nuevas columnas (incluyendo es_campeon que faltaba)
            $table->dropColumn([
                'tipo_venta',
                'tipo_precio',
                'tiene_sanidad',
                'archivo_sanidad',
                'es_campeon',
                'archivo_genetica'
            ]);
        });
    }
};