<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes_dato_sanitario_vacunaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_vacunacion_id')
                ->constrained('dato_sanitario_vacunaciones')
                ->onDelete('cascade');
            $table->string('ruta');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        if (Schema::hasColumn('datos_sanitarios', 'certificado_imagen')) {
            DB::table('datos_sanitarios')
                ->join('dato_sanitario_vacunaciones', 'datos_sanitarios.id', '=', 'dato_sanitario_vacunaciones.dato_sanitario_id')
                ->whereNotNull('datos_sanitarios.certificado_imagen')
                ->orderBy('datos_sanitarios.id')
                ->select('dato_sanitario_vacunaciones.id as vacunacion_id', 'datos_sanitarios.certificado_imagen')
                ->chunk(100, function ($registros) {
                    $now = now();

                    foreach ($registros as $registro) {
                        DB::table('imagenes_dato_sanitario_vacunaciones')->insert([
                            'dato_sanitario_vacunacion_id' => $registro->vacunacion_id,
                            'ruta' => $registro->certificado_imagen,
                            'orden' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                });

            Schema::table('datos_sanitarios', function (Blueprint $table) {
                $table->dropColumn('certificado_imagen');
            });
        }
    }

    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            if (!Schema::hasColumn('datos_sanitarios', 'certificado_imagen')) {
                $table->string('certificado_imagen')->nullable();
            }
        });

        if (Schema::hasTable('imagenes_dato_sanitario_vacunaciones')) {
            DB::table('imagenes_dato_sanitario_vacunaciones')
                ->join('dato_sanitario_vacunaciones', 'imagenes_dato_sanitario_vacunaciones.dato_sanitario_vacunacion_id', '=', 'dato_sanitario_vacunaciones.id')
                ->select('dato_sanitario_vacunaciones.dato_sanitario_id', 'imagenes_dato_sanitario_vacunaciones.ruta')
                ->orderBy('imagenes_dato_sanitario_vacunaciones.id')
                ->chunk(100, function ($registros) {
                    foreach ($registros as $registro) {
                        DB::table('datos_sanitarios')
                            ->where('id', $registro->dato_sanitario_id)
                            ->update(['certificado_imagen' => $registro->ruta]);
                    }
                });
        }

        Schema::dropIfExists('imagenes_dato_sanitario_vacunaciones');
    }
};
