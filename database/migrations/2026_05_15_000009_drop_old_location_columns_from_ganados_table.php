<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->sincronizarUbicacionesPendientes();

        Schema::table('ganados', function (Blueprint $table) {
            $table->dropColumn([
                'ubicacion',
                'latitud',
                'longitud',
                'departamento',
                'municipio',
                'provincia',
                'ciudad',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('ganados', function (Blueprint $table) {
            $table->string('ubicacion')->nullable()->after('imagen');
            $table->decimal('latitud', 10, 7)->nullable()->after('ubicacion');
            $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
            $table->string('departamento')->nullable()->after('dato_sanitario_id');
            $table->string('municipio')->nullable()->after('departamento');
            $table->string('provincia')->nullable()->after('municipio');
            $table->string('ciudad')->nullable()->after('provincia');
        });
    }

    private function sincronizarUbicacionesPendientes(): void
    {
        DB::table('ganados')
            ->whereNull('ubicacion_ganado_id')
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
