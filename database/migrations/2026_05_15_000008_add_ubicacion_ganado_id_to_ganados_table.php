<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->foreignId('ubicacion_ganado_id')
                ->nullable()
                ->after('dato_sanitario_id')
                ->constrained('ubicacion_ganado')
                ->onDelete('set null');
        });

        DB::table('ganados')
            ->where(function ($query) {
                $query->whereNotNull('ubicacion')
                    ->orWhereNotNull('latitud')
                    ->orWhereNotNull('longitud')
                    ->orWhereNotNull('departamento')
                    ->orWhereNotNull('municipio')
                    ->orWhereNotNull('provincia')
                    ->orWhereNotNull('ciudad');
            })
            ->orderBy('id')
            ->chunkById(100, function ($ganados) {
                foreach ($ganados as $ganado) {
                    $ubicacionGeograficaId = $this->obtenerUbicacionGeograficaId($ganado);

                    $ubicacionGanadoId = DB::table('ubicacion_ganado')->insertGetId([
                        'ubicacion' => $ganado->ubicacion,
                        'latitud' => $ganado->latitud,
                        'longitud' => $ganado->longitud,
                        'ubicacion_geografica_ganado_id' => $ubicacionGeograficaId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('ganados')
                        ->where('id', $ganado->id)
                        ->update(['ubicacion_ganado_id' => $ubicacionGanadoId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->dropForeign(['ubicacion_ganado_id']);
            $table->dropColumn('ubicacion_ganado_id');
        });
    }

    private function obtenerUbicacionGeograficaId(object $ganado): int
    {
        $query = DB::table('ubicacion_geografica_ganados');

        foreach (['departamento', 'municipio', 'provincia', 'ciudad'] as $campo) {
            if ($ganado->{$campo} === null) {
                $query->whereNull($campo);
            } else {
                $query->where($campo, $ganado->{$campo});
            }
        }

        $existente = $query->first();

        if ($existente) {
            return $existente->id;
        }

        return DB::table('ubicacion_geografica_ganados')->insertGetId([
            'departamento' => $ganado->departamento,
            'municipio' => $ganado->municipio,
            'provincia' => $ganado->provincia,
            'ciudad' => $ganado->ciudad,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
