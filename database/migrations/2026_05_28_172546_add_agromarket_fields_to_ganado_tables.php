<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Nuevos campos principales en ganados
        Schema::table('ganados', function (Blueprint $table) {
            $table->string('modalidad')->nullable()->after('nombre');
            $table->string('proposito')->nullable()->after('modalidad');
            $table->string('tipo_genetica')->nullable()->after('proposito');
            $table->foreignId('categoria_id')->nullable()->change();
        });

        // 2. Tabla exacta: caracteristicas_ganado
        Schema::table('caracteristicas_ganado', function (Blueprint $table) {
            $table->integer('edad_valor')->nullable()->after('edad');
            $table->string('unidad_edad')->nullable()->after('edad_valor');
        });

        // 3. Tabla exacta: datos_productivos_ganado
        Schema::table('datos_productivos_ganado', function (Blueprint $table) {
            $table->string('unidad_peso')->nullable()->after('peso_actual');
            $table->string('tipo_pesaje')->nullable()->after('unidad_peso');
            $table->foreignId('tipo_peso_id')->nullable()->change();
        });

        // 4. Tabla exacta: datos_comerciales_ganado
        Schema::table('datos_comerciales_ganado', function (Blueprint $table) {
            $table->string('forma_cobro')->nullable()->after('precio');
        });

        // 5. Tabla exacta: datos_sanitarios
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->boolean('has_sanity')->default(false)->after('ganado_id');
            $table->string('documento_pdf')->nullable()->after('has_sanity');
        });
    }

    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->dropColumn(['modalidad', 'proposito', 'tipo_genetica']);
        });
        Schema::table('caracteristicas_ganado', function (Blueprint $table) {
            $table->dropColumn(['edad_valor', 'unidad_edad']);
        });
        Schema::table('datos_productivos_ganado', function (Blueprint $table) {
            $table->dropColumn(['unidad_peso', 'tipo_pesaje']);
        });
        Schema::table('datos_comerciales_ganado', function (Blueprint $table) {
            $table->dropColumn(['forma_cobro']);
        });
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropColumn(['has_sanity', 'documento_pdf']);
        });
    }
};