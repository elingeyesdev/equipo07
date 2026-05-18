<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imagenes_certificado_campeon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->constrained('datos_sanitarios')->onDelete('cascade');
            $table->string('ruta');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('archivos_arbol_genealogico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->constrained('datos_sanitarios')->onDelete('cascade');
            $table->string('ruta');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        DB::table('datos_sanitarios')
            ->select('id', 'certificado_campeon_imagen', 'arbol_genealogico')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                $now = now();

                foreach ($registros as $registro) {
                    if (filled($registro->certificado_campeon_imagen)) {
                        DB::table('imagenes_certificado_campeon')->insert([
                            'dato_sanitario_id' => $registro->id,
                            'ruta' => $registro->certificado_campeon_imagen,
                            'orden' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }

                    if (filled($registro->arbol_genealogico)) {
                        DB::table('archivos_arbol_genealogico')->insert([
                            'dato_sanitario_id' => $registro->id,
                            'ruta' => $registro->arbol_genealogico,
                            'orden' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            });

        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->dropColumn(['certificado_campeon_imagen', 'arbol_genealogico']);
        });
    }

    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $table->string('certificado_campeon_imagen')->nullable();
            $table->string('arbol_genealogico')->nullable();
        });

        DB::table('imagenes_certificado_campeon')
            ->select('dato_sanitario_id', 'ruta')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                foreach ($registros as $registro) {
                    DB::table('datos_sanitarios')
                        ->where('id', $registro->dato_sanitario_id)
                        ->update(['certificado_campeon_imagen' => $registro->ruta]);
                }
            });

        DB::table('archivos_arbol_genealogico')
            ->select('dato_sanitario_id', 'ruta')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                foreach ($registros as $registro) {
                    DB::table('datos_sanitarios')
                        ->where('id', $registro->dato_sanitario_id)
                        ->update(['arbol_genealogico' => $registro->ruta]);
                }
            });

        Schema::dropIfExists('archivos_arbol_genealogico');
        Schema::dropIfExists('imagenes_certificado_campeon');
    }
};
