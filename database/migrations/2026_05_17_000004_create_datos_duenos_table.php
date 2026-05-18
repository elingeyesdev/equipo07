<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('datos_duenos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->unique()->constrained('datos_sanitarios')->onDelete('cascade');
            $table->string('nombre_dueno')->nullable();
            $table->string('carnet_dueno_foto')->nullable();
            $table->timestamps();
        });

        DB::table('datos_sanitarios')
            ->select('id', 'nombre_dueno', 'carnet_dueno_foto')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                $now = now();

                foreach ($registros as $registro) {
                    if (blank($registro->nombre_dueno) && blank($registro->carnet_dueno_foto)) {
                        continue;
                    }

                    DB::table('datos_duenos')->insert([
                        'dato_sanitario_id' => $registro->id,
                        'nombre_dueno' => $registro->nombre_dueno,
                        'carnet_dueno_foto' => $registro->carnet_dueno_foto,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('datos_duenos');
    }
};
