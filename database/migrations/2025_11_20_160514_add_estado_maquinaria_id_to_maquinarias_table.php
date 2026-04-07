<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\EstadoMaquinaria;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->foreignId('estado_maquinaria_id')->nullable()->after('estado')->constrained('estado_maquinarias')->onDelete('set null');
        });

        // Crear estados por defecto si no existen
        $estados = [
            ['nombre' => 'disponible', 'descripcion' => 'Maquinaria disponible para alquiler'],
            ['nombre' => 'en_mantenimiento', 'descripcion' => 'Maquinaria en mantenimiento'],
            ['nombre' => 'dado_baja', 'descripcion' => 'Maquinaria dado de baja'],
        ];

        foreach ($estados as $estado) {
            EstadoMaquinaria::firstOrCreate(
                ['nombre' => $estado['nombre']],
                ['descripcion' => $estado['descripcion']]
            );
        }

        // Migrar datos existentes del campo estado al nuevo estado_maquinaria_id
        $estadosMap = EstadoMaquinaria::pluck('id', 'nombre')->toArray();
        
        DB::table('maquinarias')->whereNotNull('estado')->chunkById(100, function ($maquinarias) use ($estadosMap) {
            foreach ($maquinarias as $maquinaria) {
                $estadoNombre = $maquinaria->estado;
                if (isset($estadosMap[$estadoNombre])) {
                    DB::table('maquinarias')
                        ->where('id', $maquinaria->id)
                        ->update(['estado_maquinaria_id' => $estadosMap[$estadoNombre]]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maquinarias', function (Blueprint $table) {
            $table->dropForeign(['estado_maquinaria_id']);
            $table->dropColumn('estado_maquinaria_id');
        });
    }
};
