<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ubicaciones_organicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organico_id')->unique()->constrained('organicos')->cascadeOnDelete();
            $table->string('direccion')->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('ciudad')->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->string('referencia')->nullable();
            $table->timestamps();
        });

        DB::table('organicos')
            ->leftJoin('ubicacion_organico', 'ubicacion_organico.id', '=', 'organicos.ubicacion_organico_id')
            ->leftJoin(
                'ubicacion_geografica_organicos',
                'ubicacion_geografica_organicos.id',
                '=',
                'ubicacion_organico.ubicacion_geografica_organico_id'
            )
            ->select([
                'organicos.id as organico_id',
                'ubicacion_organico.ubicacion',
                'ubicacion_organico.latitud',
                'ubicacion_organico.longitud',
                'ubicacion_geografica_organicos.departamento',
                'ubicacion_geografica_organicos.provincia',
                'ubicacion_geografica_organicos.municipio',
                'ubicacion_geografica_organicos.ciudad',
            ])
            ->orderBy('organicos.id')
            ->chunk(100, function ($organicos) {
                $now = now();

                foreach ($organicos as $organico) {
                    if (
                        blank($organico->ubicacion)
                        && blank($organico->latitud)
                        && blank($organico->longitud)
                        && blank($organico->departamento)
                        && blank($organico->provincia)
                        && blank($organico->municipio)
                        && blank($organico->ciudad)
                    ) {
                        continue;
                    }

                    DB::table('ubicaciones_organicos')->insert([
                        'organico_id' => $organico->organico_id,
                        'direccion' => $organico->ubicacion,
                        'departamento' => $organico->departamento,
                        'provincia' => $organico->provincia,
                        'municipio' => $organico->municipio,
                        'ciudad' => $organico->ciudad,
                        'latitud' => $organico->latitud,
                        'longitud' => $organico->longitud,
                        'referencia' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });

        Schema::create('certificados_organicos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('tipo')->default('opcional');
            $table->boolean('es_obligatorio')->default(false);
            $table->boolean('activo')->default(true);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('organico_certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organico_id')->constrained('organicos')->cascadeOnDelete();
            $table->foreignId('certificado_organico_id')->nullable()->constrained('certificados_organicos')->nullOnDelete();
            $table->string('nombre_adicional')->nullable();
            $table->string('estado')->default('pendiente');
            $table->string('archivo')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->unique(['organico_id', 'certificado_organico_id'], 'organico_certificados_unique');
        });

        $now = now();
        DB::table('certificados_organicos')->insert([
            [
                'slug' => 'senasag',
                'nombre' => 'Registro Sanitario y/o Fitosanitario (SENASAG)',
                'descripcion' => 'Registro sanitario o fitosanitario emitido por SENASAG.',
                'tipo' => 'obligatorio',
                'es_obligatorio' => true,
                'activo' => true,
                'orden' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'spg',
                'nombre' => 'Certificacion SPG (Sistema Participativo de Garantia - Sello Ecologico Nacional)',
                'descripcion' => 'Certificacion del Sistema Participativo de Garantia para produccion ecologica.',
                'tipo' => 'obligatorio',
                'es_obligatorio' => true,
                'activo' => true,
                'orden' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'globalgap',
                'nombre' => 'GlobalG.A.P.',
                'descripcion' => 'Buenas Practicas Agricolas.',
                'tipo' => 'opcional',
                'es_obligatorio' => false,
                'activo' => true,
                'orden' => 10,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'haccp_iso_22000',
                'nombre' => 'HACCP o ISO 22000',
                'descripcion' => 'Inocuidad para productos procesados o empacados.',
                'tipo' => 'opcional',
                'es_obligatorio' => false,
                'activo' => true,
                'orden' => 11,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'fairtrade',
                'nombre' => 'Certificacion Fairtrade (Comercio Justo)',
                'descripcion' => 'Valor social y mejor precio de venta.',
                'tipo' => 'opcional',
                'es_obligatorio' => false,
                'activo' => true,
                'orden' => 12,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'aopeb',
                'nombre' => 'Afiliacion a la AOPEB',
                'descripcion' => 'Asociacion de Organizaciones de Productores Ecologicos de Bolivia.',
                'tipo' => 'opcional',
                'es_obligatorio' => false,
                'activo' => true,
                'orden' => 13,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'cao',
                'nombre' => 'Afiliacion a la CAO',
                'descripcion' => 'Camara Agropecuaria del Oriente.',
                'tipo' => 'opcional',
                'es_obligatorio' => false,
                'activo' => true,
                'orden' => 14,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'cadex',
                'nombre' => 'Afiliacion a la CADEX',
                'descripcion' => 'Camara de Exportadores para logistica, tramites y networking comercial.',
                'tipo' => 'opcional',
                'es_obligatorio' => false,
                'activo' => true,
                'orden' => 15,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $obligatorios = DB::table('certificados_organicos')
            ->where('es_obligatorio', true)
            ->pluck('id');

        DB::table('organicos')->select('id')->orderBy('id')->chunk(100, function ($organicos) use ($obligatorios) {
            $now = now();
            $registros = [];

            foreach ($organicos as $organico) {
                foreach ($obligatorios as $certificadoId) {
                    $registros[] = [
                        'organico_id' => $organico->id,
                        'certificado_organico_id' => $certificadoId,
                        'estado' => 'pendiente',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if ($registros) {
                DB::table('organico_certificados')->insert($registros);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organico_certificados');
        Schema::dropIfExists('certificados_organicos');
        Schema::dropIfExists('ubicaciones_organicos');
    }
};
