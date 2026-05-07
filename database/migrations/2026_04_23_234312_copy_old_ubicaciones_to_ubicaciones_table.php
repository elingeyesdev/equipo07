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
        $tables = ['ganados', 'maquinarias', 'organicos'];
        
        foreach ($tables as $tableName) {
            $items = DB::table($tableName)->get();
            foreach ($items as $item) {
                $departamento = property_exists($item, 'departamento') ? $item->departamento : null;
                $provincia = property_exists($item, 'provincia') ? $item->provincia : null;
                $municipio = property_exists($item, 'municipio') ? $item->municipio : null;
                $ciudad = property_exists($item, 'ciudad') ? $item->ciudad : null;
                $direccion = property_exists($item, 'ubicacion') ? $item->ubicacion : null;
                $latitud = property_exists($item, 'latitud') ? $item->latitud : null;
                $longitud = property_exists($item, 'longitud') ? $item->longitud : null;
                
                if ($departamento || $provincia || $municipio || $ciudad || $direccion || $latitud || $longitud) {
                    $ubicacionId = DB::table('ubicaciones')->insertGetId([
                        'departamento' => $departamento,
                        'provincia' => $provincia,
                        'municipio' => $municipio,
                        'ciudad' => $ciudad,
                        'direccion' => $direccion,
                        'latitud' => $latitud,
                        'longitud' => $longitud,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    DB::table($tableName)->where('id', $item->id)->update(['ubicacion_id' => $ubicacionId]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Limpiamos los IDs pero no borramos las ubicaciones para no perder datos si hay rollback parcial
        DB::table('ganados')->update(['ubicacion_id' => null]);
        DB::table('maquinarias')->update(['ubicacion_id' => null]);
        DB::table('organicos')->update(['ubicacion_id' => null]);
    }
};
