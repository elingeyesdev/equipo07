<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicacion_geografica_organicos', function (Blueprint $table) {
            $table->id();
            $table->string('departamento')->nullable();
            $table->string('municipio')->nullable();
            $table->string('provincia')->nullable();
            $table->string('ciudad')->nullable();
            $table->timestamps();

            $table->unique(
                ['departamento', 'municipio', 'provincia', 'ciudad'],
                'ubicacion_geo_org_unique'
            );
        });

        Schema::create('ubicacion_organico', function (Blueprint $table) {
            $table->id();
            $table->string('ubicacion')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->foreignId('ubicacion_geografica_organico_id')
                ->nullable()
                ->constrained('ubicacion_geografica_organicos')
                ->onDelete('set null');
            $table->timestamps();
        });

        Schema::table('organicos', function (Blueprint $table) {
            $table->foreignId('ubicacion_organico_id')
                ->nullable()
                ->after('tipo_cultivo_id')
                ->constrained('ubicacion_organico')
                ->onDelete('set null');
        });

        DB::table('organicos')
            ->where(function ($query) {
                $query->whereNotNull('origen')
                    ->orWhereNotNull('latitud_origen')
                    ->orWhereNotNull('longitud_origen');
            })
            ->orderBy('id')
            ->chunkById(100, function ($organicos) {
                foreach ($organicos as $organico) {
                    $ubicacionOrganicoId = DB::table('ubicacion_organico')->insertGetId([
                        'ubicacion' => $organico->origen,
                        'latitud' => $organico->latitud_origen,
                        'longitud' => $organico->longitud_origen,
                        'ubicacion_geografica_organico_id' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('organicos')
                        ->where('id', $organico->id)
                        ->update(['ubicacion_organico_id' => $ubicacionOrganicoId]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('organicos', function (Blueprint $table) {
            $table->dropForeign(['ubicacion_organico_id']);
            $table->dropColumn('ubicacion_organico_id');
        });

        Schema::dropIfExists('ubicacion_organico');
        Schema::dropIfExists('ubicacion_geografica_organicos');
    }
};
