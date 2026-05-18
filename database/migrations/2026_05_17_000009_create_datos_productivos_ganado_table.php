<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_productivos_ganado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->unique()->constrained('ganados')->onDelete('cascade');
            $table->foreignId('tipo_peso_id')->nullable()->constrained('tipo_pesos')->onDelete('set null');
            $table->decimal('peso_actual', 10, 2)->nullable();
            $table->decimal('cantidad_leche_dia', 8, 2)->nullable();
            $table->timestamps();
        });

        DB::table('ganados')
            ->select('id', 'tipo_peso_id', 'peso_actual', 'cantidad_leche_dia')
            ->orderBy('id')
            ->chunk(100, function ($ganados) {
                $now = now();

                foreach ($ganados as $ganado) {
                    DB::table('datos_productivos_ganado')->insert([
                        'ganado_id' => $ganado->id,
                        'tipo_peso_id' => $ganado->tipo_peso_id,
                        'peso_actual' => $ganado->peso_actual,
                        'cantidad_leche_dia' => $ganado->cantidad_leche_dia,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('datos_productivos_ganado');
    }
};
