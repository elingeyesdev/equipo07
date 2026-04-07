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
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->string('destino_matadero_campo')->nullable()->after('certificado_imagen');
            $table->string('hoja_ruta_foto')->nullable()->after('destino_matadero_campo');
            $table->string('marca_ganado')->nullable()->after('hoja_ruta_foto');
            $table->string('senal_numero')->nullable()->after('marca_ganado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropColumn(['destino_matadero_campo', 'hoja_ruta_foto', 'marca_ganado', 'senal_numero']);
        });
    }
};
