<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tratamientos_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->unique()->constrained('datos_sanitarios')->onDelete('cascade');
            $table->string('tratamiento')->nullable();
            $table->string('medicamento')->nullable();
            $table->date('fecha_aplicacion')->nullable();
            $table->date('proxima_fecha')->nullable();
            $table->string('veterinario')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        DB::table('datos_sanitarios')
            ->select('id', 'tratamiento', 'medicamento', 'fecha_aplicacion', 'proxima_fecha', 'veterinario', 'observaciones')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                $now = now();

                foreach ($registros as $registro) {
                    if (
                        blank($registro->tratamiento) &&
                        blank($registro->medicamento) &&
                        blank($registro->fecha_aplicacion) &&
                        blank($registro->proxima_fecha) &&
                        blank($registro->veterinario) &&
                        blank($registro->observaciones)
                    ) {
                        continue;
                    }

                    DB::table('tratamientos_medicamentos')->insert([
                        'dato_sanitario_id' => $registro->id,
                        'tratamiento' => $registro->tratamiento,
                        'medicamento' => $registro->medicamento,
                        'fecha_aplicacion' => $registro->fecha_aplicacion,
                        'proxima_fecha' => $registro->proxima_fecha,
                        'veterinario' => $registro->veterinario,
                        'observaciones' => $registro->observaciones,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('tratamientos_medicamentos');
    }
};
