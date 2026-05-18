<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logros_reconocimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->unique()->constrained('datos_sanitarios')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('belleza_estructuras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logro_reconocimiento_id')->unique()->constrained('logros_reconocimientos')->onDelete('cascade');
            $table->boolean('logro_campeon_raza')->default(false);
            $table->boolean('logro_gran_campeon_macho')->default(false);
            $table->boolean('logro_gran_campeon_hembra')->default(false);
            $table->boolean('logro_mejor_ubre')->default(false);
            $table->timestamps();
        });

        Schema::create('produccion_leches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logro_reconocimiento_id')->unique()->constrained('logros_reconocimientos')->onDelete('cascade');
            $table->boolean('logro_campeona_litros_dia')->default(false);
            $table->boolean('logro_mejor_lactancia')->default(false);
            $table->boolean('logro_mejor_calidad_leche')->default(false);
            $table->timestamps();
        });

        Schema::create('produccion_carnes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logro_reconocimiento_id')->unique()->constrained('logros_reconocimientos')->onDelete('cascade');
            $table->boolean('logro_mejor_novillo')->default(false);
            $table->boolean('logro_gran_campeon_carne')->default(false);
            $table->boolean('logro_mejor_semental')->default(false);
            $table->timestamps();
        });

        Schema::create('reproduccion_logros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logro_reconocimiento_id')->unique()->constrained('logros_reconocimientos')->onDelete('cascade');
            $table->boolean('logro_mejor_madre')->default(false);
            $table->boolean('logro_mejor_padre')->default(false);
            $table->boolean('logro_mejor_fertilidad')->default(false);
            $table->timestamps();
        });

        DB::table('datos_sanitarios')
            ->select([
                'id',
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
            ])
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                $now = now();

                foreach ($registros as $registro) {
                    $logroId = DB::table('logros_reconocimientos')->insertGetId([
                        'dato_sanitario_id' => $registro->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('belleza_estructuras')->insert([
                        'logro_reconocimiento_id' => $logroId,
                        'logro_campeon_raza' => (bool) $registro->logro_campeon_raza,
                        'logro_gran_campeon_macho' => (bool) $registro->logro_gran_campeon_macho,
                        'logro_gran_campeon_hembra' => (bool) $registro->logro_gran_campeon_hembra,
                        'logro_mejor_ubre' => (bool) $registro->logro_mejor_ubre,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('produccion_leches')->insert([
                        'logro_reconocimiento_id' => $logroId,
                        'logro_campeona_litros_dia' => (bool) $registro->logro_campeona_litros_dia,
                        'logro_mejor_lactancia' => (bool) $registro->logro_mejor_lactancia,
                        'logro_mejor_calidad_leche' => (bool) $registro->logro_mejor_calidad_leche,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('produccion_carnes')->insert([
                        'logro_reconocimiento_id' => $logroId,
                        'logro_mejor_novillo' => (bool) $registro->logro_mejor_novillo,
                        'logro_gran_campeon_carne' => (bool) $registro->logro_gran_campeon_carne,
                        'logro_mejor_semental' => (bool) $registro->logro_mejor_semental,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    DB::table('reproduccion_logros')->insert([
                        'logro_reconocimiento_id' => $logroId,
                        'logro_mejor_madre' => (bool) $registro->logro_mejor_madre,
                        'logro_mejor_padre' => (bool) $registro->logro_mejor_padre,
                        'logro_mejor_fertilidad' => (bool) $registro->logro_mejor_fertilidad,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });

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
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->boolean('logro_campeon_raza')->default(false);
            $table->boolean('logro_gran_campeon_macho')->default(false);
            $table->boolean('logro_gran_campeon_hembra')->default(false);
            $table->boolean('logro_mejor_ubre')->default(false);
            $table->boolean('logro_campeona_litros_dia')->default(false);
            $table->boolean('logro_mejor_lactancia')->default(false);
            $table->boolean('logro_mejor_calidad_leche')->default(false);
            $table->boolean('logro_mejor_novillo')->default(false);
            $table->boolean('logro_gran_campeon_carne')->default(false);
            $table->boolean('logro_mejor_semental')->default(false);
            $table->boolean('logro_mejor_madre')->default(false);
            $table->boolean('logro_mejor_padre')->default(false);
            $table->boolean('logro_mejor_fertilidad')->default(false);
        });

        Schema::dropIfExists('reproduccion_logros');
        Schema::dropIfExists('produccion_carnes');
        Schema::dropIfExists('produccion_leches');
        Schema::dropIfExists('belleza_estructuras');
        Schema::dropIfExists('logros_reconocimientos');
    }
};
