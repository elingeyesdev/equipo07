<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_comerciales_ganado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->unique()->constrained('ganados')->onDelete('cascade');
            $table->decimal('precio', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->date('fecha_publicacion')->nullable();
            $table->timestamps();
        });

        DB::table('ganados')
            ->select('id', 'precio', 'stock', 'fecha_publicacion')
            ->orderBy('id')
            ->chunk(100, function ($ganados) {
                $now = now();

                foreach ($ganados as $ganado) {
                    DB::table('datos_comerciales_ganado')->insert([
                        'ganado_id' => $ganado->id,
                        'precio' => $ganado->precio,
                        'stock' => $ganado->stock ?? 0,
                        'fecha_publicacion' => $ganado->fecha_publicacion,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('datos_comerciales_ganado');
    }
};
