<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marcas_animales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->unique()->constrained('datos_sanitarios')->onDelete('cascade');
            $table->string('marca_ganado')->nullable();
            $table->string('senal_numero')->nullable();
            $table->timestamps();
        });

        Schema::create('imagenes_marca_ganado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marca_animal_id')->constrained('marcas_animales')->onDelete('cascade');
            $table->string('ruta');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        DB::table('datos_sanitarios')
            ->select('id', 'marca_ganado', 'senal_numero', 'marca_ganado_foto')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                $now = now();

                foreach ($registros as $registro) {
                    if (blank($registro->marca_ganado) && blank($registro->senal_numero) && blank($registro->marca_ganado_foto)) {
                        continue;
                    }

                    $marcaId = DB::table('marcas_animales')->insertGetId([
                        'dato_sanitario_id' => $registro->id,
                        'marca_ganado' => $registro->marca_ganado,
                        'senal_numero' => $registro->senal_numero,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    if (filled($registro->marca_ganado_foto)) {
                        DB::table('imagenes_marca_ganado')->insert([
                            'marca_animal_id' => $marcaId,
                            'ruta' => $registro->marca_ganado_foto,
                            'orden' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('imagenes_marca_ganado');
        Schema::dropIfExists('marcas_animales');
    }
};
