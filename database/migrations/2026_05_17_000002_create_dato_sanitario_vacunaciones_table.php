<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dato_sanitario_vacunaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dato_sanitario_id')->unique()->constrained('datos_sanitarios')->onDelete('cascade');
            $table->string('vacuna')->nullable();
            $table->boolean('vacunado_fiebre_aftosa')->default(false);
            $table->boolean('vacunado_antirabica')->default(false);
            $table->timestamps();
        });

        DB::table('datos_sanitarios')
            ->select('id', 'vacuna', 'vacunado_fiebre_aftosa', 'vacunado_antirabica')
            ->orderBy('id')
            ->chunk(100, function ($registros) {
                $now = now();

                foreach ($registros as $registro) {
                    DB::table('dato_sanitario_vacunaciones')->insert([
                        'dato_sanitario_id' => $registro->id,
                        'vacuna' => $registro->vacuna,
                        'vacunado_fiebre_aftosa' => (bool) $registro->vacunado_fiebre_aftosa,
                        'vacunado_antirabica' => (bool) $registro->vacunado_antirabica,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('dato_sanitario_vacunaciones');
    }
};
