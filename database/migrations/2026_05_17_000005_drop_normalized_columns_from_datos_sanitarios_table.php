<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            $columns = [
                'vacuna',
                'vacunado_fiebre_aftosa',
                'vacunado_antirabica',
                'tratamiento',
                'medicamento',
                'fecha_aplicacion',
                'proxima_fecha',
                'veterinario',
                'observaciones',
                'marca_ganado',
                'marca_ganado_foto',
                'senal_numero',
                'nombre_dueno',
                'carnet_dueno_foto',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('datos_sanitarios', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('datos_sanitarios', function (Blueprint $table) {
            if (!Schema::hasColumn('datos_sanitarios', 'vacuna')) {
                $table->string('vacuna')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'vacunado_fiebre_aftosa')) {
                $table->boolean('vacunado_fiebre_aftosa')->default(false);
            }
            if (!Schema::hasColumn('datos_sanitarios', 'vacunado_antirabica')) {
                $table->boolean('vacunado_antirabica')->default(false);
            }
            if (!Schema::hasColumn('datos_sanitarios', 'tratamiento')) {
                $table->string('tratamiento')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'medicamento')) {
                $table->string('medicamento')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'fecha_aplicacion')) {
                $table->date('fecha_aplicacion')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'proxima_fecha')) {
                $table->date('proxima_fecha')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'veterinario')) {
                $table->string('veterinario')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'observaciones')) {
                $table->text('observaciones')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'marca_ganado')) {
                $table->string('marca_ganado')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'marca_ganado_foto')) {
                $table->string('marca_ganado_foto')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'senal_numero')) {
                $table->string('senal_numero')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'nombre_dueno')) {
                $table->string('nombre_dueno')->nullable();
            }
            if (!Schema::hasColumn('datos_sanitarios', 'carnet_dueno_foto')) {
                $table->string('carnet_dueno_foto')->nullable();
            }
        });
    }
};
