<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caracteristicas_ganado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ganado_id')->unique()->constrained('ganados')->onDelete('cascade');
            $table->integer('edad')->nullable();
            $table->string('sexo')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });

        DB::table('ganados')
            ->select('id', 'edad', 'sexo', 'descripcion')
            ->orderBy('id')
            ->chunk(100, function ($ganados) {
                $now = now();

                foreach ($ganados as $ganado) {
                    DB::table('caracteristicas_ganado')->insert([
                        'ganado_id' => $ganado->id,
                        'edad' => $ganado->edad,
                        'sexo' => $ganado->sexo,
                        'descripcion' => $ganado->descripcion,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('caracteristicas_ganado');
    }
};
