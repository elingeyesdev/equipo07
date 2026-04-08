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
            $table->boolean('logro_campeon_raza')->default(false)->after('certificado_campeon_imagen');
            $table->boolean('logro_gran_campeon_macho')->default(false)->after('logro_campeon_raza');
            $table->boolean('logro_gran_campeon_hembra')->default(false)->after('logro_gran_campeon_macho');
            $table->boolean('logro_mejor_ubre')->default(false)->after('logro_gran_campeon_hembra');
            
            $table->boolean('logro_campeona_litros_dia')->default(false)->after('logro_mejor_ubre');
            $table->boolean('logro_mejor_lactancia')->default(false)->after('logro_campeona_litros_dia');
            $table->boolean('logro_mejor_calidad_leche')->default(false)->after('logro_mejor_lactancia');
            
            $table->boolean('logro_mejor_novillo')->default(false)->after('logro_mejor_calidad_leche');
            $table->boolean('logro_gran_campeon_carne')->default(false)->after('logro_mejor_novillo');
            $table->boolean('logro_mejor_semental')->default(false)->after('logro_gran_campeon_carne');
            
            $table->boolean('logro_mejor_madre')->default(false)->after('logro_mejor_semental');
            $table->boolean('logro_mejor_padre')->default(false)->after('logro_mejor_madre');
            $table->boolean('logro_mejor_fertilidad')->default(false)->after('logro_mejor_padre');
            
            $table->string('arbol_genealogico')->nullable()->after('logro_mejor_fertilidad')->comment('PDF o imagen del árbol genealógico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropColumn([
                'logro_campeon_raza',
                'logro_gran_campeon_macho',
                'logro_gran_campeon_hembra',
                'logro_mejor_ubre',
                'logro_campeona_litros_dia',
                'logro_mejor_lactancia',
                'logro_mejor_calidad_leche',
                'logro_mejor_novillo',
                'logro_gran_campeon_carne',
                'logro_mejor_semental',
                'logro_mejor_madre',
                'logro_mejor_padre',
                'logro_mejor_fertilidad',
                'arbol_genealogico'
            ]);
        });
    }
};
